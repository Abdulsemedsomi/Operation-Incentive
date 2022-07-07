<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Filledkpi;
use App\Filledkpilist;
use App\Formula;
use App\Kpi;
use App\Kpiform;
use App\Plan;
use App\KpiNotice;
use App\Report;
use App\Score;
use App\Session;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;

use PhpOffice\PhpSpreadsheet\Style\Fill;

class FillKpiController extends Controller
{
    //
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
        $input = $request->all();
        $sessionid = Plan::find(Report::find($input['report_id'])->plan_id)->sessionid;
        $userid = Report::find($input['report_id'])->user_id;
        $kpis = Kpiform::where("kpi_id", $input['kpi_id'])->get();
        $totalscore = 0;

        Filledkpi::updateOrCreate(
            ['employee_id' => $userid, 'session_id' => $sessionid, 'filledby_id' => Auth::user()->id, 'kpi_id' => $input['kpi_id']],
            ['type' => 0]
        );
        $filledkpi = Filledkpi::orderby('updated_at', 'desc')->first();
        foreach ($kpis as $kpi) {
            $actual = $input['actual' . $kpi->id];


            if ($kpi->formula_id != null) {
                $formulaarray = explode(" ", Formula::find($kpi->formula_id)->formula);
                for ($i = 0; $i < sizeof($formulaarray); $i++) {
                    if ($formulaarray[$i] == "actual") {
                        $formulaarray[$i] = $actual;
                    } elseif ($formulaarray[$i] == "target") {
                        $formulaarray[$i] = $kpi->target;
                    } elseif ($formulaarray[$i] == "weight") {
                        $formulaarray[$i] = $kpi->weight / 100;
                    } else if ($formulaarray[$i] == "x") {
                        $formulaarray[$i] = "*";
                    } else if ($formulaarray[$i] == "÷") {
                        $formulaarray[$i] = "/";
                    }
                }
                $finalvalue = "";
                foreach ($formulaarray as $fa) {
                    $finalvalue .= $fa;
                }


                $evaluatedValue = 0;
                try {
                    $evaluatedValue = eval('return (' . $finalvalue . ');');
                } catch (Exception $ex) {
                    return redirect()->back()
                        ->with('error', 'Error.');
                    dd($ex);
                }

                $indivscore = round($evaluatedValue * 100, 1);
                Filledkpilist::updateOrCreate(
                    ['filledkpi_id' => $filledkpi->id, 'kpiform_id' => $kpi->id],
                    ['actual' => $actual, 'score' =>  $indivscore]
                );
                $totalscore += round($evaluatedValue * 100, 1);
            } else {
                if ($actual == 1) {
                    $indivscore = $kpi->weight;
                    $totalscore += $kpi->weight;
                    Filledkpilist::updateOrCreate(
                        ['filledkpi_id' => $filledkpi->id, 'kpiform_id' => $kpi->id],
                        ['actual' => 1, 'score' =>  $indivscore]
                    );
                } else if ($actual == 0) {
                    $indivscore = 0;
                    $totalscore += 0;
                    Filledkpilist::updateOrCreate(
                        ['filledkpi_id' => $filledkpi->id, 'kpiform_id' => $kpi->id],
                        ['actual' => 0, 'score' =>  $indivscore]
                    );
                }
            }
        }
        $filledkpi->totalScore = $totalscore;
        $filledkpi->save();

        $filledkpiinsession = Filledkpi::where('employee_id', $userid)->where('session_id', $sessionid)->get();

        $totalkpi =0; $kcount =0;

        foreach($filledkpiinsession as $fk){
            $totalkpi += $fk->totalScore;
            $kcount ++;
        }
        $kpiscore =round($totalkpi/ $kcount,2);
        Score::updateOrCreate(
            ['user_id' => $userid, 'session_id' => $sessionid],
            ['KPI_Score' =>  $kpiscore]
        );
        return redirect()->back()
            ->with('success', 'Kpi Filled successfully.');
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
