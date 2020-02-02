<?php

namespace App\Observers;

use App\Jobs\CommandJob;
use App\Models\Job;
use App\Models\State;
use Illuminate\Support\Facades\Log;

class JobObserver
{
    /**
     * Handle the job "created" event.
     *
     * @param Job $job
     * @return void
     */
    public function created(Job $job)
    {
        $job->queue_job_id = CommandJob::dispatch($job)->onQueue('high')->delay(now()->addSeconds(15));
        $job->state = State::PENDING;
        $job->save();
        Log::info('Job queue id' . $job->queue_job_id);
    }

    /**
     * Handle the job "updated" event.
     *
     * @param Job $job
     * @return void
     */
    public function updated(Job $job)
    {
        //
    }

    /**
     * Handle the job "deleted" event.
     *
     * @param Job $job
     * @return void
     */
    public function deleted(Job $job)
    {
        //
    }

    /**
     * Handle the job "restored" event.
     *
     * @param Job $job
     * @return void
     */
    public function restored(Job $job)
    {
        //
    }

    /**
     * Handle the job "force deleted" event.
     *
     * @param Job $job
     * @return void
     */
    public function forceDeleted(Job $job)
    {
        //
    }
}
