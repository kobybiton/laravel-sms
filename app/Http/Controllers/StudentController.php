<?php

namespace App\Http\Controllers;

use App\Student;
use App\Period;
use App\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $students = Student::all();
        return response()->json($students);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $students = Student::all();
        return response()->json($students);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $validated = $request->validate([
            'userName' => 'bail|required|min:3|max:50|unique:students|unique:teachers',
            'password' => 'bail|required|min:6|max:50',
            'fullName' => 'bail|required|min:6|max:50',
            'grade' => 'bail|required|gte:0|lte:12'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $student = Student::create($validated);

        return response()->json($student);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $student = Student::find($id);
        $students = Student::all() ;
        return response()->json($students);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $student = Student::find($id);
        return response()->json($student);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $student = Student::find($id);

        $validated = $request->validate([
            'userName' => "bail|required|min:3|max:50|unique:students,userName,$id|unique:teachers",
            'password' => 'bail|required|min:6|max:50',
            'fullName' => 'bail|required|min:6|max:50',
            'grade' => 'bail|required|gte:0|lte:12'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $student->fill($validated)->save();

        return response()->json($student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $student = Student::find($id);
        $student->delete() ;
        return response()->json($student);
    }

    // student assignment to a period
    public function assignToPeriod(Request $request, $id) {
        $student = Auth::guard('student')->user();

        $studentAlreadyExist = Schedule::where('periodId', $id)->where('studentId', $student->id)->exists();
        if(!$studentAlreadyExist){
            if (Period::where('id', $id)->whereNotNull('teacherId')->exists()) {
                $student->periods()->attach($id);
                return response()->json($student);
            }
            return 'period or teacher is not exist';
        }
        return 'this student is already exist in this class';
    }

    public function removeFromPeriod(Request $request, $id) {
        $student = Auth::guard('student')->user();
        $student->periods()->detach($id);
        return response()->json($student);
    }
}
