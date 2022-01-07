<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends BaseModel
{
    use HasFactory;

    protected $appends = ['duration_for_humans'];
    protected $dates = ['started_at', 'ended_at'];

    public static function start(User $user, $started_at=null)
    {
        $started_at = $started_at ?? Carbon::now();
        $patient = $user->patient;
        $attributes = [
            'patient_id' => $patient->id,
            'started_at' => $started_at,
        ];
        return self::query()->create($attributes);
    }

    public function end()
    {
        return $this->update(['ended_at' => Carbon::now()]);
    }

    public function getDurationForHumansAttribute()
    {
        if (! $this->ended_at) {
            return $this->started_at->diff(Carbon::now())->format('%H:%I:%S');
        }
        return $this->started_at->diff($this->ended_at)->format('%H:%I:%S');
    }
}
