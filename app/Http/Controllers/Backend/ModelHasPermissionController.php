<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ModelHasPermission;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ModelHasPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:data-all|data-create|data-edit|data-show|data-delete', ['only' => ['index']]);
        $this->middleware('permission:data-create|data-all', ['only' => ['create','store']]);
        $this->middleware('permission:data-show|data-all', ['only' => ['show']]);
        $this->middleware('permission:data-edit|data-all', ['only' => ['edit','update']]);
        $this->middleware('permission:data-delete|data-all', ['only' => ['destroy']]);
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = ModelHasPermission::with('getPermission','getModel')->get();
		return view('backend.model-has-permission.index',[
			'models'=>$models,
		]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        $users = User::where(['user_type'=>1])->get();
        return view('backend.model-has-permission.create',[
            'permissions' => $permissions,
            'users' => $users,
		]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' =>  ['required', 'integer'],
            'permission_id' =>  ['required','integer'],
        ]); 

        if($validator->fails()){
            return redirect()->route('model-has-permission.create')->withErrors($validator)->withInput();
        }

        $model = User::findOrFail($request->input('user_id'));
        $model->givePermissionTo($request->input('permission_id'));
        

        if ($model->save()) {
            return redirect()->route('model-has-permission.index')->with('success','model haspermission ' . __('msg.successfully created'));
        }

        
        return redirect()->route('model-has-permission.create')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModelHasPermission  $modelHasPermission
     * @return \Illuminate\Http\Response
     */
    public function show(ModelHasPermission $modelHasPermission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ModelHasPermission  $modelHasPermission
     * @return \Illuminate\Http\Response
     */
    public function edit($permission_id,$model_id)
    {
        $model = ModelHasPermission::with('getPermission','getModel')->where(['permission_id'=>$permission_id,'model_id'=>$model_id])->firstOrFail();
        
        $users = User::where(['user_type'=>1])->get();
        $permissions = Permission::all();
        
       	return view('backend.model-has-permission.edit')->with([
			'model'=>$model,
			'users'=>$users,
			'permissions'=>$permissions,
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ModelHasPermission  $modelHasPermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$permission_id,$model_id)
    {
        $validator = Validator::make($request->all(),[
            'user_id' =>  ['required', 'integer'],
            'permission_id' =>  ['required','integer'],
        ]); 

        if($validator->fails()){
            return redirect()->route('model-has-permission.create')->withErrors($validator)->withInput();
        }

        DB::table('model_has_roles')->where(['permission_id'=>$permission_id,'model_id'=>$model_id])->delete();
        
        $model = User::findOrFail($request->input('user_id'));
        $model->givePermissionTo($request->input('permission_id'));
        
        if ($model) {
            return redirect()->route('model-has-permission.index')->with('success','model has role  ' . __('msg.successfully created'));
        }

        return redirect()->route('model-has-permission.edit')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModelHasPermission  $modelHasPermission
     * @return \Illuminate\Http\Response
     */
    public function destroy($permission_id,$model_id)
    {
        DB::table('model_has_roles')->where(['permission_id'=>$permission_id,'model_id'=>$model_id])->delete();
        return redirect()->route('model-has-permission.index')->with('success','deleted');
    }
}
