<?php

namespace App\Http\Controllers;

use App\Events\MessagePayment;
use App\Http\Filters\Payment\Search;
use App\Http\Filters\Payment\ShowByStatus;
use App\Http\Filters\Payment\ShowByUser;
use App\Http\Filters\Payment\Sort;
use App\Http\Resources\Payment\PaymentCollection;
use App\Http\Resources\Payment\PaymentDetail;
use App\Http\Traits\MessageFixer;
use App\Models\Advertisement;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\Payment\PaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class PaymentController extends Controller
{
    use MessageFixer;

    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function index(Request $request)
    {
        $payments = app(Pipeline::class)
            ->send($this->paymentRepository->query())
            ->through([
                Sort::class,
                Search::class,
                ShowByUser::class,
                ShowByStatus::class
            ])
            ->thenReturn()
            ->with(["user"])
            ->paginate($request->per_page);

        return new PaymentCollection($payments);
    }

    public function callback(Request $request)
    {
        DB::beginTransaction();

        $payment = $this->paymentRepository->findByCriteria(["external_id" => $request->external_id]);
        $role = Role::findById($payment->user->role_id, 'api');

        try {
            $this->assignRoles($payment, $role, $request);

            if ($payment->advertisement) {
                $this->updateStatusAds($payment->advertisement);
            }

            MessagePayment::dispatch($payment, $request->external_id);

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

    protected function updateStatusAds($advertisement)
    {
        return $advertisement->update([
            "is_active" => Advertisement::ACTIVE
        ]);
    }

    protected function assignRoles($payment, $role, $request)
    {
        $payment->update(['status' => $request->status]);
        return $payment->user->syncRoles($role);
    }
}
