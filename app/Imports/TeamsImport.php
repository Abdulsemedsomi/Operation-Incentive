<?php

namespace App\Imports;

use App\Team;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;

class TeamsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        
        if( array_key_exists(1,$row) && $row[1] != null && $row[1] !="Station"){
      
            return  Team::updateOrCreate([
                'team_name'     => $row[2]],

                ['parentteam'    => $row[5],
                'manager_id'    => $row[3] != null ?User::where('fname', explode(" ", $row[3])[0])->where('lname', explode(" ", $row[3])[1])->first()?User::where('fname', explode(" ", $row[3])[0])->where('lname', explode(" ", $row[3])[1])->first()->id: null:null,


             ]);
            }
    }
}
