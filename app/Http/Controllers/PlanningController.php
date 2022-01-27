<?php

namespace App\Http\Controllers;

use App\Http\Requests\Planning\importHubRequest;
use App\Models\Collaborateur;
use App\Models\CollaborateurDate;
use App\Models\Date;
use App\Models\Hub;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PlanningController extends Controller
{

    public function show (Hub $hub)
    {
        $inputFileName = Storage::path('planning/Gujan/planning-2022.xlsx');
        $inputFileType = IOFactory::identify($inputFileName);

        $reader = IOFactory::createReader($inputFileType);

        $reader->setLoadSheetsOnly(' PLANNING ');
        $reader->setReadDataOnly(true);

            $spreadsheet = $reader->load($inputFileName);

        $sheet = $spreadsheet->getActiveSheet();

        $cells = [
            'F' => '8h00',
            'G' => '8h30',
            'H' => '9h00',
            'I' => '9h30',
            'J' => '10h00',
            'K' => '10h30',
            'L' => '11h00',
            'M' => '11h30',
            'N' => '12h00',
            'O' => '12h30',
            'P' => '13h00',
            'Q' => '13h30',
            'R' => '14h00',
            'S' => '14h30',
            'T' => '15h00',
            'U' => '15h30',
            'V' => '16h00',
            'W' => '16h30',
            'X' => '17h00',
            'Y' => '17h30',
            'Z' => '18h00',
            'AA' => '18h30',
            'AB' => '19h00',
            'AC' => '19h30',
            'AD' =>'20h00',
            'AE' => '20h30',
            'AF' => '21h00'
        ];

        $collect = collect();

        for ($i = 1000; $i < $sheet->getHighestRow(); $i++) {
            if ($sheet->getCell('B' . $i)->getCalculatedValue() !== null) {
                $excel_date = $sheet->getCell('B'. $i)->getCalculatedValue();
                $unix_date = ($excel_date - 25569) * 86400;
                $excel_date = 25569 + ($unix_date / 86400);
                $unix_date = ($excel_date - 25569) * 86400;

                    $numberMembers = $i;
                    $members = collect();
                    while ($sheet->getCell('D'. $numberMembers)->getOldCalculatedValue() !== null || $sheet->getCell('D'. $numberMembers)->getValue()) {
                        $member['collaborateur'] = $sheet->getCell('D'. $numberMembers)->getOldCalculatedValue() ?
                            $sheet->getCell('D'. $numberMembers)->getOldCalculatedValue() :
                            $sheet->getCell('D'. $numberMembers)->getValue();

                        $horaires = $sheet->getCell('C' . $numberMembers)->getOldCalculatedValue();
                        $debutJournee = 'OFF';
                        $finJournee = 'OFF';
                        if ($horaires !== 'OFF' && !empty($horaires)) {
                            $horaire = explode('-', $horaires);
                            $debutJournee = $horaire[0];
                            $finJournee = $horaire[1];
                        }
                        $debutPause = null;
                        $finPause = null;

                        foreach ($cells as $cell => $value) {
                            if ($sheet->getCell($cell . $numberMembers)->getOldCalculatedValue() === 'DEJ') {
                                if (empty($debutPause)) {
                                    $debutPause = $value;
                                }
                            }
                            if ($sheet->getCell($cell . $numberMembers)->getOldCalculatedValue() !== 'DEJ' && !empty($debutPause) && empty($finPause)) {
                                $finPause = $value;
                            }
                        }

                        $horaires = [
                            'debut_journee' => $debutJournee,
                            'debut_pause' => $debutPause,
                            'fin_pause' => $finPause,
                            'fin_journee' => $finJournee,
                        ];
                        $member['horaire'] = $horaires;
                        $members->push($member);
                        $numberMembers++;
                    }
                    $object = [
                        date("l d F", $unix_date) => $members->toArray()
                    ];
                $collect->push($object);
                }
        }
        $spreadsheet->__destruct();
        $spreadsheet = null;
        unset($spreadsheet);

        $reader = null;
        unset($reader);

        $allPlannings = $collect->toArray();

        foreach ($allPlannings as $plannings) {
            foreach ($plannings as $key => $values) {
                $date = Date::firstOrCreate([
                    'date' => date('Y-m-d', strtotime($key))
                ]);

                foreach ($values as $value) {
                    $collaborateur = Collaborateur::firstOrCreate([
                        'name' => $value['collaborateur'],
                        'hub_id' => Auth::user()->hub_id,
                    ]);
                    $hub = Hub::find(Auth::user()->hub_id);

                    CollaborateurDate::where([
                            ['hub_id', $hub->id],
                            ['date_id', $date->id],
                            ['collaborateur_id', $collaborateur->id]
                        ])->delete();

                    $hub->dates()->attach($date, [
                        'collaborateur_id' => $collaborateur->id,
                        'horaire' => json_encode($value['horaire'])
                    ]);
//dd($hub->dates()->first());
                }
            }
        }

//        if (Storage::get('planning/Gujan/planning-2022.xlsx')) {
//            dd('oui');
//        }
//        dd('non');
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
