<?php

namespace App\Http\Controllers;

use App\Keyresult;
use App\Objective;
use Illuminate\Http\Request;
use App\Task;
use App\Weeklyplan;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)

    {
        //

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $task = new Task;
        $task->metricid = $request->metricid;
        $task->taskname = $request->taskname;
        // $task->unplanned = $request->unplanned;
        $task->userid = Auth::user()->id;
        $task->save();
        return $task;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $input = $request->all();

        if($input['isMilestone'] == 1 & $request->has('parent_task')){
           $task = Task::find($input['parent_task']);
           return $task;
        }
        $task = Task::create($input);
        return Task::find($task->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        return Task::find($id);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $task_id)
    {
        //
        $input= $request->all();
        $task = Task::find($task_id);
        // $task->taskname = $request->taskname;
        // $task->parent_task = $request->parent_task;
        $task->fill($input);
        // $task->unplanned = $request->unplanned;

        $task->save();
        return $task;
    }

    public function updateStatus(Request $request){
        $input = $request->all();
        $task = Task::find($input['id']);
        $task->status = $input['status'];
        $task->save();
        $keyresult = Keyresult::find($task->keyresultid);
        $tasks = Task::where("keyresultid", $keyresult->id)->get();


        $acht = Task::where("keyresultid", $keyresult->id)->where('status', 1)->count();

        $kratt = $acht/$tasks->count();
        $keyresult->attainment = $kratt;
        $keyresult->save();
       $objective = Objective::find($keyresult->objective_id);
       $keyrs = Keyresult::where('objective_id', $objective->id )->get();

       $t = 0;
       foreach($keyrs as $kr){
          $t += $kr->attainment;
       }
      $objatt = $t/$keyrs->count();
      $objective->attainment =  $objatt;
      $objective->save();



        $result[0] = $task;
        $result[1] = $keyresult;
        $result[2] = $objective;

        return $result;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($task_id)
    {
        //

       $weekplan = Weeklyplan::where("task_id", $task_id)->first();
       $keyresultid = Task::find($task_id)->keyresultid;
        $tt = Task::find($task_id);
        $count = 0;
        if($tt->isMilestone == 1 ){
            $deps = Task::where('parent_task', $tt->id)->get();
            if($deps->count()> 0){
                foreach($deps as $dep){
                    $wp = Weeklyplan::where("task_id", $dep->id)->first();
                     if($wp){
                        $task = Task::find($dep->id);
                        $task->isactive=0;
                        $task->save();
                       $count++;
                       }
                       else{
                            $task = Task::destroy($dep->id);
                       }
                }
                
            }
             if($weekplan || $count > 0){
                    $task = Task::find($task_id);
                    $task->isactive=0;
                    $task->save();
                    return $tt;
                }
        }
       if($weekplan && $tt->isMilestone == 0){
        $task = Task::find($task_id);
        $task->isactive=0;
        $task->save();
        return $keyresultid;
       }

        $task = Task::destroy($task_id);
        return $tt;
    }
}
