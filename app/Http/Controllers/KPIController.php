<?php

namespace App\Http\Controllers;

use App\Imports\KpiformsImport;

use App\Kpi;
use App\Kpiform;
use Illuminate\Http\Request;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class KPIController extends Controller
{
    //

    public function index()
    {
        //
        $kpis = Kpi::all();

        return view('kpi', compact('kpis'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('addkpi');
    }
    public function import(Request $request)
    {
        
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);
        $file = $request->file('select_file');
        dd($file);
        try {


            Excel::import(new KpiformsImport, $file);

            return back()->with('success', 'Excel Data Imported successfully.');
        } catch (Exception $e) {
            return back()->with('error', $e);
        }
    }
    public function store(Request $request)
    {
        //
        $input = $request->all();
        
        //  $kpi = Kpi::create($input);

        $kpi = new Kpi;
        $kpi->kpi_name = $input['kpiname'];
        $kpi->position = $input['kpiposition'];
        $kpi->save();

        $lastkpi = Kpi::orderby('id', 'desc')->first();

        $perspectivecount = $input['perscount'];
        $maxobjcount = $input['objcount'];
        for ($i = 1; $i < $perspectivecount + 1; $i++) {

            if ($request->has('perspective' . $i)) {

                for ($j = 1; $j < $maxobjcount + 1; $j++) {
                    if ($request->has('kpiobj' . $j . "_" . $i)) {
                        $kpiform = new Kpiform;
                        $kpiform->kpi_id = $lastkpi->id;
                        $kpiform->perspective = $input['perspective' . $i];
                        $kpiform->objective = $input['kpiobj' . $j . "_" . $i];
                        $kpiform->measure = $input['measure' . $j . "_" . $i];
                        $kpiform->target = $input['target' . $j . "_" . $i];
                        $kpiform->type = $input['type' . $j . "_" . $i];
                        $kpiform->weight = $input['weight' . $j . "_" . $i];
                        if ($request->has('formula' . $j . "_" . $i) && $input['formula' . $j . "_" . $i] != 0) {
                            $kpiform->formula_id = $input['formula' . $j . "_" . $i];
                        }
                        $kpiform->save();
                    }
                }
            }
        }
        return redirect()->back()
            ->with('success', 'Kpi Added successfully.');

        // return $kpi;
    }

    public function show($id)
    {
        //
        $kpi = Kpi::find($id);

        if (is_null($kpi)) {
            return $this->sendError('kpi not found.');
        }

        $pers = Kpiform::where('kpi_id', $id)->select('perspective')->distinct()->get();
        return view('displaykpi', compact(['pers', 'kpi']));
    }
    public function kpishow($id)
    {
        //
        $kpi = Kpi::find($id);

        if (is_null($kpi)) {
            return $this->sendError('kpi not found.');
        }


        return $kpi;
    }
    public function showkpiform($id)
    {
        //
        $kpi = Kpiform::where('kpiforms.id', $id)->join('formulas', 'formulas.id', '=', 'kpiforms.formula_id')->first();
        $skpi = Kpiform::find($id);
        if ($skpi->formula_id == null) {
            return $skpi;
        }
        if (is_null($kpi)) {
            return 0;
        }


        return $kpi;
    }
    public function kpifromposition($id)
    {

        $kpi = Kpi::find('position', $position)->first();
        if ($kpi) {

            $pers = Kpiform::where('kpi_id', $kpi->id)->select('perspective')->distinct()->get();
            $kpiforms = Kpiform::where('kpi_id', $kpi->id)->get();

            $result[0] =  $pers->toArray();
            $result[1] =  $kpiforms->toArray();
        } else {
            $result = [];
        }

        return $result;
    }
    public function perspectivefromid($id)
    {

            $pers = Kpiform::where('kpi_id', $id)->select('perspective', 'kpi_id')->distinct()->get();
        return  $pers;
    }
    public function kpiformfromid($id)
    {
       
        $kpiforms = Kpiform::where('kpi_id', $id)->get();

       return  $kpiforms;
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
        $kpi = Kpi::find($id);
        $pers = Kpiform::where('kpi_id', $id)->select('perspective')->distinct()->get();
        $objcount = 1;
        foreach ($pers as $p) {
            $kpiforms = Kpiform::where('kpi_id', $id)->where('perspective', $p->perspective)->get();
            if ($kpiforms->count() > $objcount) {
                $objcount = $kpiforms->count();
            }
        }
        return view('editkpi', compact(['pers', 'kpi', 'objcount']));
    }

    public function update(Request $request, $id)
    {
        //
        $input = $request->all();

        $ekpi = Kpi::find($id);
        $ekpi->kpi_name = $input['kpiname'];
        $ekpi->position = $input['kpiposition'];
        $ekpi->save();
        Kpiform::where('kpi_id', $id)->delete();
        $perspectivecount = $input['perscount'];
        $maxobjcount = $input['objcount'];
        for ($i = 1; $i < $perspectivecount + 1; $i++) {

            if ($request->has('perspective' . $i)) {

                for ($j = 1; $j < $maxobjcount + 1; $j++) {
                    if ($request->has('kpiobj' . $j . "_" . $i)) {
                        $kpiform = new Kpiform;
                        $kpiform->kpi_id = $ekpi->id;
                        $kpiform->perspective = $input['perspective' . $i];
                        $kpiform->objective = $input['kpiobj' . $j . "_" . $i];
                        $kpiform->measure = $input['measure' . $j . "_" . $i];
                        $kpiform->target = $input['target' . $j . "_" . $i];
                        $kpiform->weight = $input['weight' . $j . "_" . $i];
                        if ($request->has('formula' . $j . "_" . $i) && $input['formula' . $j . "_" . $i] != 0) {
                            $kpiform->formula_id = $input['formula' . $j . "_" . $i];
                        }
                        $kpiform->save();
                    }
                }
            }
        }
        return redirect()->back()
            ->with('success', 'Kpi Edited successfully.');
    }
public function getPosition(){
    $kpi = Kpi::select('id','position')->get();
    return $kpi;
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Kpi::destroy($id);
        return "Deleted Successfuly";
    }
}
