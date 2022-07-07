<?php

namespace App\Http\Controllers;

use App\Dailyplan;
use App\Subtask;
use App\Task;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
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
        $task = Subtask::create($input);
        return Subtask::find($task->id);
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


        return Subtask::find($id);
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
        $task = Subtask::find($task_id);
        $task->subtask_name = $request->subtask_name;

        // $task->unplanned = $request->unplanned;

        $task->save();
        return $task;
    }
    public function updateStatus(Request $request){

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
        $sub = Subtask::find($task_id);
        $keyid = Task::find($sub->taskid)->keyresultid;
        $dayplan = Dailyplan::where("subtask_id", $task_id)->first();
        if($dayplan){
         $task = Subtask::find($task_id);
         $task->isactive=0;
         $task->save();
         return $keyid;
        }
        $task = Subtask::destroy($task_id);
        return $keyid;
    }
}

