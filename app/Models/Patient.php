<?php

namespace App\Models;

use App\Models\Relations\BelongsToUser;
use App\Models\Relations\HasManyIntervals;
use App\Models\Relations\HasManyWorkouts;
use App\Models\Relations\HasManyXrays;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends BaseModel
{
    use HasFactory, BelongsToUser, HasManyIntervals, HasManyWorkouts, HasManyXrays;

    protected $casts = [
        'is_on_now' => 'boolean',
    ];

    protected $appends = ['duration_today_seconds', 'duration_today_for_humans', 'latest_workout', 'latest_interval', 'latest_xray'];


    public function getDurationTodayAttribute()
    {
        $seconds = 0;
        $intervals = $this->intervals()->today()->get();
        foreach ($intervals as $interval) {
            $start = $interval->started_at->max(Carbon::today('Europe/Moscow'));
            $seconds += $start->diffInSeconds($interval->ended_at);
        }
        return  \Carbon\CarbonInterval::seconds($seconds)->cascade();
    }

    public function getDurationTodayForHumansAttribute()
    {
        return  $this->duration_today->forHumans();
    }

    public function getDurationTodaySecondsAttribute()
    {
        return  $this->duration_today->totalSeconds;
    }
}
