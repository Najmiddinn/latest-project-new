<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ModelHasRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\AssignOp\Mod;
use Illuminate\Support\Facades\DB;


class ModelHasRoleController extends Controller
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
        $models = ModelHasRole::with('getRole','getModel')->get();
		return view('backend.model-has-role.index',[
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
        $roles = Role::all();
        $users = User::where(['user_type'=>1])->get();
        return view('backend.model-has-role.create',[
            'roles' => $roles,
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
            'role_id' =>  ['required','integer'],
        ]); 

        if($validator->fails()){
            return redirect()->route('model-has-role.create')->withErrors($validator)->withInput();
        }

        $model = User::findOrFail($request->input('user_id'));
        $model->assignRole($request->input('role_id'));
        

        if ($model->save()) {
            return redirect()->route('model-has-role.index')->with('success','role ' . __('msg.successfully created'));
        }

        
        return redirect()->route('model-has-role.create')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModelHasRole  $modelHasRole
     * @return \Illuminate\Http\Response
     */
    public function show(ModelHasRole $modelHasRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ModelHasRole  $modelHasRole
     * @return \Illuminate\Http\Response
     */
    public function edit($role_id,$model_id)
    {
        $model = ModelHasRole::with('getRole','getModel')->where(['role_id'=>$role_id,'model_id'=>$model_id])->firstOrFail();
        
        $users = User::where(['user_type'=>1])->get();
        $roles = Role::all();
        
       	return view('backend.model-has-role.edit')->with([
			'model'=>$model,
			'users'=>$users,
			'roles'=>$roles,
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ModelHasRole  $modelHasRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$role_id,$model_id)
    {
        $validator = Validator::make($request->all(),[
            'user_id' =>  ['required', 'integer'],
            'role_id' =>  ['required','integer'],
        ]); 

        if($validator->fails()){
            return redirect()->route('model-has-role.create')->withErrors($validator)->withInput();
        }

        DB::table('model_has_roles')->where(['role_id'=>$role_id,'model_id'=>$model_id])->delete();
        
        $model = User::findOrFail($request->input('user_id'));
        $model->assignRole($request->input('role_id'));
        
        if ($model) {
            return redirect()->route('model-has-role.index')->with('success','model has role  ' . __('msg.successfully created'));
        }

        return redirect()->route('model-has-role.edit')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModelHasRole  $modelHasRole
     * @return \Illuminate\Http\Response
     */
    public function destroy($role_id,$model_id)
    {
        DB::table('model_has_roles')->where(['role_id'=>$role_id,'model_id'=>$model_id])->delete();
        return redirect()->route('model-has-role.index')->with('success','deleted');
    }


}



