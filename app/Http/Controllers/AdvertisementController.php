<?php

namespace App\Http\Controllers;

use App\Http\Facades\PaymentFacade;
use App\Http\Filters\Advertisement\ByAuth;
use App\Http\Filters\Advertisement\ByExpired;
use App\Http\Filters\Advertisement\ByRecovery;
use App\Http\Filters\Advertisement\ByStatus;
use App\Http\Requests\Advertisement\AdvertisementRequestStore;
use App\Http\Resources\Advertisement\AdvertisementCollection;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\PaymentFixer;
use App\Http\Traits\UploadDocument;
use App\Models\Advertisement;
use App\Models\Cost;
use App\Repositories\Advertisement\AdvertisementRepository;
use App\Repositories\Cost\CostRepository;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdvertisementController extends Controller
{
    use MessageFixer, UploadDocument;

    protected $advertisementRepository, $costRepository, $paymentFacade;

    public function __construct(
        AdvertisementRepository $advertisementRepository,
        CostRepository $costRepository
    ) {
        $this->advertisementRepository = $advertisementRepository;
        $this->costRepository = $costRepository;
        $this->paymentFacade = new PaymentFacade();
    }

    public function all(Request $request)
    {
        $advertisements = app(Pipeline::class)
            ->send($this->advertisementRepository->query())
            ->through([
                ByStatus::class,
                ByExpired::class
            ])
            ->thenReturn()
            ->with(['user', 'document'])
            ->paginate($request->per_page);

        return new AdvertisementCollection($advertisements);
    }

    public function index(Request $request)
    {
        $advertisements = app(Pipeline::class)
            ->send($this->advertisementRepository->query())
            ->through([
                ByAuth::class,
                ByRecovery::class
            ])
            ->thenReturn()
            ->with(['user', 'document', 'payment'])
            ->paginate($request->per_page);

        return new AdvertisementCollection($advertisements);
    }

    public function store(AdvertisementRequestStore $request)
    {
        DB::beginTransaction();

        $cost = $this->costRepository->find(Cost::ADVERTISEMENT);
        $expiredAt = Carbon::now()->addMonths(1);

        $request->merge([
            "uuid" => Str::uuid(),
            "cost" => $cost->nominal_price,
            "user_id" => auth()->user()->id,
            "expired_at" => Carbon::createFromFormat('Y-m-d H:i:s', $expiredAt)->isoFormat("Y-MM-D")
        ]);

        try {
            $advertisement = $this->advertisementRepository->create($request->all());

            $cost = $this->costRepository->find(Cost::ADVERTISEMENT);
            $payment = $this->paymentFacade->payToAdvertisement($cost, url('advertisement'));
            $this->upload($request->banner, $advertisement->document(), 'banner');

            $advertisement->update([
                "external_id" => $payment->external_id
            ]);

            $advertisement->payment_link = $payment->payment_link;

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $advertisement);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        $advertisement = $this->advertisementRepository->findWhere(["uuid" => $id]);

        try {
            $advertisement->update([
                "is_active" => Advertisement::DELETED
            ]);

            $advertisement->payment()->delete();

            $advertisement->delete();

            DB::commit();
            return $this->successMessage("data berhasil dihapus", $advertisement);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }
}
