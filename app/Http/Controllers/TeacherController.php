<?php

namespace App\Http\Controllers;

use App\Teacher;
use App\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $teacher = Teacher::all();
        return response()->json($teacher);
    }

    public function periods($id) {
        if ($teacher = Teacher::find($id)) {
            return response()->json($teacher->periods);
        }

        return null;
    }

    public function students($id) {
        if ($teacher = Teacher::find($id)) {
            return $teacher->periods()->with('students')->get()
                ->map(function($period) {
                    return $period->students;
                })
                ->flatten()
                ->unique()
                ->toJson();
        }
        return null;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $teacher = Teacher::all();
        return response()->json($teacher);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $validated = $request->validate([
            'userName' => 'bail|required|min:3|max:50|unique:teachers|unique:students',
            'password' => 'bail|required|min:6|max:50',
            'fullName' => 'bail|required|min:6|max:50',
            'email' => 'bail|required|email'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $teacher = Teacher::create($validated);
        return response()->json($teacher);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $teacher = Teacher::find($id);
        $teachers = Teacher::all();
        return response()->json($teachers);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $teacher = Teacher::find($id);
        return response()->json($teacher);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){

        $teacher = Teacher::find($id);
        $validated = $request->validate([
            'userName' => "bail|required|min:3|max:50|unique:teachers,userName,$id|unique:students",
            'password' => 'bail|required|min:6|max:50',
            'fullName' => 'bail|required|min:6|max:50',
            'email' => 'bail|required|email'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $teacher->fill($validated)->save();
        return response()->json($teacher);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $teacher = Teacher::find($id);
        $teacher->delete();
        return response()->json($teacher);
    }

    public function assignToPeriod(Request $request, $id) {
        $teacher = Auth::guard('teacher')->user();

        if ($period = Period::find($id)) {
            $period->teacher()->associate($teacher)->save();
        }

        return response()->json($teacher);
    }

    public function removeFromPeriod(Request $request, $id) {
        $teacher = Auth::guard('teacher')->user();

        if ($period = Period::find($id)) {
            $period->teacher()->dissociate($teacher)->save();
        }

        return response()->json($teacher);
    }
}
