<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;


use App\Session;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Award;
use App\Financialstatement;
use App\Companyinfo;
use App\Imports\AwardImport;
use App\Imports\FinancialImports;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
      
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
    public function store(Request $request)
    {
        //
          $request->validate(['sign' => 'required|image'  ]);


        if($request->hasFile('sign')){
            $sign = $request->file('sign');
            $filename = time() . '.' . $sign->getClientOriginalExtension();
            FacadesImage::make($sign)->resize(100, 100)->save( public_path('/uploads/signs/' . $filename ) );
            $user = Auth::user();
            $user->signature = $filename;
            $user->save();
            Auth::setUser($user);

        }
        else{
            return back()->with('erroru', 'Please choose a file.');
        }

        return back()->with('successu', 'Signature updated Successfully.');
      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
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
    public function update(Request $request, Team $team)
    {
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
       
    }
}