<?php

namespace App\Repositories\Payment;

use App\Http\Traits\PaymentFixer;
use App\Mail\SendReminderPayment;
use App\Models\Cost;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class PaymentRepositoryImplement extends Eloquent implements PaymentRepository
{
    use PaymentFixer;
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->query();
    }

    public function findByCriteria(array $data)
    {
        return $this->model->where($data)->first();
    }

    public function paymentUsers()
    {
        $payments = $this->model->get();

        foreach ($payments as $payment) {
            $expirationDate = Carbon::parse($payment->created_at)->addMonth();

            if (Carbon::now()->gt($expirationDate) && $payment->status == Payment::PAID) {
                if ($payment->user->onlyRoles([User::MEMBER_WELDER, User::PAKAR]) && !$payment->isRecreated()) {
                    $this->pay(Cost::WELDER_MEMBER, false, $payment->user);
                    Mail::to($payment->user->email)->send(new SendReminderPayment());
                    $payment->markAsRecreated();
                }

                if ($payment->user->onlyRoles([User::MEMBER_COMPANY]) && !$payment->isRecreated()) {
                    $this->pay(Cost::COMPANY_MEMBER, false, $payment->user);
                    Mail::to($payment->user->email)->send(new SendReminderPayment());
                    $payment->markAsRecreated();
                }
            }
        }
    }
}
