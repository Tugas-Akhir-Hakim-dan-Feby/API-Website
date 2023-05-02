<?php

namespace App\Http\Controllers;

use App\Http\Resources\Payment\PaymentDetail;
use App\Http\Traits\MessageFixer;
use App\Models\Payment;
use App\Repositories\Payment\PaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    use MessageFixer;

    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function callback(Request $request)
    {
        DB::beginTransaction();

        $payment = $this->paymentRepository->findByCriteria(["external_id" => $request->external_id]);

        try {
            $payment->update(['status' => $request->status]);

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $payment);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show($externalId)
    {
        $payment = $this->paymentRepository->findByCriteria(["external_id" => $externalId]);
        if (!$payment) {
            abort(404);
        }

        if ($payment->status == Payment::PAID) {
            return $this->successMessage($payment->status, $payment);
        }

        $payment->load('user');

        return new PaymentDetail($payment);
    }
}
