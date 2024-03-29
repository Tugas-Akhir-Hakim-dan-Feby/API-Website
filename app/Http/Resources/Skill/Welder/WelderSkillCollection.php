<?php

namespace App\Http\Resources\Skill\Welder;

use App\Http\Traits\MessageFixer;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WelderSkillCollection extends ResourceCollection
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
