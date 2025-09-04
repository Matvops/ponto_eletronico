<?php

namespace Tests\Unit;

use App\Repositories\TimeSheetRepository;
use App\Services\TimeSheetService;
use App\Utils\Response;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

final class TimeSheetServiceTest extends TestCase
{
    private TimeSheetService $timeSheetService;
    private TimeSheetRepository&MockInterface $timeSheetRepository;

    protected function setUp(): void
    {
        $this->timeSheetRepository = Mockery::mock(TimeSheetRepository::class);
        $this->timeSheetService = new TimeSheetService($this->timeSheetRepository);
    }

    public function test_calculate_time_balance_with_id()
    {
        $differences = json_decode(
            json_encode(
                [
                    [
                        'value' => '01:58:01'
                    ],
                    [
                        'value' => '01:00:00'
                    ],
                    [
                        'value' => '00:00:00'
                    ],
                    [
                        'value' => '00:07:20'
                    ],
                    [
                        'value' => '00:00:00'
                    ],
                    [
                        'value' => '00:00:00'
                    ],
                    [
                        'value' => '-10:00:00'
                    ],
                    [
                        'value' => '-10:00:00'
                    ],
                    [
                        'value' => '-10:00:00'
                    ],
                    [
                        'value' => '-10:00:00'
                    ],
                    [
                        'value' => '-10:00:00'
                    ],
                    [
                        'value' => '-10:00:00'
                    ]
                ]

            )
        );

        $data =
            [
                'timeBalance' => '-58:65:21',
                'status' => false
            ];

        $this->timeSheetRepository->shouldReceive('getTimesBalance')
            ->with(4)
            ->andReturn($differences);

        $this->assertSame(Response::getResponse(true, data: $data)->getData(), $this->timeSheetService->calculateTimeBalance(4)->getData());
        $this->assertSame(Response::getResponse(true, data: $data)->getStatus(), $this->timeSheetService->calculateTimeBalance(4)->getStatus());

    }
}
