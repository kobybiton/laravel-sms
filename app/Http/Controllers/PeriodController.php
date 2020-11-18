<?php

namespace App\Http\Controllers;

use App\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $period = Period::all();
        return response()->json($period);
    }

    public function students($id){
        if ($period = Period::find($id)) {
            return response()->json($period->students);
        }

        // Doesn't exist error?
        return null;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $period = Period::all();
        return response()->json($period);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'bail|required|min:6|max:50'
        ]);

        $period = Period::create($validated);

        return response()->json($period);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $period = Period::find($id);
        $periods = Period::all();
        return response()->json($periods);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $period = Period::find($id);
        return response()->json($period);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $period = Period::find($id);
        $validated = $request->validate([
            'fullName' => 'bail|required|min:6|max:50'
        ]);

        $period->fill($validated)->save();
        return response()->json($period);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $period = Period::find($id);
        $period->delete() ;
        return response()->json($period);
    }
}
