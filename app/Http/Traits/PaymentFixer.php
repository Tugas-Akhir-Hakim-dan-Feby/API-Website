<?php

namespace App\Http\Traits;

use App\Models\Cost;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait PaymentFixer
{
    protected function pay($costId)
    {
        $cost = Cost::findOrFail($costId);
        $description = $this->description($costId);

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
            'description' => $description,
            'amount' => $cost->nominal_price,
            'payment_link' => $response->invoice_url,
            'status' => $response->status,
        ]);
    }

    protected function description($costId)
    {
        $description = "pembayaran";

        if ($costId == Cost::WELDER_MEMBER) {
            $description = "pembayaran welder member";
        }

        if ($costId == Cost::COMPANY_MEMBER) {
            $description = "pembayaran perusahaan member";
        }

        if ($costId == Cost::ADVERTISEMENT) {
            $description = "pembayaran iklan";
        }

        if ($costId == Cost::TEST_INSTITUTION) {
            $description = "pembayaran lembaga sertfikasi";
        }

        return $description;
    }
}
