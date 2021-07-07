<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Teacher extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable; 

    protected $table='teachers';

    protected $allowedSorts=[
        'first_name',
        'last_name',
        'patronym'
    ];

    protected $allowedFilters=[
        'first_name',
        'last_name',
        'patronym'
    ];
    public $fillable=[
        'first_name',
        'last_name',
        'patronym',
        'pass_sery',
        'pass_number',
        'pinfl',
        'birth_date',
        'gender',
        'address',
        'phone'
    ];

    public function getFullNameAttribute(){
        return "{$this->first_name} {$this->last_name} {$this->patronym}";
    }
}
