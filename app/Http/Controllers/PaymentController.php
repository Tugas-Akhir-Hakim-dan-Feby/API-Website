<?php

namespace App\Http\Controllers;

use App\Events\MessagePayment;
use App\Http\Facades\PaymentFacade;
use App\Http\Filters\Payment\Search;
use App\Http\Filters\Payment\ShowByStatus;
use App\Http\Filters\Payment\ShowByUser;
use App\Http\Filters\Payment\Sort;
use App\Http\Resources\Payment\PaymentCollection;
use App\Http\Resources\Payment\PaymentDetail;
use App\Http\Traits\MessageFixer;
use App\Models\Advertisement;
use App\Models\Cost;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\Payment\PaymentRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;
use Spatie\Permission\Models\Role;

class PaymentController extends Controller
{
    use MessageFixer;

    protected $paymentRepository, $paymentFacade;

    public function __construct(PaymentRepository $paymentRepository, PaymentFacade $paymentFacade)
    {
        $this->paymentRepository = $paymentRepository;
        $this->paymentFacade = $paymentFacade;
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

        try {
            DB::commit();

            $payment->update(['status' => $request->status]);

            if ($payment->cost_id == Cost::WELDER_MEMBER) {
                return $this->payWelderMember($payment, $request);
            }

            if ($payment->cost_id == Cost::COMPANY_MEMBER) {
                return $this->payCompanyMember($payment, $request);
            }

            if ($payment->cost_id == Cost::TEST_INSTITUTION) {
                return $this->payOperator($payment, $request);
            }

            if ($payment->cost_id == Cost::ADVERTISEMENT) {
                return $this->payAdvertisement($payment, $request);
            }
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
        $expiredAt = Carbon::now()->addMonths(1);

        return $advertisement->update([
            "is_active" => Advertisement::ACTIVE,
            "expired_at" => Carbon::createFromFormat('Y-m-d H:i:s', $expiredAt)->isoFormat("Y-MM-D"),
        ]);
    }

    protected function payWelderMember($payment, $request)
    {
        $role = Role::findById($payment->user->role_id, 'api');
        if ($request->status == Payment::PAID) {
            MessagePayment::dispatch($payment, $request->external_id);
            $payment->user->syncRoles($role);
            return $this->successMessage("data berhasil diperbaharui", $payment);
        } else {
            $newPayment = $this->paymentFacade->updatePayToPersonalMember($payment, $request);
            return $this->successMessage("data berhasil diperbaharui", $newPayment);
        }
    }

    protected function payCompanyMember($payment, $request)
    {
        $role = Role::findById($payment->user->role_id, 'api');
        if ($request->status == Payment::PAID) {
            MessagePayment::dispatch($payment, $request->external_id);
            $payment->user->syncRoles($role);
            return $this->successMessage("data berhasil diperbaharui", $payment);
        } else {
            $newPayment = $this->paymentFacade->updatePayToCompanyMember($payment, $request);
            return $this->successMessage("data berhasil diperbaharui", $newPayment);
        }
    }

    protected function payOperator($payment, $request)
    {
        $role = Role::findById($payment->user->role_id, 'api');
        if ($request->status == Payment::PAID) {
            MessagePayment::dispatch($payment, $request->external_id);
            $payment->user->syncRoles($role);
            return $this->successMessage("data berhasil diperbaharui", $payment);
        } else {
            $newPayment = $this->paymentFacade->updatePayToOperator($payment, $request);
            return $this->successMessage("data berhasil diperbaharui", $newPayment);
        }
    }

    protected function payAdvertisement($payment, $request)
    {
        if ($request->status == Payment::PAID) {
            $expiredAt = Carbon::now()->addMonths(1);

            $payment->advertisement()->update([
                "is_active" => Advertisement::ACTIVE,
                "expired_at" => Carbon::createFromFormat('Y-m-d H:i:s', $expiredAt)->isoFormat("Y-MM-D"),
            ]);

            return $this->successMessage("data berhasil diperbaharui", $payment);
        } else {
            $payment = $this->paymentFacade->updatePayToAdvertisement($payment, $request);
            return $this->successMessage("data berhasil diperbaharui", $payment);
        }
    }
}
