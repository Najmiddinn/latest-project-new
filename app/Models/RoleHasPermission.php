<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;
use App\Models\Role;

class RoleHasPermission extends Model
{
    use HasFactory;
    
    protected $table = 'role_has_permissions';
    public $timestamps = false;
    
    protected $primaryKey = 'permission_id';

    protected $fillable = [
        'permission_id',
        'role_id',
    ];

    

    public function getPermission() {
        return $this->hasOne('App\Models\Permission','id','permission_id'); 
        // return $this->belongsToMany('App\Models\Permission','id','permission_id');
    }

    public function getRole() {
        return $this->hasOne('App\Models\Role','id','role_id');
    }

    
    

}



