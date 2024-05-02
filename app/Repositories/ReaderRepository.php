<?php

namespace App\Repositories;

use App\Models\Reader;

class ReaderRepository extends BaseRepository
{
    public function __construct(Reader $model)
    {
        parent::__construct($model);
    }
}
