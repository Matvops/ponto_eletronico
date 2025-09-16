<?php

namespace Tests\Unit\TimeSheet;

use App\Models\TimeSheet;
use App\Repositories\TimeSheetRepository;
use App\Services\TimeSheetService;
use App\Utils\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Mockery;
use PHPUnit\Framework\Attributes\RunClassInSeparateProcess;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

#[RunClassInSeparateProcess]
final class TimeSheetServiceTest extends TestCase
{
    private TimeSheetService $timeSheetService;
    private TimeSheetRepository&Stub $timeSheetRepositoryStub;
    private array $differences;


    protected function setUp(): void
    {
        $this->timeSheetRepositoryStub = $this->createStub(TimeSheetRepository::class);
        $this->timeSheetService = new TimeSheetService($this->timeSheetRepositoryStub);
        $this->differences = json_decode(
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
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_calculate_time_balance_with_id(): void
    {
        $data = [
                    'timeBalance' => '01:58:01',
                    'status' => true
        ];

        $differences = json_decode(
            json_encode(
                [
                    [
                        'value' => '01:58:01'
                    ]
                ]
            )
        );

        $this->timeSheetRepositoryStub->method('getTimesBalance')
            ->willReturn($differences);

        $this->assertSame($data, $this->timeSheetService->calculateTimeBalance(4)->getData());
        $this->assertSame(true, $this->timeSheetService->calculateTimeBalance(4)->getStatus());
    }

    public function test_calculate_time_balance_with_one_time_sheet(): void
    {
        $data = [
                    'timeBalance' => '-57:55:39',
                    'status' => false
        ];

        $this->timeSheetRepositoryStub->method('getTimesBalance')
            ->willReturn($this->differences);

        $this->assertSame(Response::getResponse(true, data: $data)->getData(), $this->timeSheetService->calculateTimeBalance(4)->getData());
        $this->assertSame(Response::getResponse(true, data: $data)->getStatus(), $this->timeSheetService->calculateTimeBalance(4)->getStatus());
    }

    public function test_calculate_time_balance_throw_exception(): void
    {


        $differences = json_decode(
                        json_encode(
                            [
                                ['value' => '01:58:01'],
                                ['value' => '-10:00:a']
                            ]
                        )
                    );

        $this->timeSheetRepositoryStub->method('getTimesBalance')
            ->willReturn($differences);

        $response = $this->timeSheetService->calculateTimeBalance(4);

        $this->assertSame("Erro ao consultar saldo de horas! Entre em contato com o suporte.", $response->getMessage());
        $this->assertFalse($response->getStatus());
    }

    public function test_calculate_time_balance_differences_null(): void
    {
        $data = [
                    'timeBalance' => '00:00:00',
                    'status' => false
        ];

        $this->timeSheetRepositoryStub->method('getTimesBalance')
            ->willReturn(null);

        $this->assertSame($data, $this->timeSheetService->calculateTimeBalance(4)->getData());
        $this->assertTrue($this->timeSheetService->calculateTimeBalance(4)->getStatus());
    }

    public function test_punch_clock_successfully(): void
    {

        DB::expects('beginTransaction')
            ->andReturnSelf();

        DB::expects('commit')
            ->andReturnSelf();

        $timeSheetMock = Mockery::mock(TimeSheet::class);

        $timeSheetMock->shouldReceive('setAttribute')
            ->with('updated_at', Mockery::type(Carbon::class))
            ->andReturnSelf();

        $timeSheetMock->shouldReceive('save')
            ->once()
            ->andReturnSelf(true);

        $this->timeSheetRepositoryStub->method('getTimeSheetByTisId')
            ->with(4)
            ->willReturn($timeSheetMock);

        $tisIdEncrypted = 'abcdefghi';
        Crypt::expects('decrypt')
            ->with($tisIdEncrypted)
            ->andReturn(4);

        $response = $this->timeSheetService->punchClock($tisIdEncrypted);

        $this->assertTrue($response->getStatus());
        $this->assertSame('Ponto atualizado', $response->getMessage());
    }

    public function test_punch_clock_without_tis_id_invalid(): void
    {

        DB::expects('beginTransaction')
            ->andReturnSelf();

        DB::expects('rollback')
            ->andReturnSelf();

        $this->timeSheetRepositoryStub->method('getTimeSheetByTisId')
            ->with(4)
            ->willReturn(null);

        $tisIdEncrypted = 'abcdefghi';
        Crypt::expects('decrypt')
            ->with($tisIdEncrypted)
            ->andReturn(4);

        $response = $this->timeSheetService->punchClock($tisIdEncrypted);

        $this->assertFalse($response->getStatus());
        $this->assertSame("Erro ao salvar registro de ponto", $response->getMessage());
    }

    public function test_punch_clock_with_tis_id_null(): void
    {

        DB::expects('beginTransaction')
            ->andReturnSelf();

        DB::expects('rollback')
            ->andReturnSelf();

        $tisIdEncrypted = null;
        Crypt::expects('decrypt')
            ->with($tisIdEncrypted)
            ->andThrow(DecryptException::class);

        $response = $this->timeSheetService->punchClock($tisIdEncrypted);

        $this->assertFalse($response->getStatus());
    }
}
