<?php

namespace App\Observers;

use App\Events\JobStateChanged;
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
        dispatch(new CommandJob($job))->onQueue('high')->delay(now()->addSeconds(5));
        $job->state = State::PENDING;
        $job->save();
    }

    /**
     * Handle the job "updated" event.
     *
     * @param Job $job
     * @return void
     */
    public function updated(Job $job)
    {
        if($job->isDirty('state')){
            event(new JobStateChanged($job));
        }
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
