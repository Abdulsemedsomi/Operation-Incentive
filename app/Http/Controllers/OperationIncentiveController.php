<?php

namespace App\Http\Controllers;

use App\Celoxis;
use App\OperationIncentive;
use App\GeneratedIncentive;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

use GuzzleHttp\Client;


class OperationIncentiveController extends Controller
{

    public function fetchTwoRawsData()
    {

        $Celoxis_data = Celoxis::all();
        foreach ($Celoxis_data as $key => $data) {
            if ($data->custom_schedule_tracker == 'Forex Start') {
                $forexend = Celoxis::where(['project_id' => $data->project_id, 'milestone_name' => $data->milestone_name, 'custom_schedule_tracker' => 'Forex End'])->get();
                foreach ($forexend as $key => $end) {
                    $task_id = $end->task_id;
                    $session_id = $end->session_id;
                    $project_id = $end->project_id;
                    $project_name = $end->project_name;
                    $project_amount = $end->project_amount;
                    $task_started_name = $data->task_started_name;
                    $task_name = $end->task_name;
                    $task_amount = $end->task_amount;
                    $milestone_name = $end->milestone_name;
                    $milestone_amount = $end->milestone_amount;
                    $percent_completion = $end->percent_completion;
                    $schedule_tracker = $end->custom_schedule_tracker;

                    $planned_start = $data->planned_start;
                    $planned_finish = $end->planned_finish;
                    $actual_finish = $end->actual_finish;

                    $planned_start_date = new DateTime($planned_start);
                    $panned_finish_date = new DateTime($planned_finish);
                    $actual_finished_date = new DateTime($actual_finish);

                    $interval = date_diff($planned_start_date, $panned_finish_date);

                    $SAC = (int)($interval->format("%a"));

                    $actual_time = date_diff($planned_start_date, $actual_finished_date);
                    $interval = (int)($actual_time->format("%a"));
                    $actual_time = $interval;

                    $actual_finish_quarter = $end->actual_finish_quarter;
                    $earned_schedule = doubleval($SAC) * doubleval($percent_completion / 100);
                    $SPI = number_format(($earned_schedule / $actual_time), 2);
                    $users = $end->users;
                    $position = $end->position;
                    $minimum_spi = $end->minimum_spi;
                    $PPA = $end->PPA;
                    $project_percent_amount = $PPA / 100;
                    $bonus =  $SPI * ($project_percent_amount * $task_amount);
                    OperationIncentive::create(
                        [
                            'task_id' => $task_id,
                            'session_id' => $session_id,
                            'project_id' => $project_id,
                            'project_name' => $project_name,
                            'project_amount' => $project_amount,
                            'task_started_name' => $task_started_name,
                            'task_name' => $task_name,
                            'task_amount' => $task_amount,
                            'custom_schedule_tracker' => $schedule_tracker,
                            'milestone_name' => $milestone_name,
                            'milestone_amount' => $milestone_amount,
                            'percent_completion' => $percent_completion,
                            'planned_start' => $planned_start,
                            'planned_finish' => $planned_finish,
                            'SAC' => $SAC,
                            'actual_finish' => $actual_finish,
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
            } elseif ($data->custom_schedule_tracker == 'Forex Start|Forex End' && $data->session_id == $session_id) {
                $task_id = $data->task_id;
                $session_id = $request->input('session_id');
                $project_id = $data->project_id;
                $project_name = $data->project_name;
                $project_amount = $data->project_amount;
                $task_name = $data->task_name;
                $task_amount = $data->task_amount;
                $milestone_name = $data->milestone_name;
                $milestone_amount = $data->milestone_amount;
                $percent_completion = $data->percent_completion;
                $schedule_tracker = $data->custom_schedule_tracker;

                $planned_start = $data->planned_start;
                $planned_finish = $data->planned_finish;
                $actual_finish = $data->actual_finish;

                $planned_start_date = new DateTime($planned_start);
                $panned_finish_date = new DateTime($planned_finish);
                $actual_finished_date = new DateTime($actual_finish);

                $interval = date_diff($planned_start_date, $panned_finish_date);

                $SAC = (int)($interval->format("%a"));

                $actual_time = date_diff($planned_start_date, $actual_finished_date);
                $interval = (int)($actual_time->format("%a"));
                $actual_time = $interval;

                $actual_finish_quarter = $data->actual_finish_quarter;
                $earned_schedule = doubleval($SAC) * doubleval($percent_completion / 100);
                $SPI = number_format(($earned_schedule / $actual_time), 2);
                $users = $data->users;
                $position = $data->position;
                $minimum_spi = $data->minimum_spi;
                $PPA = $data->PPA;
                $project_percent_amount = $PPA / 100;
                $bonus =  $SPI * ($project_percent_amount * $task_amount);
                OperationIncentive::create(
                    [
                        'task_id' => $task_id,
                        'session_id' => $session_id,
                        'project_id' => $project_id,
                        'project_name' => $project_name,
                        'project_amount' => $project_amount,
                        'task_name' => $task_name,
                        'task_amount' => $task_amount,
                        'custom_schedule_tracker' => $schedule_tracker,
                        'milestone_name' => $milestone_name,
                        //'milestone_amount' => $id,
                        'percent_completion' => $percent_completion,
                        'planned_start' => $planned_start,
                        'planned_finish' => $planned_finish,
                        'SAC' => $SAC,
                        'actual_finish' => $actual_finish,
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
        }
        echo "Incentive created Succesfully";
    }
    public function addParticipants(Request $request, $id)
    {
        $input = $request->all();
        $task = OperationIncentive::where('task_id', $id)->first();

        $task->userss()->attach($input['participants']);
        return back()->with('success', 'Add succefully Participant');
    }
}
