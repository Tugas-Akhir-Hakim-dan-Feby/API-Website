<?php

namespace App\Http\Facades;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentFacade
{
    protected $external_id;
    protected $apiUrl = "https://api.xendit.co/v2/invoices";

    public function __construct()
    {
        $this->external_id = (string) mt_rand(000000000, 999999999);
    }

    public function processApi($params)
    {
        $params["locale"] = "id";
        $params["currency"] = "IDR";

        return Http::withHeaders([
            'Authorization' => 'Basic ' . config('xendit.key_auth')
        ])->post('https://api.xendit.co/v2/invoices', $params);
    }

    public function payToPersonalMember($cost, $url)
    {
        $data_request = $this->processApi([
            'external_id' => $this->external_id,
            'amount' => $cost->nominal_price,
            'success_redirect_url' => $url,
            'invoice_duration' => 86400,
        ]);
        $response = $data_request->object();

        return Payment::create([
            'uuid' => Str::uuid(),
            'user_id' => auth()->user()->id,
            'external_id' => $this->external_id,
            'description' => "Pembayaran Personal Member",
            'amount' => $cost->nominal_price,
            'payment_link' => $response->invoice_url,
            'status' => $response->status,
            'cost_id' => $cost->id
        ]);
    }

    public function updatePayToPersonalMember($payment, $payload)
    {
        $data_request = $this->processApi([
            'external_id' => $this->external_id,
            'amount' => $payload->amount,
            'success_redirect_url' => $payload->success_redirect_url,
            'invoice_duration' => 259200,
        ]);
        $response = $data_request->object();

        return Payment::create([
            'uuid' => Str::uuid(),
            'user_id' => $payment->user_id,
            'external_id' => $this->external_id,
            'description' => "Pembayaran Personal Member",
            'amount' => $payload->amount,
            'payment_link' => $response->invoice_url,
            'status' => $response->status,
            'cost_id' => $payment->cost_id
        ]);
    }

    public function payToCompanyMember($cost, $url)
    {
        $data_request = $this->processApi([
            'external_id' => $this->external_id,
            'amount' => $cost->nominal_price,
            'success_redirect_url' => $url,
            'invoice_duration' => 86400,
        ]);
        $response = $data_request->object();

        return Payment::create([
            'uuid' => Str::uuid(),
            'user_id' => auth()->user()->id,
            'external_id' => $this->external_id,
            'description' => "Pembayaran Perusahaan Member",
            'amount' => $cost->nominal_price,
            'payment_link' => $response->invoice_url,
            'status' => $response->status,
            'cost_id' => $cost->id
        ]);
    }

    public function updatePayToCompanyMember($payment, $payload)
    {
        $data_request = $this->processApi([
            'external_id' => $this->external_id,
            'amount' => $payload->amount,
            'success_redirect_url' => $payload->success_redirect_url,
            'invoice_duration' => 259200,
        ]);
        $response = $data_request->object();

        return Payment::create([
            'uuid' => Str::uuid(),
            'user_id' => $payment->user_id,
            'external_id' => $this->external_id,
            'description' => "Pembayaran Company Member",
            'amount' => $payload->amount,
            'payment_link' => $response->invoice_url,
            'status' => $response->status,
            'cost_id' => $payment->cost_id
        ]);
    }

    public function payToOperator($cost, $url)
    {
        $data_request = $this->processApi([
            'external_id' => $this->external_id,
            'amount' => $cost->nominal_price,
            'success_redirect_url' => $url,
            'invoice_duration' => 86400,
        ]);
        $response = $data_request->object();

        return Payment::create([
            'uuid' => Str::uuid(),
            'user_id' => auth()->user()->id,
            'external_id' => $this->external_id,
            'description' => "Pembayaran TUK Member",
            'amount' => $cost->nominal_price,
            'payment_link' => $response->invoice_url,
            'status' => $response->status,
            'cost_id' => $cost->id
        ]);
    }

    public function updatePayToOperator($payment, $payload)
    {
        $data_request = $this->processApi([
            'external_id' => $this->external_id,
            'amount' => $payload->amount,
            'success_redirect_url' => $payload->success_redirect_url,
            'invoice_duration' => 259200,
        ]);
        $response = $data_request->object();

        return Payment::create([
            'uuid' => Str::uuid(),
            'user_id' => $payment->user_id,
            'external_id' => $this->external_id,
            'description' => "Pembayaran TUK Member",
            'amount' => $payload->amount,
            'payment_link' => $response->invoice_url,
            'status' => $response->status,
            'cost_id' => $payment->cost_id
        ]);
    }

    public function payToAdvertisement($cost, $url)
    {
        $data_request = $this->processApi([
            'external_id' => $this->external_id,
            'amount' => $cost->nominal_price,
            'success_redirect_url' => $url,
            'invoice_duration' => 86400,
        ]);
        $response = $data_request->object();

        return Payment::create([
            'uuid' => Str::uuid(),
            'user_id' => auth()->user()->id,
            'external_id' => $this->external_id,
            'description' => "Pembayaran Iklan",
            'amount' => $cost->nominal_price,
            'payment_link' => $response->invoice_url,
            'status' => $response->status,
            'cost_id' => $cost->id
        ]);
    }

    public function updatePayToAdvertisement($payment, $payload)
    {
        $data_request = $this->processApi([
            'external_id' => $this->external_id,
            'amount' => $payload->amount,
            'success_redirect_url' => $payload->success_redirect_url,
            'invoice_duration' => 259200,
        ]);
        $response = $data_request->object();

        $payment->advertisement()->update([
            "external_id" => $this->external_id
        ]);

        return $payment->update([
            "external_id" => $this->external_id,
            "status" => Payment::PENDING,
            "payment_link" => $response->invoice_url
        ]);
    }
}
