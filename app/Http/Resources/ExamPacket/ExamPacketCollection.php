<?php

namespace App\Http\Resources\ExamPacket;

use App\Http\Traits\MessageFixer;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExamPacketCollection extends ResourceCollection
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
