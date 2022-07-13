<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $table = 'students';
    public $timestamps = false;
    
    protected $fillable = [
        'course_id',
        'first_name',
        'last_name',
        'birth_date',
    ];

    public function getCourse() {
        return $this->hasOne('App\Models\Course','id','course_id'); 
    }

}

