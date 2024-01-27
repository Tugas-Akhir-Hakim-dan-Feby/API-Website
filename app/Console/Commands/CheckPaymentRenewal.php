<?php

namespace App\Console\Commands;

use App\Http\Facades\PaymentFacade;
use App\Http\Traits\PaymentFixer;
use App\Mail\SendReminderPayment;
use App\Models\Cost;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

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
        DB::beginTransaction();

        try {
            DB::commit();

            $payments = Payment::all();

            foreach ($payments as $payment) {
                $expirationDate = Carbon::parse($payment->created_at)->addMonth();
                $downRoleDate = Carbon::parse($payment->created_at)->addWeeks(2);

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

                if (Carbon::now()->gt($downRoleDate) && $payment->status == Payment::PENDING) {
                    $payment->user->syncRoles(Role::findById(User::MEMBER_APPLICATION, 'api'));

                    $msg = "User {$payment->user->name} ubah role menjadi member aplikasi!";
                    $this->info($msg);
                    Log::channel('renewal')->info($msg);
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
        }
    }
}
