<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';
    public $timestamps = false;
   
    protected $fillable = [
        'id',
        'category',
        // 'book_code',
        'book_count',
        'title',
        'author',
        'publisher',
    ];

    public function getCategory() {
        return $this->hasOne('App\Models\BookCategory','id','category'); 
    }
    
}
