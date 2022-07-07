<?php

namespace App\Http\Controllers;

use App\Filledkpi;
use App\Form;
use App\Kpi;
use App\Kpiscoring;
use App\Session;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreKpiController extends Controller
{
    //
    public function index()
    {
        $displays = Filledkpi::join('users as emp', 'emp.id', '=', 'filledkpis.employee_id')
            ->join('users as m', 'm.id', '=', 'filledkpis.filledby_id')->select('filledkpis.id', 'totalResult', 'emp.id as empid', 'emp.fname as empfname', 'emp.lname as emplname', 'm.fname as mfname', 'm.lname as mlname', 'emp.team as team')->get();
        $kpitypes = Kpi::all();
        return view('displaykpi', compact(['displays', 'kpitypes']));
    }
    public function create(Request $request)
    {
        $generalmajor = Form::where('kpi_type_id', $request->input('kpitype'))->where('division', 1)->where('isMajor_criteria', 1)->get();
        $departmentmajor = Form::where('kpi_type_id', $request->input('kpitype'))->where('division', 2)->where('isMajor_criteria', 1)->get();


        $kpi = Kpi::find($request->input('kpitype'));
        $team = Team::find($kpi->department_id);
        $users = [];
        $sessions = Session::all();
        if ($team) {
            $users = User::where('team', $team->team_name)->get();
        }
        return view('fillkpi', compact(['generalmajor', 'departmentmajor', 'users', 'sessions']));
    }
    public function store(Request $request)
    {
        $input = $request->all();

        $criterias = Form::all();
        $criteriaIds = [];
        $subcrIds = [];
        $count = 0;
        $subcount = 0;
        $kpitype = [];
        foreach ($criterias as $c) {

            if ($request->has('mcriteriaweight' . $c->id)) {
                $criteriaIds[$count] = $c->id;
                $count++;
                $kpitype = Form::find($c->id);
            }
            if ($request->has('rating' . $c->id)) {
                $subcrIds[$subcount] = $c->id;
                $subcount++;
            }
        }

        $filledkpi = new Filledkpi;
        if (sizeof($kpitype->toArray()) > 0) {
            $filledkpi->kpi_form_id = $kpitype->kpi_type_id;
        } else {
            $filledkpi->kpi_form_id = 9999;
        }

        $filledkpi->employee_id = $input['employee_id'];
        $filledkpi->filledby_id = Auth::user()->id;
        $filledkpi->session_id = $input['session_id'];
        $filledkpi->save();

        $lastkpi = Filledkpi::orderby('id', 'desc')->first();
        $indivweight = [];
        $crweight = [];
        for ($i = 0; $i < sizeof($criteriaIds); $i++) {
            $majweight = $input['mcriteriaweight' . $criteriaIds[$i]];
            $crweight[$i] = $majweight;
            $count = 0;

            for ($j = 0; $j < sizeof($subcrIds); $j++) {
                $subc = Form::find($subcrIds[$j]);
                if ($criteriaIds[$i] == $subc->parent_criteria_id) {
                    $count++;
                    //$subcweight = $input['rating'.$subcrIds[$j]]/5 * $majweight
                }
            }
            $indivweight[$i] =   round($majweight / $count, 2);
        }
        $attainment = 0;
        for ($i = 0; $i < sizeof($criteriaIds); $i++) {

            $nmajweight = 0;

            for ($j = 0; $j < sizeof($subcrIds); $j++) {
                $subc = Form::find($subcrIds[$j]);
                if ($criteriaIds[$i] == $subc->parent_criteria_id) {

                    $subcweight = round($input['rating' . $subcrIds[$j]] / 5 * $indivweight[$i], 2);
                    $skpiscoring = new Kpiscoring;
                    $skpiscoring->filled_kpi_id = $lastkpi->id;
                    $skpiscoring->criteria_id = $subcrIds[$j];
                    $skpiscoring->attainment = $subcweight;
                    $skpiscoring->rating = $input['rating' . $subcrIds[$j]];
                    $skpiscoring->save();
                    $nmajweight += $subcweight;
                }
            }
            $kpiscoring = new Kpiscoring;
            $kpiscoring->filled_kpi_id = $lastkpi->id;
            $kpiscoring->criteria_id = $criteriaIds[$i];
            $kpiscoring->attainment = $nmajweight;
            $kpiscoring->tpercentage = $crweight[$i];
            $kpiscoring->save();

            $attainment += $nmajweight;
        }
        $lastkpi->totalResult =  $attainment;
        $lastkpi->save();
        return back()->with('success', 'Added Successfully.');
    }
    public function show($id)
    {
        $filledkpi = Filledkpi::where('filledkpis.id', $id)->join('users', 'users.id', '=', 'filledkpis.employee_id')->select('filledkpis.id', 'fname', 'session_id', 'filledby_id', 'employee_id', 'filledkpis.created_at')->first();

        $scoreratinggeneral = Kpiscoring::where('filled_kpi_id', $id)->where('forms.isMajor_criteria', 1)->where('division', 1)->join('forms', 'forms.id', '=', 'kpiscorings.criteria_id')->get();
        $scoreratingdept = Kpiscoring::where('filled_kpi_id', $id)->where('forms.isMajor_criteria', 1)->where('division', 2)->join('forms', 'forms.id', '=', 'kpiscorings.criteria_id')->get();

        return view('showfilled', compact(['filledkpi', 'scoreratinggeneral', 'scoreratingdept']));
    }
    public function showFilled($id)
    {
        $filledkpi = Filledkpi::where('filledkpis.id', $id)->join('users', 'users.id', '=', 'filledkpis.employee_id')->first();


        return $filledkpi;
    }
    public function edit($id)
    {
        $filledkpi = Filledkpi::where('filledkpis.id', $id)->join('users', 'users.id', '=', 'filledkpis.employee_id')
            ->join("sessions", 'sessions.id', '=', 'filledkpis.session_id')->select('filledkpis.id', 'session_id', 'fname', 'lname', 'users.id as uid', 'kpi_form_id', 'filledkpis.created_at')->first();
        $scoreratinggeneral = Kpiscoring::where('filled_kpi_id', $id)->where('forms.isMajor_criteria', 1)->where('division', 1)->join('forms', 'forms.id', '=', 'kpiscorings.criteria_id')->get();
        $scoreratingdept = Kpiscoring::where('filled_kpi_id', $id)->where('forms.isMajor_criteria', 1)->where('division', 2)->join('forms', 'forms.id', '=', 'kpiscorings.criteria_id')->get();

        $kpi = Kpi::find($filledkpi->kpi_form_id);

        $team = Team::find($kpi->department_id);
        $users = [];
        $sessions = Session::all();
        if ($team) {
            $users = User::where('team', $team->team_name)->get();
        }
        return view('editfilledkpi', compact(['filledkpi', 'scoreratinggeneral', 'scoreratingdept', 'users', 'sessions']));
    }
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $criterias = Form::all();
        $criteriaIds = [];
        $subcrIds = [];
        $count = 0;
        $subcount = 0;
        $kpitype = [];
        foreach ($criterias as $c) {

            if ($request->has('mcriteriaweight' . $c->id)) {
                $criteriaIds[$count] = $c->id;
                $count++;
                $kpitype = Form::find($c->id);
            }
            if ($request->has('rating' . $c->id)) {
                $subcrIds[$subcount] = $c->id;
                $subcount++;
            }
        }

        $lastkpi = Filledkpi::find($id);
        $indivweight = [];
        $crweight = [];
        for ($i = 0; $i < sizeof($criteriaIds); $i++) {
            $majweight = $input['mcriteriaweight' . $criteriaIds[$i]];
            $crweight[$i] = $majweight;
            $count = 0;

            for ($j = 0; $j < sizeof($subcrIds); $j++) {
                $subc = Form::find($subcrIds[$j]);
                if ($criteriaIds[$i] == $subc->parent_criteria_id) {
                    $count++;
                    //$subcweight = $input['rating'.$subcrIds[$j]]/5 * $majweight
                }
            }
            $indivweight[$i] =   round($majweight / $count, 2);
        }
        $attainment = 0;
        //  $remove = Kpiscoring::where('filled_kpi_id',$lastkpi->id)->delete();

        for ($i = 0; $i < sizeof($criteriaIds); $i++) {

            $nmajweight = 0;

            for ($j = 0; $j < sizeof($subcrIds); $j++) {
                $subc = Form::find($subcrIds[$j]);
                if ($criteriaIds[$i] == $subc->parent_criteria_id) {

                    $subcweight = round($input['rating' . $subcrIds[$j]] / 5 * $indivweight[$i], 2);
                    $skpiscoring = Kpiscoring::where('filled_kpi_id',  $lastkpi->id)->where('criteria_id', $subcrIds[$j])->first();


                    $skpiscoring->attainment = $subcweight;
                    $skpiscoring->rating = $input['rating' . $subcrIds[$j]];
                    $skpiscoring->save();
                    $nmajweight += $subcweight;
                }
            }
            $kpiscoring = Kpiscoring::where('filled_kpi_id',  $lastkpi->id)->where('criteria_id', $criteriaIds[$i])->first();

            $kpiscoring->attainment = $nmajweight;
            $kpiscoring->tpercentage = $crweight[$i];
            $kpiscoring->save();

            $attainment += $nmajweight;
        }
        $lastkpi->totalResult =  $attainment;
        $lastkpi->employee_id = $input['employee_id'];
        $lastkpi->save();
        return back()->with('success', 'Edited Successfully.')->withInput($request->input());;
    }
    public function destroy($filledkpi)
    {
        //



        $fk = Filledkpi::destroy($filledkpi);
        return $fk;
    }
}
