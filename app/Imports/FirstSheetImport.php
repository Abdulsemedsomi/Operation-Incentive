<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use App\Financialstatement;
use App\Companyinfo;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
class FirstSheetImport implements ToModel
{
    
     public function __construct(int $id,  $date)
    {
        $this->id = $id;
        $this->date = $date;
    }
  public function model(array $row)
    {
      
       
        if($row[1] == 'Cash Collection'){
            Companyinfo::updateOrCreate( ['session_id' => $this->id],['cash_actual'=>floatval($row[2])]);
            return Financialstatement::updateOrCreate(['session_id' => $this->id, 'record' => $this->date], 
            ['user_id' => Auth::user()->id, 'cash'=> (floatval($row[2]))]);
            
        }
        
    }
}