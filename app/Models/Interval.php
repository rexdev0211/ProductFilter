<?php

namespace App\Models;

use App\Models\Traits\CanStartAndEnd;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interval extends BaseModel
{
    use HasFactory, CanStartAndEnd;

    protected $appends = ['duration_for_humans', 'started_at_moscow', 'ended_at_moscow'];
    protected $dates = ['started_at', 'ended_at'];


    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('started_at', '>=', Carbon::today('Europe/Moscow')->setTimezone('UTC'))->orWhereDate('ended_at', '>=', Carbon::today('Europe/Moscow')->setTimezone('UTC'));
    }

    public function scopeByDate(Builder $query, $date): Builder
    {
        $trueDate = Carbon::parse($date)->toDateString();
        return $query->where('started_at', '=', $trueDate)->orWhereDate('ended_at', '=',  $trueDate);
    }

    public function scopeStartTime(Builder $query, $time): Builder
    {
        $startTime = Carbon::parse($time)->toDateTimeString();
        return $query->where('started_at', '>=', $startTime);
    }

    public function scopeEndTime(Builder $query, $time): Builder
    {
        $endTime = Carbon::parse($time)->toDateTimeString();
        return $query->where('started_at', '<=', $endTime);
    }

    public function getDurationForHumansAttribute()
    {
        return $this->started_at->diff($this->ended_at)->format('%H:%I:%S');
    }

    public function getStartedAtMoscowAttribute()
    {
        return $this->started_at->timezone('Europe/Moscow')->toDayDateTimeString();
    }

    public function getEndedAtMoscowAttribute()
    {
        if (!$this->ended_at) {
            return null;
        }
        return $this->ended_at->timezone('Europe/Moscow')->toDayDateTimeString();
    }
}
