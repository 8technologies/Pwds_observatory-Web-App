<?php

namespace App\Console\Commands;

use DateTime;
use Illuminate\Console\Command;

class CheckJobExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:check-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update job expiration statuses';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $jobs = Job::all();

        foreach ($jobs as $job) {
            $this->checkIfExpired($job);
        }

        return 0;
    }

    protected function checkIfExpired($job)
    {
        $deadline = new DateTime($job->deadline);
        $today = new DateTime();

        if ($today > $deadline) {
            $job->status = 'Expired';
        } else {
            $job->status = 'Active';
        }

        $job->save();
    }
}
