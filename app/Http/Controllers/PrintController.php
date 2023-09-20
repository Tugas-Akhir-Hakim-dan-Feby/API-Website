<?php

namespace App\Http\Controllers;

use App\Models\WelderSkill;
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

    public function invoice($externalId)
    {
        $payment = $this->paymentRepository->findByCriteria(["external_id" => $externalId]);

        $pdf = Pdf::loadView('print.invoice', compact('payment'));
        // return $pdf->download("$payment->external_id.pdf");
        // return view('print.invoice', compact('payment'));
        return $pdf->stream();
    }

    public function chartSkill()
    {
        $data = [
            "skills" => WelderSkill::all()
        ];

        // return view('print.chartSkill', $data);
        $pdf = Pdf::loadView('print.chartSkill', $data);
        return $pdf->stream();
    }
}
