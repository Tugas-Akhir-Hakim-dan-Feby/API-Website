<?php

namespace App\Http\Resources\WelderAnswer;

use Illuminate\Http\Resources\Json\JsonResource;

class WelderAnswerCorrection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
