<?php

namespace App\Http\Traits;

use App\Models\Cost;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait PaymentFixer
{
    protected $external_id = "";
    protected $payment_link = "";
    protected $invoice_success = "";

    protected function pay($costId, $isLoggedIn = true, $user = null, $url = null)
    {
        $cost = Cost::findOrFail($costId);
        $description = $this->description($costId);

        if ($url) {
            $this->invoice_success = $url;
        } else {
            $this->invoice_success = url('invoice/success');
        }

        $secret_key = 'Basic ' . config('xendit.key_auth');
        $this->external_id = Str::random(10);
        $data_request = Http::withHeaders([
            'Authorization' => $secret_key
        ])->post('https://api.xendit.co/v2/invoices', [
            'external_id' => $this->external_id,
            'amount' => $cost->nominal_price,
            'success_redirect_url' => $this->invoice_success
        ]);
        $response = $data_request->object();

        if ($isLoggedIn) {
            Payment::create([
                'uuid' => Str::uuid(),
                'user_id' => Auth::user()->id,
                'external_id' => $this->external_id,
                'description' => $description,
                'amount' => $cost->nominal_price,
                'payment_link' => $response->invoice_url,
                'status' => $response->status,
            ]);
        } else {
            Payment::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'external_id' => $this->external_id,
                'description' => $description,
                'amount' => $cost->nominal_price,
                'payment_link' => $response->invoice_url,
                'status' => $response->status,
            ]);
        }

        $this->payment_link = $response->invoice_url;
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
            $description = "pembayaran tempat uji kompetensi";
        }

        return $description;
    }
}
