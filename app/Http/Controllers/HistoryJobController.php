<?php

namespace App\Http\Controllers;

use App\Http\Filters\HistoryJob\ByAuth;
use App\Http\Filters\HistoryJob\SearchByStatus;
use App\Http\Resources\HistoryJob\HistoryJobCollection;
use App\Repositories\RegisterJob\RegisterJobRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class HistoryJobController extends Controller
{
    protected $registerJobRepository;

    public function __construct(RegisterJobRepository $registerJobRepository)
    {
        $this->registerJobRepository = $registerJobRepository;
    }

    public function __invoke(Request $request)
    {
        $jobVacancies = app(Pipeline::class)
            ->send($this->registerJobRepository->query())
            ->through([
                ByAuth::class,
                SearchByStatus::class
            ])
            ->thenReturn()
            ->with([])
            ->paginate($request->per_page);

        return new HistoryJobCollection($jobVacancies);
    }
}
