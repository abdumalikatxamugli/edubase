<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table='attendance';

    public function statusText(){
        switch($this->status){
            case 'p':
                return 'present';
            case 'a':
                return 'absent';
            case 'l':
                return 'late';
        }
    }
}
