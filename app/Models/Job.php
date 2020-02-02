<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class Job.
 * @version October 2, 2018, 4:27 am UTC
 *
 * @property int id
 * @property string queue_job_id
 * @property int submitter_id
 * @property int processor_id
 * @property string command
 * @property string state
 * @property string started_at
 * @property string finished_at
 */
class Job extends Model
{
    use LadaCacheTrait;

    protected $fillable =
        [
            'queue_job_id', 'submitter_id', 'processor_id', 'command',
            'state', 'started_at', 'finished_at'
        ];


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'queue_job_id' => 'string',
        'submitter_id' => 'integer',
        'processor_id' => 'integer',
        'command' => 'string',
        'state' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'queue_job_id' => 'nullable|sometimes|string',
        'submitter_id' => 'nullable|sometimes|integer',
        'processor_id' => 'nullable|sometimes|integer',
        'command' => 'required|string',
        'state' => 'nullable|string',
    ];


    public function activate()
    {
        $this->state = State::PROCESSING;
        $this->save();
    }


    public function finish(bool $success = true)
    {
        $this->state = $success ? State::SUCCESS : State::FAIL;
        $this->finished_at = Carbon::now();
        $this->save();
    }
}

