<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCategory extends Model
{
    use HasFactory;
    protected $table = 'book_category';
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'parent',
        'name',
        'status',
        'order_by',
    ];

    public function getParentName(){
        return $this->hasOne(self::class, 'id','parent');
    }

    public function book() {
        return $this->hasMany('App\Models\Book', 'category','id');
    }

  


    public function ebook() {
        return $this->hasMany('App\Models\ElektronBook', 'category','id');
    }

    
}


