<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElektronBook extends Model
{
    use HasFactory;
    protected $table = 'elektron_books';
    
    protected $fillable = [
        'id',
        'category',
        'title',
        'description',
        'file',
        'extension',
        'size',
        'status',
    ];

    public function getCategory() {
        return $this->hasOne('App\Models\BookCategory','id','category'); 
    }
    
}
