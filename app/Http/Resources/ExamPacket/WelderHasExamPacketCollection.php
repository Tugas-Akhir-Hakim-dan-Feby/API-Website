<?php

namespace App\Http\Resources\ExamPacket;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WelderHasExamPacketCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}