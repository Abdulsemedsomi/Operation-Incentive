<?php

namespace App\Imports;

use App\Award;
use App\Financialstatement;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FinancialImports implements WithMultipleSheets 
{
     public function __construct(int $id, $date)
    {
        $this->id = $id;
        $this->date  = $date;
    }
    public function sheets(): array
    {
       
        return [
            '0' => new FirstSheetImport($this->id,$this->date),
                3 => new SecondSheetImport($this->id,$this->date),
        ];
    }
}
      

