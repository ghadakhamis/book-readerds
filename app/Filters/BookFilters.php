<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BookFilters extends QueryFilters
{
    public function __construct(Request $request)
    {
        $this->filters    = ['search', 'sort_read_pages'];
        parent::__construct($request);
    }
  
    public function search($value) {
            return $this->builder->where(function (Builder $query) use ($value) {
                return $query->where('name', 'LIKE', '%'.$value.'%')
                    ->orWhere('author', 'LIKE', '%'.$value.'%');
            });
    }

    /**
     * Sort the book by the most read pages first.
     *
     */
    public function sort_read_pages()
    {
        $this->builder->orderBy('is_read', 'desc')
            ->orderBy('read_pages_count', 'desc');
    }
}