<?php

namespace App\Console\Commands;

use App\Http\Traits\PaymentFixer;
use App\Mail\SendReminderPayment;
use App\Models\Cost;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckPaymentRenewal extends Command
{
    use PaymentFixer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:check-renewal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $payments = Payment::all();

        foreach ($payments as $payment) {
            $expirationDate = Carbon::parse($payment->created_at)->addMonth();

            if (Carbon::now()->gt($expirationDate) && $payment->status == Payment::PAID) {
                if ($payment->user->onlyRoles([User::MEMBER_INDIVIDUAL, User::EXPERT]) && !$payment->isRecreated()) {
                    $this->pay(Cost::WELDER_MEMBER, false, $payment->user);
                    Mail::to($payment->user->email)->send(new SendReminderPayment());
                    $payment->markAsRecreated();
                }

                if ($payment->user->onlyRoles([User::MEMBER_COMPANY]) && !$payment->isRecreated()) {
                    $this->pay(Cost::COMPANY_MEMBER, false, $payment->user);
                    Mail::to($payment->user->email)->send(new SendReminderPayment());
                    $payment->markAsRecreated();
                }

                if ($payment->user->onlyRoles([User::OPERATOR]) && !$payment->isRecreated()) {
                    $this->pay(Cost::TEST_INSTITUTION, false, $payment->user);
                    Mail::to($payment->user->email)->send(new SendReminderPayment());
                    $payment->markAsRecreated();
                }

                $msg = "User {$payment->user->name} belum melakukan pembayaran ulang!";
                $this->info($msg);
                Log::channel('renewal')->info($msg);
            }
        }
    }
}
