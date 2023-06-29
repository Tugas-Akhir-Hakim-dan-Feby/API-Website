<?php

namespace App\Http\Controllers;

use App\Repositories\Payment\PaymentRepository;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintController extends Controller
{
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function __invoke($externalId)
    {
        $payment = $this->paymentRepository->findByCriteria(["external_id" => $externalId]);

        $pdf = Pdf::loadView('print.invoice', compact('payment'));
        return $pdf->download("$payment->external_id.pdf");
        // return view('print.invoice', compact('payment'));
    }
}
