<?php

namespace App\Http\Controllers;

use App\Models\Collaborateur;
use App\Models\Hub;
use App\Models\Planning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GeneratePlanningController extends Controller
{
    private $cells = [
        '',
        '',
        '',
        '',
        '',
        '',
        '8h00',
        '8h30',
        '9h00',
        '9h30',
        '10h00',
        '10h30',
        '11h00',
        '11h30',
        '12h00',
        '12h30',
        '13h00',
        '13h30',
        '14h00',
        '14h30',
        '15h00',
        '15h30',
        '16h00',
        '16h30',
        '17h00',
        '17h30',
        '18h00',
        '18h30',
        '19h00',
        '19h30',
        '20h00',
        '20h30',
        '21h00'
    ];

    public function generetePlanning ()
    {
        $hub = Hub::find(Auth::user()->hub_id);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);
        $worksheet1 = new Worksheet($spreadsheet, " PLANNING ");
        $spreadsheet->addSheet($worksheet1);

        $data = $this->loadPlanning();

        $sheet1Data = collect();
        $date = null;

        $title = ['', '', 'Horaire', 'Conseiller', 'type', 'Rotation'];
        $sheet1Data->push($title, []);
        $sheet1Data->push($this->cells);

        foreach ($data as $items) {
            foreach ($items as $item) {
                if ($date !== null && $date !== $item['date']) {
                    $sheet1Data->push([], []);
                    $sheet1Data->push($this->cells);
                }

                $data = [
                    '',
                    $date === null || $date !== $item['date'] ? $this->convertDateToFormatExcel($item['date']) : null,
                    $item['horaires'] ? $item['horaires']->debut_journee . '-' . $item['horaires']->fin_journee : 'Non Planifié',
                    $item['collaborateur'],
                    $item['type'],
                    $item['horaires'] ? property_exists($item['horaires'], 'rotation') ? $item['horaires']->rotation : 'N/A' : 'Repos'
                ];
                $date = $item['date'];
                $sheet1Data->push($data);
            }
        }
        $worksheet1->fromArray($sheet1Data->toArray());
        $worksheets = [$worksheet1];

        foreach ($worksheets as $worksheet)
        {
            foreach ($worksheet->getColumnIterator() as $column)
            {
                $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }
        $spreadsheet->getActiveSheet()->getStyle('B')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
        $writer = new Xlsx($spreadsheet);

        $writer->save('test.xlsx');

//        Storage::put('avatars/1', $writer);
    }

    private function convertDateToFormatExcel ($date)
    {
        $unix_date = strtotime($date);
        $excel_date = 25569 + ($unix_date / 86400);

        return $excel_date;
    }

    private function loadPlanning (): array
    {
        $hub = Hub::findOrFail(Auth::user()->hub_id);

        $collaborateurs = Collaborateur::with('dates')
            ->where('hub_id', $hub->id)
            ->get();

        $collect = collect();

        foreach ($collaborateurs as $collaborateur) {
            foreach ($collaborateur->dates as $date) {
                if (strtotime(date('l d F Y', strtotime($date->date))) > strtotime(date('l d F Y', strtotime('- '.$this->getLundi().' days')))) {
                    $horaires = $this->getHoraire($date->pivot->horaire);
                    $object = [
                        'collaborateur' => $collaborateur->name,
                        'date' => $date->date,
                        'horaires' => $horaires,
                        'type' => $this->getType($date->pivot->horaire, $horaires)
                    ];
                    $collect->push($object);
                }
            }
        }
           $collect = $collect->groupBy('date');
            return $collect->toArray();

    }

    private function getLundi (): int
    {
        $today = date('l', strtotime('now'));

        $return_value = match ($today) {
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
            'Sunday' => 7
        };

        return $return_value;
    }

    private function getHoraire ($data): mixed
    {
        $horaires = json_decode($data);
        $isPlanifier = true;

        if ($horaires->debut_journee === 'OFF' || empty($horaires->debut_journee)) {
            $isPlanifier = false;
        }
        if ($isPlanifier) {
            return $horaires;
        } else {
            return false;
        }
    }

    private function getType($data, $horaires): ?string
    {
        $value = json_decode($data);

        if ($value->type === 'Iti1' || $value->type === 'Iti2' || $value->type === 'Iti3' || $value->type === 'TER') {
            if (!$horaires) {
                return null;
            } else {
                $value->type = 'Iti';
            }
        }

        return $value->type;
    }

    private function formatDateFr($data): string
    {
        $format = date('l d F', strtotime($data));
        $date = explode(' ', $format);
        $days = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche'
        ];
        $months = [
            'Janvier' => 'January',
            'Février' => 'February',
            'Mars' => 'March',
            'Avril' => 'April',
            'Mai' => 'May',
            'Juin' => 'June',
            'Juillet' => 'July',
            'Aout' => 'August',
            'Septembre' => 'September',
            'Octobre' => 'October',
            'Novembre' => 'November',
            'Décembre' => 'December'
        ];
        foreach ($days as $key => $value) {
            if ($date[0] === $key) {
                $date[0] = $value;
            }
        }
        foreach ($months as $key => $value) {
            if ($date[2] === $value) {
                $date[2] = $key;
            }
        }
        return implode(' ', $date);
    }

}
