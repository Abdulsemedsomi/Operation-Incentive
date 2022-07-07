<?php

namespace App\Imports;

use App\Formula;
use App\Kpi;
use App\Kpiform;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class KpiformsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {

        return new Kpiform([
            //
            'kpi_id'     => Kpi::where('position','Solutions Architect')->first()->id,
            'perspective'     => $row[0],
            'objective'     => $row[1],
            'measure'     => $row[2],
            'target'     => $row[3],
            'weight'     => (double)$row[5],
            'type'     =>  Str::contains($row[2], 'appreciation') || Str::contains($row[2], 'Appreciation')? 1:2,
            'formula_id'     => Formula::where('formula',$row[6])->first()->id,
        ]);
    }
}
