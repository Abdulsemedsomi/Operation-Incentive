<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use App\Financialstatement;
use App\Companyinfo;
class SecondSheetImport implements ToModel
{
     public function __construct(int $id,  $date)
    {
        $this->id = $id;
        $this->date = $date;
    }
  public function model(array $row)
    {
      
        if ($row[1] == 'EBITDA') {
              
             Companyinfo::updateOrCreate( ['session_id' => $this->id],['ebitda_actual'=>floatval($row[2]) ]);   
             Financialstatement::updateOrCreate(['session_id' => $this->id, 'record' => $this->date],
             ['user_id' => Auth::user()->id, 'ebitda'=> floatval($row[2])]);
        }
       
        if ($row[1] == 'Total Revenues'){
            Companyinfo::updateOrCreate( ['session_id' => $this->id],['revenue_actual'=> floatval($row[2]) ]);   
             Financialstatement::updateOrCreate(['session_id' => $this->id, 'record' => $this->date],
             ['user_id' => Auth::user()->id, 'revenue'=> floatval($row[2])]);
        
            
        }
      
      
        
    }
}