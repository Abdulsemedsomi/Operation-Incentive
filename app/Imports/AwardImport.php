<?php

namespace App\Imports;

use App\Award;
use App\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AwardImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
      public function __construct(int $id)
    {
        $this->id = $id;
    }
    public function model(array $row)
    {
        if ($row[1] != null && $row[1] != "No") {
                $session = Session::where('start_date', '<=', Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])))->where('end_date', '>=', Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])))->first();
               
            return Award::updateOrCreate(['client' => $row[2] ],[
                
                'award'    => floatval($row[4]),
                'contract_sign_date' =>Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])),
                'session_id' => $session ? $session->id: $this->id,
                'user_id' => Auth::user()->id
            ]);
            
        }
    }
}
