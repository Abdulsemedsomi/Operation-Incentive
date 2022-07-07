<?php

namespace App\Http\Controllers;

use App\Failuretarget;
use App\Failure;
use App\User;
use App\Weeklyreport;
use App\Report;
use App\Imports\MatrixImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class FailureController extends Controller
{
    //
    public function index()
    {
        //
        $failures = Failuretarget::all();
        return view('failuretargets', compact('failures'));
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
        Failuretarget::create($input);

        return redirect()->back()
            ->with('success', 'Failure target added successfully.');
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
        return  Failuretarget::find($id);
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
          $input = $request->all();
        $failure = Failuretarget::find($id);
        $failure->target = $input['target'];
        $failure->save();
        return redirect()->back()
            ->with('success', 'Failure target updated successfully.');
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
          Failuretarget::destroy($id);
           return redirect()->back()
            ->with('success', 'Failure target deleted successfully.');
    }
    public function updatefailures(){
       
        $failures = Weeklyreport::all();
        foreach($failures as $failure){
      
          
            if( $failure->failurereason_id == 0){
               $failure->failurereason_id = null;
                $failure->save();
            }
            
        }
        
    }
}
