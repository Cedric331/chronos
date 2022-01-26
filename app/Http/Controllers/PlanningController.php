<?php

namespace App\Http\Controllers;

use App\Http\Requests\Planning\importHubRequest;
use App\Imports\FirstSheetImport;
use App\Models\Hub;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PlanningController extends Controller
{

    public function show (Hub $hub)
    {
        Excel::import(new FirstSheetImport(), Storage::path('planning/Gujan/planning-2022.xlsx'));

        if (Storage::get('planning/Gujan/planning-2022.xlsx')) {
            dd('ok');
        }
        dd('non');
    }


    /**
     * @param importHubRequest $request
     * @param Hub $hub
     * @return false|string
     */
    public function import (importHubRequest $request, Hub $hub)
    {
        $name = 'planning-'.Carbon::now()->format('Y').'.'.$request->file('file')->getClientOriginalExtension();
        $path = $request->file('file')->storeAs('planning/'.$hub->ville, $name);
        return $path;
    }
}
