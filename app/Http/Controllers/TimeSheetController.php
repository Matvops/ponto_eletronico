<?php

namespace App\Http\Controllers;

use App\Http\Requests\PunchClockRequest;
use App\Services\TimeSheetService;

class TimeSheetController extends Controller
{
    private TimeSheetService $service;

    function __construct(TimeSheetService $service)
    {
        $this->service = $service;
    }

    public function punchClock(PunchClockRequest $request)
    {

        $response = $this->service->punchClock($request->input('tis_id'));

        if(!$response->getStatus())
            return back()->with('error_punch_clock', $response->getMessage());

        return back()->with('success_punch_clock', $response->getMessage());
    }
}
