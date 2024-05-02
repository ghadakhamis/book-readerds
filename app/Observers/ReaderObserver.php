<?php

namespace App\Observers;
use App\Models\Reader;
use Illuminate\Support\Facades\Auth;

class ReaderObserver
{
    /**
     * Handle the reader "creating" event.
     */
    public function creating(Reader $reader): void
    {
        $reader->user_id = Auth::id()?? $reader->user_id;
    }
}
