<?php

namespace App\Http\Controllers;

use App\Celoxis;
use Illuminate\Http\Request;
use DateTime;

use GuzzleHttp\Client;

class CeloxisController extends Controller
{

    public function fetchTask()
    {
        set_time_limit(0);
        $token = ' Nt5AiFWcL0N3tPsh5FFknvwcyM6prSHOn1yG8Pni';
        $baseUrl = 'https://app.celoxis.com';
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',

        ];

        $tasks = new Client();
        $response = $tasks->request('GET', $baseUrl . '/psa//api/v2/tasks?filter={"custom_schedule_tracker" : ["Forex Start", "Forex End","Finance Start", "Finance End",Sourcing Start", "Sourcing End","Logistics Start", "Logistics End" ,"Implementation Start", "Implementation End","Project Start", "Project End" ], "custom_milestone" :["1","2","3","4","5","6","7","8","9","10"],"actualPercentComplete" :"100"}', [
            'headers' => $headers,
            'apiKey' => $token
        ]);
        $response = json_decode($response->getBody());


        // $response = collect($response->data);

        // $response->filter(function ($response, $key) {
        //     return $response['item']['actualPercentComplete'] === '50';
        // });

        //convert Json Response into Associative array
        // $arrayResponse = json_decode($response->getBody());

        return $response;
    }



    public function store(Request $request)
    {
        $baseUrl = 'https://app.celoxis.com';
        $token = ' Nt5AiFWcL0N3tPsh5FFknvwcyM6prSHOn1yG8Pni';
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];


        $arrayResponse = $this->fetchTask();

        $input = $request->all();
        $minimum_spi = $request->input('minimum_spi');
        $PPA = $request->input('ppa');
        $sessions = $request->input('session_id');
        $array_counter = count($arrayResponse->data);
        set_time_limit(0);

        Celoxis::truncate();
        for ($i = 0; $i < $array_counter; $i++) {
            $schedule_trackers = $arrayResponse->data[$i]->custom_schedule_tracker;
            $percent_completions =  $arrayResponse->data[$i]->actualPercentComplete;

            $id = $arrayResponse->data[$i]->id;
            $schedule_tracker = $arrayResponse->data[$i]->custom_schedule_tracker;
            $projects_response = $arrayResponse->data[$i]->project;

            $project = new Client();
            $projects_data = $project->get($projects_response, [
                'headers' => $headers,
                'apiKey' => $token
            ]);
            $projectarrayResponse = json_decode($projects_data->getBody());
            $project_id = $projectarrayResponse->data->id;
            $project_name = $projectarrayResponse->data->name;
            $project_amount = $projectarrayResponse->data->budget;
            $task_name = $arrayResponse->data[$i]->name;
            $task_amount =  $arrayResponse->data[$i]->budget;
            $task_amount = $arrayResponse->data[$i]->custom_milestone_amount;
            $milestone_name =  $arrayResponse->data[$i]->custom_milestone;
            $custom_milestone_name = $arrayResponse->data[$i]->custom_milestone_name;

            $project_milestone =  $arrayResponse->data[$i]->custom_project_milestone;
            $payment_milestone =  $arrayResponse->data[$i]->custom_payment_milestone;
            $forex_milestone =  $arrayResponse->data[$i]->custom_forex_milestone;
            $sourcing_milestone =  $arrayResponse->data[$i]->custom_sourcing_milestone;
            $delivery_milestone =  $arrayResponse->data[$i]->custom_delivery_milestone;
            $implementation_milestone =  $arrayResponse->data[$i]->custom_implementation_milestone;

            $milestone_amount =  $arrayResponse->data[$i]->custom_milestone_amount;
            $percent_completion =  $arrayResponse->data[$i]->actualPercentComplete;
            $percent_completion = (int)$percent_completion;

            $planned_start = $arrayResponse->data[$i]->plannedStart;
            $planned_finish = $arrayResponse->data[$i]->plannedFinish;
            $SAC =  $arrayResponse->data[$i]->duration;
            $SAC = (int)$SAC;

            $actual_finished =  $arrayResponse->data[$i]->actualFinish;

            $planned_start_date = new DateTime($planned_start);
            $actual_finished_date = new DateTime($actual_finished);
            $actual_time = date_diff($planned_start_date, $actual_finished_date);
            $interval = (int)($actual_time->format("%a"));
            $actual_time = $interval;
            if ($actual_time == 0) {
                $actual_time = 10000000;
            }

            $actual_finish_quarter =  $arrayResponse->data[$i]->actualFinishQuarter;

            $earned_schedule =  doubleval($SAC) * doubleval($percent_completion / 100);
            $SPI = number_format(($earned_schedule / $actual_time), 2);

            $users =  $arrayResponse->data[$i]->resources;
            $position = $arrayResponse->data[$i]->custom_position;
            $PPA =  $PPA;
            $project_percent_amount = $PPA / 100;
            $bonus =  $SPI * ($project_percent_amount * $milestone_amount);
            if ($bonus == 0) {
                $bonus = "No bonus";
            }

            Celoxis::create(
                [
                    'task_id' => $id,
                    'session_id' => $sessions,
                    'project_id' => $project_id,
                    'project_name' => $project_name,
                    'project_amount' => $project_amount,
                    'task_name' => $task_name,
                    'task_amount' => $task_amount,
                    'custom_schedule_tracker' => $schedule_tracker,
                    'custom_milestone_name' => $custom_milestone_name,
                    'milestone_name' => $milestone_name,
                    'project_milestone' => $project_milestone,
                    'forex_milestone' => $project_milestone,
                    'sourcing_milestone' => $project_milestone,
                    'delivery_milestone' => $project_milestone,
                    'implementation_milestone' => $implementation_milestone,
                    'payment_milestone' => $payment_milestone,
                    'milestone_amount' => $milestone_amount,
                    'percent_completion' => $percent_completion,
                    'planned_start' => $planned_start,
                    'planned_finish' => $planned_finish,
                    'actual_finish' => $actual_finished,
                    'SAC' => $SAC,
                    'actual_time' => $actual_time,
                    'actual_finish_quarter' => $actual_finish_quarter,
                    'earned_schedule' => $earned_schedule,
                    'SPI' => $SPI,
                    'users' => $users,
                    'position' => $position,
                    'minimum_spi' => $minimum_spi,
                    'PPA' => $PPA,
                    'bonus' => $bonus,
                ]
            );
        }

        return redirect()->route('incentive');
    }

    // public function generatedOperationIncentive(){

    //     $geneOperationIncentive = DB::table('operation_incentives')->where('spi', '>=', 0.5)->get();
    //     echo "<pre>";
    //     echo "task name    User         position         bonus", "<br><br>";

    //     foreach ($geneOperationIncentive as $operationIncentive) {
    //         $id =  $operationIncentive->id;
    //         $task_name = $operationIncentive->task_name ;
    //         $user = $operationIncentive->users;
    //         $user = explode(',',$user);         //convert string response into array
    //         $position = $operationIncentive->position;
    //         $bonus = $operationIncentive->bonus;

    //         $userscounter = count($user);

    //         for ($u =0; $u<$userscounter; $u++){
    //             echo $task_name,  "   ", $user[$u],"     ", $position,"          ", $bonus,"<br>";
    //         }


    //     }
    //     echo "</pre>";
    // }





}
