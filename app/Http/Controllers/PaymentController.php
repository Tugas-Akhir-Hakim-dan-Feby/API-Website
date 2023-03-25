<?php

namespace App\Http\Controllers;

use App\Repositories\Payment\PaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function callback(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $payment = $this->paymentRepository->findByCriteria(["external_id" => $request->external_id]);

            return $payment->update(['status' => $request->status]);
        });
    }
}
