<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'author'           => $this->author,
            'pages_count'      => $this->pages_count,
            'read_pages_count' => $this->read_pages_count,
            'is_read'          => $this->is_read,
        ];;
    }
}
