<?php

namespace App\Http\Controllers\Api;

use App\Models\Interval;
use App\Models\Workout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WorkoutsController
{
    public function index(Request  $request)
    {
        $user = $request->user();
        $workouts = QueryBuilder::for(Workout::class);
        if ($user->hasRole('patient')) {
            $workouts = $workouts->where('patient_id', $user->patient->id);
        }
        return $workouts->orderByDesc('started_at')->get();
    }


    public function store(Request $request)
    {
        $patient = $request->user()->patient;
        $attributes = [
            'patient_id' => $patient->id,
            'started_at' => $request->started_at ?? Carbon::now(),
            'ended_at' => $request->ended_at,
            'note' => $request->note,
        ];
        return Workout::create($attributes);
    }

    public function end(Request  $request, Workout $workout)
    {
        if (! $request->user()) {
            abort(403);
        }
        $workout->end();
        return $workout;
    }

    public function show(Workout $workout)
    {
        return $workout;
    }

    public function update(Request $request, Workout $workout)
    {
        $workout->update($request->all());
        return $workout;
    }

    public function destroy($id)
    {
        $interval = Workout::findOrFail($id);
        $interval->delete();
        return response('1', 204);
    }
}
