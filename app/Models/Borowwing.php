<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borowwing extends Model
{
    use HasFactory;
    protected $table = 'borowwing';
    public $timestamps = false;
    
    protected $fillable = [
        'student_id',
        'book_id',
        'book_code',
        'status',
        'date_borrowwed',
        'date_return',
    ];

    
    public function getStudent() {
        return $this->hasOne('App\Models\Student','id','student_id'); 
    }
    
    public function getStudentCategory($id) {
        if (!empty($id)) {
            $course =  Course::findOrFail($id); 
            return $course->course_name; 
        }
        return null;
    }
    
    public function getBook() {
        return $this->hasOne('App\Models\Book','id','book_id'); 
    }
   
    
    
}