<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleHasPermissonController extends Controller
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
        $models = RoleHasPermission::with('getRole','getPermission')->get();
        // echo '<pre>';
        // dump($models);die;
		return view('backend.role-has-permissions.index',[
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
        $roles = Role::all();
		
        return view('backend.role-has-permissions.create')->with([
			'permissions'=>$permissions,
			'roles'=>$roles,
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
			// 'permission_id' => 'required|unique:role_has_permissions,permission_id|integer',
			'permission_id' => ['required','integer'],
			// 'role_id' => 'required|unique:role_has_permissions,role_id|integer',
			'role_id' => ['required','integer'],

		]); 

        if ($validator->fails()) {
			return redirect()->route('role-has-permissions.create')->withErrors($validator)->withInput();
		}

        $model = RoleHasPermission::create($request->all());

        if ($model) {
            // Session::flash('success','ajoyib');
            return redirect()->route('role-has-permissions.index')->with('success','role-has-permissions ' . __('msg.successfully created'));

        }
        return redirect()->route('role-has-permissions.create')->with('success','role-has-permissions ' . __('msg.successfully created'));
        

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RoleHasPermission  $roleHasPermission
     * @return \Illuminate\Http\Response
     */
    // public function show($permission_id,$role_id)
    // {
    //     // echo $permission_id, $role_id; die;
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RoleHasPermission  $roleHasPermission
     * @return \Illuminate\Http\Response
     */
    public function edit($permission_id,$role_id)
    {
        $model = RoleHasPermission::with('getRole','getPermission')->where(['permission_id'=>$permission_id,'role_id'=>$role_id])->firstOrFail();
        
        $roles = Role::all();
        $permissions = Permission::all();
        
       	return view('backend.role-has-permissions.edit')->with([
			'model'=>$model,
			'roles'=>$roles,
			'permissions'=>$permissions,
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RoleHasPermission  $roleHasPermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$permission_id,$role_id)
    {
        $validator = Validator::make($request->all(),[
			'permission_id' => 'required|integer',
			'role_id' => 'required|integer',
		]); 

        if ($validator->fails()) {
			return redirect()->route('role-has-permissions.update',['permission_id'=>$permission_id,'role_id'=>$role_id])->withErrors($validator)->withInput();
		}

        
        $model = RoleHasPermission::where(['permission_id'=>$permission_id,'role_id'=>$role_id])->firstOrFail();
        
        // dump($model);die;

        $model->permission_id = $request->input('permission_id');
        $model->role_id = $request->input('role_id');

        if ($model->save()) {
            return redirect()->route('role-has-permissions.index')->with('success','role-has-permissions ' . __('msg.successfully updated'));
        }

        return redirect()->route('role-has-permissions.update',['permission_id'=>$permission_id,'role_id'=>$role_id])->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RoleHasPermission  $roleHasPermission
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoleHasPermission $roleHasPermission)
    {
        //
    }
    
}
