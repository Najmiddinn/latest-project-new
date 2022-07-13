<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'course';
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'course_name',
        'course_year',
    ];

    public function course() {
        return $this->hasMany('App\Models\Student', 'course_id','id');
    }

}
