<?php

namespace App\Imports;

use App\User;
use App\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Leave;
class LeavesImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
 
    public function model(array $row)
    {
        if ($row[1] != null && $row[1] != "User Name") {
                $user = User::where('webhr_username', $row[1])->first();
                if($user){
                      $session = Session::where('start_date', '<=', Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5])))->where('end_date', '>=', Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6])))->first();
               
                    return Leave::updateOrCreate(['user_id' => $user->id, 'leave_from' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5])) ,
                    'leave_to' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6])), 'leave_type' => $row[3] , 'session_id' => $session->id],[
                'duration' =>$row[7],
                
             
            ]); 
                }
           
            
        }
    }
}
