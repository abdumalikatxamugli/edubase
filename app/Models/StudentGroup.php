<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attendance;

class StudentGroup extends Model
{
    use HasFactory;
    protected $table='student_groups';

    public function student(){
        return $this->belongsTo(\App\Models\Student::class);
    }
    public function todayAttendance(){
        return Attendance::where([
            'group_student_id'=>$this->id,
            'date_control'=>date('Y-m-d')
        ])->first()??new Attendance();
    }
}
