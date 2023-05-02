<?php

namespace App\Http\Traits;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait PaymentFixer
{
    protected function pay()
    {
        $secret_key = 'Basic ' . config('xendit.key_auth');
        $external_id = Str::random(10);
        $data_request = Http::withHeaders([
            'Authorization' => $secret_key
        ])->post('https://api.xendit.co/v2/invoices', [
            'external_id' => $external_id,
            'amount' => 100000
        ]);
        $response = $data_request->object();

        Payment::create([
            'uuid' => Str::uuid(),
            'user_id' => Auth::user()->id,
            'external_id' => $external_id,
            'description' => 'pembayaran welder member',
            'amount' => 100000,
            'payment_link' => $response->invoice_url,
            'status' => $response->status,
        ]);
    }
}
