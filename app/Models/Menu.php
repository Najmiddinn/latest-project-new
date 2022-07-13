<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menu';
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'name',
        'url',
        'type',
        'parent',
        'order_by',
        'status',
    ];
    
    public function getParentName(){
        return $this->hasOne(self::class, 'id','parent');
    }

    public function renderMenu($id)
    {
        $out = '';
        $menu = \App\Models\Menu::where(['status'=>1,'id' => $id,'type' => 0])->first();
        $_query = \App\Models\Menu::where(['status'=>1,'parent' => $id,'type' => 0])->orderBy('order_by','asc')->get();

        if(count($_query)>0)
        {
            $out .= '<li  class="nav-item"><a class="nav-link" href="#">'.$menu->name.'</a>';
            $out .= '<ul class="dropdown">';

            foreach ($_query as $item){
                $out .= $this->renderMenu($item->id);
            }
            $out .= '</ul></li>';

        }else {
            $out .= '<li class="nav-item"><a class="nav-link" href="'  .$menu['url']. '">';
            $out .= $menu['name'].'</a></li>';
        }

        return $out;
    }
    

}


