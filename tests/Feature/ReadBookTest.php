<?php

namespace Tests\Feature;

use App\Jobs\CalculateBookReadPages;
use App\Jobs\SendSMS;
use App\Models\Book;
use App\Models\Reader;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ReadBookTest extends TestCase
{
    public function test_fail_read_book_without_body_and_authinticate(): void
    {
        /** @var Book $book */
        $book     = Book::factory()->create();

        $this->json('POST', route('books.readers.store', ['book' => $book->id]), [])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_fail_read_book_without_body(): void
    {
        /** @var Book $book */
        $book     = Book::factory()->create();
        /** @var User $user */
        $user     = User::factory()->create();

        $this->actingAs($user, 'user')
            ->json('POST', route('books.readers.store', ['book' => $book->id]), [])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['start_page', 'end_page'], 'errors');
    }

    public function test_fail_read_book_with_not_exist_book(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        $body     = ['start_page' => rand(1, 50), 'end_page' => rand(50, 100)];

        $this->actingAs($user, 'user')
            ->json('POST', route('books.readers.store', ['book' => rand(100, 300)]), $body)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_fail_read_book_without_start_page(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        /** @var Book $book */
        $book     = Book::factory()->create();
        $body     = ['end_page' => rand(50, 100)];

        $this->actingAs($user, 'user')
            ->json('POST', route('books.readers.store', ['book' => $book->id]), $body)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['start_page'], 'errors');
    }

    public function test_fail_read_book_without_end_page(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        /** @var Book $book */
        $book     = Book::factory()->create();
        $body     = ['start_page' => rand(1, 50)];

        $this->actingAs($user, 'user')
            ->json('POST', route('books.readers.store', ['book' => $book->id]), $body)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['end_page'], 'errors');
    }

    public function test_fail_read_book_with_invalid_min_start_page(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        /** @var Book $book */
        $book     = Book::factory()->create();
        $body     = ['start_page' => rand(-50, 0), 'end_page' => rand(50, 100)];

        $this->actingAs($user, 'user')
            ->json('POST', route('books.readers.store', ['book' => $book->id]), $body)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['start_page'], 'errors');
    }

    public function test_fail_read_book_with_invalid_max_start_page(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        /** @var Book $book */
        $book     = Book::factory()->create();
        $body     = ['start_page' => $book->pages_count + rand(1, 50), 'end_page' => rand(50, 100)];

        $this->actingAs($user, 'user')
            ->json('POST', route('books.readers.store', ['book' => $book->id]), $body)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['start_page'], 'errors');
    }

    public function test_fail_read_book_with_invalid_max_end_page(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        /** @var Book $book */
        $book     = Book::factory()->create();
        $body     = ['start_page' => rand(1, 50), 'end_page' => $book->pages_count + rand(1, 50)];

        $this->actingAs($user, 'user')
            ->json('POST', route('books.readers.store', ['book' => $book->id]), $body)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['end_page'], 'errors');
    }

    public function test_fail_read_book_with_invalid_min_end_page(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        /** @var Book $book */
        $book     = Book::factory()->create();
        $body     = ['start_page' => rand(30, 50), 'end_page' => rand(1, 20)];

        $this->actingAs($user, 'user')
            ->json('POST', route('books.readers.store', ['book' => $book->id]), $body)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['end_page'], 'errors');
    }

    public function test_fail_read_book_with_the_same_interval(): void
    {
        /** @var User $user */
        $user     = User::factory()->create();
        /** @var Book $book */
        $book     = Book::factory()->create();
        /** @var Reader $reader */
        $reader   = Reader::factory()->create(['book_id' => $book->id, 'user_id' => $user->id]);
        $body     = ['start_page' => $reader->start_page, 'end_page' => $reader->end_page];

        $this->actingAs($user, 'user')
            ->json('POST', route('books.readers.store', ['book' => $book->id]), $body)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['start_page'], 'errors');
    }

    public function test_success_read_book(): void
    {
        Queue::fake();
        /** @var User $user */
        $user     = User::factory()->create();
        /** @var Book $book */
        $book     = Book::factory()->create(['read_pages_count' => 0]);
        $body     = ['start_page' => rand(1, 50), 'end_page' => rand(50, 100)];

        $this->assertDatabaseHas(Book::class, [
            'id'               => $book->id,
            'read_pages_count' => 0,
            'is_read'          => false,
        ]);

        $this->actingAs($user, 'user')
            ->json('POST', route('books.readers.store', ['book' => $book->id]), $body)
            ->assertStatus(Response::HTTP_CREATED);
        
        $this->assertDatabaseHas(Reader::class, [
                'book_id'    => $book->id,
                'user_id'    => $user->id,
                'start_page' => $body['start_page'],
                'end_page'   => $body['end_page'],
            ]);

        Queue::assertPushed(CalculateBookReadPages::class);
        Queue::assertPushed(SendSMS::class);

        (new CalculateBookReadPages($book))->handle();

        $this->assertDatabaseHas(Book::class, [
            'id'               => $book->id,
            'read_pages_count' => $body['end_page'] - $body['start_page'] + 1,
            'is_read'          => 0,
        ]);
    }
}