<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Interval;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IntervalsController extends Controller
{
    public function index(Request  $request)
    {
        $user = $request->user();
        $intervals = QueryBuilder::for(Interval::class)->allowedFilters([
            AllowedFilter::scope('startTime'),
            AllowedFilter::scope('endTime'),
        ]);
        $startTime = Carbon::parse($request['filter']['startTime'])->toDateTimeString();
        $endTime = Carbon::parse($request['filter']['endTime'])->toDateTimeString();
        $intervals = $intervals->orWhere(function ($query) use ($startTime, $endTime) {
            $query->where('ended_at', '>=', $startTime)
                ->Where('ended_at', '<=', $endTime);
        });
        if ($user->hasRole('patient')) {
            $intervals = $intervals->where('patient_id', $user->patient->id);
        }
        return $intervals->orderByDesc('started_at')->get();
    }


    public function store(Request $request)
    {
        $patient = $request->user()->patient;
        $attributes = [
            'patient_id' => $patient->id,
            'started_at' => $request->started_at ?? Carbon::now(),
            'ended_at' => $request->ended_at ?? null,
            'note' => $request->note,
        ];

        $interval =  Interval::create($attributes);
        if (!$request->ended_at) {
            $patient->update(['is_on_now' => true]);
        }
        return $interval;
    }

    public function end(Interval $interval, Request $request)
    {
        $interval->end();
        $request->user()->patient->update(['is_on_now' => false]);
        return $interval;
    }

    public function show(Interval $interval)
    {
        return $interval;
    }

    public function update(Request $request, Interval $interval)
    {
        $interval->update($request->only(['patient_id', 'started_at', 'ended_at', 'note']));
        return $interval;
    }

    public function destroy($id)
    {
        $interval = Interval::findOrFail($id);
        $interval->delete();
        return response('1', 204);
    }
}
