<?php

namespace App\Http\Controllers;

use App\Project;
use App\Projectmember;
use App\User;
use App\Imports\MatrixImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    //
    public function index()
    {
        $projects = Project::all();
        return view('projects', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
       public function import(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new MatrixImport, request()->file('select_file'));

            return back()->with('successt', 'Excel Data Imported successfully.');
        } catch (Exception $e) {
           dd($e);
            return back()->with('errort', 'Import Error.');
        }



       // Excel::import(new UsersImport, 'WebHR_Report.xlsx');
        // $array = Excel::toArray(new UsersImport, 'WebHR_Report.xlsx');
        // dd($array);
    }
    public function store(Request $request)
    {
        //
        $input = $request->all();


        Project::create(
            ['project_name' => $input['projectname'], 
            'amount' => $input['projectdescription']]);
        $proj = Project::orderby('id', 'desc')->first();
        $projectmember = new Projectmember;
        $projectmember->project_id = $proj->id;
        $projectmember->user_id = $input['pm'];
        $projectmember->position = "Project Manager";
        $projectmember->save();
        for ($i = 0; $i < $input['memebercount']; $i++) {
            $namearr = explode(" ", $input['member' . ($i + 1)]);
            $projectmember = new Projectmember;
            $projectmember->project_id = $proj->id;
            $projectmember->user_id = User::where('fname', $namearr[0])->where('lname', $namearr[1])->first()->id;
            $projectmember->position = $input['positionlist' . ($i + 1)];
            $projectmember->save();
        }
        return redirect()->back()
            ->with('success', 'Project Added successfully.');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    public function getmembers($pid){
        $pm = Projectmember::where('project_id', $pid)->where('projectmembers.position', '!=', 'PM')->join('users', 'users.id', '=', 'projectmembers.user_id')->get();
        return $pm;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        
    }
     public function updateinfo(Request $request)
    {
        //
        $project = Project::find($request->input('id'));
        $project->amount = $request->input('amount');
        $project->project_name = $request->input('project_name');
        $project->currency = $request->input('currency');
        $project->save();
        return $project;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}