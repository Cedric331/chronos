<?php

namespace App\Http\Controllers;

use App\Http\Requests\Planning\importHubRequest;
use App\Models\Hub;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PlanningController extends Controller
{

    public function show (Hub $hub)
    {
        $filePath = Storage::path('planning/Gujan/planning-2022.xlsx');
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($filePath);

        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->getName() === ' PLANNING ') {
                $object = collect();
                foreach ($sheet->getRowIterator() as $row) {
                    $cells = $row->getCells();
                        if (!empty($cells[1]->getValue()) && $cells[1]->getType() === 5) {
                            dd($cells);
                            $timestamp = date("l d F", $cells[1]->getValue()->getTimestamp());
                            dd($cells[3]);
                        }
                }
                break;
            }
        }

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
