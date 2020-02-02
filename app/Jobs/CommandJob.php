<?php

namespace App\Jobs;

use App\Models\Job;
use App\Models\State;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;

class CommandJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $job_a;

    /**
     * Create a new job instance.
     *
     * @param Job $job
     */
    public function __construct($job)
    {
        $this->job_a = $job;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->job_a->state = State::PROCESSING;
        $this->job_a->queue_job_id = $this->job->getJobId();
        $this->job_a->processor_id = getmypid();
        $this->job_a->started_at = now();
        $this->job_a->save();
        sleep(5);

        exec($this->job_a->command);

        $this->job_a->finished_at = now();
        $this->job_a->state = State::SUCCESS;
        $this->job_a->save();

    }


}
