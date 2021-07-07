<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;


class Group extends Model
{
    use HasFactory;
    use AsSource;
    protected $table='groups';

    public $fillable=[
        'name',
        'teacher_id'
    ];

    public function teacher(){
        return $this->belongsTo(\App\Models\Teacher::class);
    }

    public function students(){
        return $this->belongsToMany(\App\Models\Student::class, 'student_groups', 'group_id', 'student_id');
    }
}
