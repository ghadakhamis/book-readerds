<?php

namespace App\Jobs;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateBookReadPages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $book;

    /**
     * Create a new job instance.
     */
    public function __construct(Book $book)
    {
        $this->onQueue('books');
        $this->book = $book;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $result = [];
        $readers = $this->book->readers->sortBy([['start_page', 'asc'], ['end_page', 'desc']]);

        foreach ($readers as $reader) {
            $resultLength = count($result);
           if (!$resultLength || $this->isOutOfRang($reader, $result[$resultLength - 1])) {
                array_push($result, [
                    'start_page'  => $reader->start_page,
                    'end_page'    => $reader->end_page,
                    'pages_count' => $reader->end_page - $reader->start_page + 1,
                ]);
            } else if ($this->isStartPageOverlapping($reader->start_page, $result[$resultLength - 1])
                && $reader->end_page > $result[$resultLength - 1]['end_page']) {
                $result[$resultLength - 1]['end_page']    = $reader->end_page;
                $result[$resultLength - 1]['pages_count'] = $reader->end_page - $result[$resultLength - 1]['start_page'] + 1;
            }
        }

        $pages = collect($result)->sum('pages_count');
        $this->book->update(['read_pages_count' => $pages, 'is_read' => $this->book->pages_count == $pages]);
    }

    private function isOutOfRang($reader, $lastResult)
    {
        return $reader->start_page > $lastResult['end_page'] - 1;
    }

    private function isStartPageOverlapping($startPage, $lastResult)
    {
        return $startPage > $lastResult['start_page'] && $startPage - 1 <= $lastResult['end_page'];
    }
}
