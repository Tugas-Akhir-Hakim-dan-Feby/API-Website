<?php

namespace App\Http\Resources\Payment;

use App\Http\Traits\MessageFixer;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentDetail extends JsonResource
{
    use MessageFixer;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->detailMessage();
    }
}