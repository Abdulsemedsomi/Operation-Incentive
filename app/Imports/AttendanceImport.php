<?php

namespace App\Imports;

use App\User;
use App\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Hrmsdata;
use App\Leave;
class AttendanceImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
 
    public function model(array $row)
    {
        if ($row[0] != null && $row[0] != "acc_id") {
                $user = User::where('payroll_id', $row[0])->first();
                if($user){
                      $session = Session::where('start_date', '<=', Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])))->where('end_date', '>', Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])))->first();
                    $leave = Leave::where('user_id', $user->id)->where('session_id', $session->id)->where('leave_from', '<=', Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])))->where('leave_to', '>=', Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])))->first();
                    
                    if(!$leave){
                    return Hrmsdata::updateOrCreate(['user_id' => $user->id, 'date' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])) ,
                    'type' => floatval($row[2]) , 'session_id' => $session->id]); 
                    }
                }
           
            
        }
    }
}
