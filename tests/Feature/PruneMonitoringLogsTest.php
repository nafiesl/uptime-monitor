<?php

namespace Tests\Feature;

use App\Models\MonitoringLog;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruneMonitoringLogsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_deletes_monitoring_logs_older_than_the_owner_retention_months()
    {
        Carbon::setTestNow('2026-11-03 01:00:00');
        $user = $this->createUser(['log_retention_months' => 3]);
        $site = Site::factory()->create(['owner_id' => $user->id]);
        $oldLog = $this->createLog($site, '2026-08-02 23:59:59');
        $keptLog = $this->createLog($site, '2026-08-03 01:00:00');
        $newLog = $this->createLog($site, '2026-11-02 12:00:00');

        $this->assertEquals(0, $this->artisan('monitoring-logs:prune', ['--chunk' => 1]));

        $this->dontSeeInDatabase('monitoring_logs', ['id' => $oldLog->id]);
        $this->seeInDatabase('monitoring_logs', ['id' => $keptLog->id]);
        $this->seeInDatabase('monitoring_logs', ['id' => $newLog->id]);
        Carbon::setTestNow();
    }

    /** @test */
    public function it_uses_retention_months_of_each_site_owner()
    {
        Carbon::setTestNow('2026-11-03 01:00:00');
        $site1 = Site::factory()->create(['owner_id' => $this->createUser(['log_retention_months' => 1])->id]);
        $site2 = Site::factory()->create(['owner_id' => $this->createUser(['log_retention_months' => 6])->id]);
        $site1Log = $this->createLog($site1, '2026-09-15 00:00:00');
        $site2Log = $this->createLog($site2, '2026-09-15 00:00:00');

        $this->assertEquals(0, $this->artisan('monitoring-logs:prune'));

        $this->dontSeeInDatabase('monitoring_logs', ['id' => $site1Log->id]);
        $this->seeInDatabase('monitoring_logs', ['id' => $site2Log->id]);
        Carbon::setTestNow();
    }

    private function createLog(Site $site, string $createdAt): MonitoringLog
    {
        $monitoringLog = MonitoringLog::create([
            'site_id' => $site->id,
            'url' => $site->url,
            'response_time' => 100,
            'status_code' => 200,
        ]);
        $monitoringLog->created_at = $createdAt;
        $monitoringLog->save();

        return $monitoringLog;
    }
}
