<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = ['name', 'teacherId'];

    public function teacher() {
        return $this->belongsTo(Teacher::class, 'teacherId');
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'schedules', 'periodId', 'studentId');
    }
}
