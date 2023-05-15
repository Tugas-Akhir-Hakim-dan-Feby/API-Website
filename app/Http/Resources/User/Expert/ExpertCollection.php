<?php

namespace App\Http\Resources\User\Expert;

use App\Http\Traits\MessageFixer;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpertCollection extends ResourceCollection
{
    use MessageFixer;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collectionMessage();
    }
}
