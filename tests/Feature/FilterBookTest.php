<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Reader;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FilterBookTest extends TestCase
{
    public function test_success_list_books_without_authinticate(): void
    {
        $this->json('GET', route('books.index'), [])
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_success_list_books_with_authinticate(): void
    {
         /** @var User $user */
        $user     = User::factory()->create();
        $this->actingAs($user, 'user')->json('GET', route('books.index'), [])
            ->assertStatus(Response::HTTP_OK);
    }
}