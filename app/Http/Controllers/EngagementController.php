<?php

namespace App\Http\Controllers;

use App\Engagement;
use Illuminate\Http\Request;

class EngagementController extends Controller
{

    public function index()
    {
        $engagements = Engagement::all();
        //return view('Engagement.index',compact('engagements'));
        return view('engagement',compact('engagements'));

    }


    public function create()
    {
        return view('Engagement.create');
    }
    public function showExcellence()
    {
        return Engagement::where('Perspective', 0)->get();
    }
    public function showDiscipline()
    {
        return Engagement::where('Perspective', 1)->get();
    }


    public function store(Request $request)
    {

        $request->validate([
            'Objective' => 'required',
            'Measure' => 'required',
            'Target' => 'required',
            'Weight' =>'required',
            'Perspective'=>'required',

        ]);


        Engagement::create($request->all());

        return redirect()->route('engagement.index')
            ->with('success','Engagement created successfully.');
    }

    public function show($id)
    {
     return Engagement::find($id);
    }

    public function edit(Engagement $engagement)
    {
//        return view('Engagement.edit',compact('engagement'));
        $selected = Engagement::where('id','=',$engagement);
        dd($selected);
        return view('engagement',compact('engagement'));

    }

    public function update(Request $request, Engagement $engagement)
    {
        $request->validate([
            'Objective' => 'required',
            'Measure' => 'required',
            'Target' => 'required',
            'Weight' =>'required',
            'Perspective'=>'required',
        ]);

        $engagement->update($request->all());

        return redirect()->route('engagement.index')
            ->with('success','Engagement measure updated successfully');
    }

    public function destroy(Engagement $engagement)
    {
        $engagement->delete();

        return redirect()->route('engagement.index')
            ->with('success','Engagement measure deleted successfully');
    }
}
