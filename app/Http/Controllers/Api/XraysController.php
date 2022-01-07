<?php

namespace App\Http\Controllers\Api;

use App\Models\Interval;
use App\Models\Workout;
use App\Models\Xray;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class XraysController
{
    public function index(Request  $request)
    {
        $user = $request->user();
        $xrays = QueryBuilder::for(Xray::class);
        if ($user->hasRole('patient')) {
            $xrays = $xrays->where('patient_id', $user->patient->id);
        }
        return $xrays->get();
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $attributes = $request->all();
        $attributes['patient_id'] = $user->patient->id;
        return Xray::create($attributes);
    }

    public function show(Xray $xray)
    {
        return $xray;
    }

    public function update(Request $request, Xray $xray)
    {
        $xray->update($request->all());
        return $xray;
    }

    public function destroy($id)
    {
        //
    }
}
