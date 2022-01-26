<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PlanningImport implements WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            ' PLANNING ' => new FirstSheetImport(),
        ];
    }
}
