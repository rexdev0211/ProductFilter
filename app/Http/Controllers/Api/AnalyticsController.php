<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interval;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{

    public function intervals(Request $request)
    {
        $from = Carbon::parse($request->from, 'Europe/Moscow')->toISOString();
        $to = Carbon::parse($request->to, 'Europe/Moscow')->toISOString();

        $carbonPeriod = CarbonPeriod::create(
            Carbon::parse($request->from, 'Europe/Moscow'),
            '1 day',
            Carbon::parse($request->to, 'Europe/Moscow')
        );
        $periods = [];
        $on = [];
        $off = [];

        $array = $carbonPeriod->toArray();
        for ($i=0; $i<count($array); $i++) {
            $today = $array[$i]->setTimezone('UTC');
            $nextDay = $today->copy()->addHours(24);
            $intervals = Interval::whereNotNull('ended_at')->where('ended_at', '>=', $today)->where('ended_at', '<', $nextDay)->orWhere(function(Builder $query) use ($today, $nextDay) {
             //   dump($today->toDayDateTimeString());
             //   dump($nextDay->toDayDateTimeString());
                return $query->whereBetween('started_at', [$today, $nextDay->subSecond()]);
            });
           $intervals = $intervals->get();

            $duration = 0;
            foreach ($intervals as $interval) {
                $start = $interval->started_at->max($today);
                $end = $interval->ended_at ? $interval->ended_at->min($nextDay) : $nextDay;
                $duration += $start->diffInSeconds($end);
            }

            $periods[] = $today->timezone('Europe/Moscow')->toDateString();
            $on[] = $duration / 3600;
            $off[] = 24 - ( $duration / 3600);
        }
        return [
            'from'=>$from,
            'to'=>$to,
            'carbon' => $carbonPeriod,
            'periods' => $periods,
            'on' => $on,
            'off' => $off,
        ];
    }

}
