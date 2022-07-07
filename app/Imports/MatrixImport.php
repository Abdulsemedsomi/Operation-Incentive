<?php

namespace App\Imports;

use App\User;
use App\Projectmember;
use App\Project;
use Maatwebsite\Excel\Concerns\ToModel;

class MatrixImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       
        if($row[0]!= null && trim($row[0]) != 'Resource Name' && $row[0]!= ""){
      
            $project = Project::where('project_name', trim($row[0]))->first();
            if(!$project){
           
            $project = new Project;
            $project->project_name = trim($row[0]);
            $project->save();
            }
            $i = 1;
            while($i<=57){
              
                if($row[$i] !=null && $row[$i] != ""){
                    $arr = explode (",", trim($row[$i]));
                    if(sizeof($arr) ==2 ){
                    $name = $arr[0];
                    // dd(explode (",", trim($row[$i]))[1]);
                    $position  = $arr[1];
                     $user = User::where('fname',explode(" ", $name)[0])->where('lname',explode(" ", $name)[1])->first();
                      if($user){
                         Projectmember::updateOrCreate(
                          ['user_id'=> $user->id, 'project_id'=> $project->id]
                          ,['position'=>$position]);
                    }
                    }
                   
                   
                    
                   
                }
                  $i++;
            }
            return $project;
        }
        
    }
}
