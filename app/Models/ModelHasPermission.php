<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasPermission extends Model
{
    use HasFactory;
    protected $table = 'model_has_permissions';
    public $timestamps = false;
    
    protected $primaryKey = 'model_id';

    protected $fillable = [
        'permission_id',
        'model_type',
        'model_id',
    ];

    
    public function getModel() {
        return $this->hasOne('App\Models\User','id','model_id'); 
    }

    public function getPermission() {
        return $this->hasOne('App\Models\Permission','id','permission_id');
    }
}
