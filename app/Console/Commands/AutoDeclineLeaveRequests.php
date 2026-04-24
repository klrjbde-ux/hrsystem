<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CreateAdminLeaves;
use Carbon\Carbon;

class AutoDeclineLeaveRequests extends Command
{
    protected $signature = 'leaves:autodenied';
    protected $description = 'Automatically declines leave requests that have ended before today';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::today();

        $leaveRequests = CreateAdminLeaves::where('end_date', '<', $today)
                                          ->where('status', '!=', 'Declined')
                                          ->get();

        foreach ($leaveRequests as $leaveRequest) {
            $leaveRequest->status = 'Declined';
            $leaveRequest->save();
        }

        $this->info('Auto-declined leave requests have been processed successfully.');
    }
}
