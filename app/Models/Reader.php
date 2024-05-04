<?php

namespace App\Models;

use App\Observers\ReaderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

#[ObservedBy([ReaderObserver::class])]
class Reader extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['book_id', 'user_id', 'start_page', 'end_page'];

    /**
     * Get the user that read the book.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book that the user has read.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function scopeBookId(Builder $query, $value): void
    {
        $query->where('book_id', $value);
    }

    public function scopeStartPage(Builder $query, $value): void
    {
        $query->where('start_page', $value);
    }

    public function scopeEndPage(Builder $query, $value): void
    {
        $query->where('end_page', $value);
    }
}
