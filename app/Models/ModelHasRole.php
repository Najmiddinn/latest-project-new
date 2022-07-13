<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    use HasFactory;
    
    protected $table = 'model_has_roles';
    public $timestamps = false;
    
    protected $primaryKey = 'model_id';

    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];

    
    public function getModel() {
        return $this->hasOne('App\Models\User','id','model_id'); 
    }

    public function getRole() {
        return $this->hasOne('App\Models\Role','id','role_id');
    }



}


