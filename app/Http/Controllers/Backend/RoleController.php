<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\components\ImageResize;
use Illuminate\Support\Facades\Validator;
use File;

class RoleController extends Controller
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
        $models = Role::orderBy('id','desc')->paginate(100);
		return view('backend.role.index',[
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
        // $models = new Role();
		
        return view('backend.role.create')->with([
			// 'models'=>$models,
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
			'roleName' => 'required|unique:roles,guard_name|max:255',
			'roleGuardName' => 'required|unique:roles,guard_name|max:255',

		]); 

        if ($validator->fails()) {
			return redirect()->route('role.create')->withErrors($validator)->withInput();
		}

        $model = Role::create([
            'name' => $request->input('roleName'),
            'guard_name' => $request->input('roleGuardName'),

        ]);

        if ($model) {
            // Session::flash('success','ajoyib');
            return redirect()->route('role.index')->with('success','role ' . __('msg.successfully created'));

        }

        return redirect()->route('role.create')->withInput();
		
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Role::findorFail($id);
        return view('backend.role.show')->with([
			'model'=>$model,
		]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Role::findorFail($id);
        return view('backend.role.edit')->with([
			'model'=>$model,
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = Role::findorFail($id);
        
        $validator = Validator::make($request->all(),[
			// 'roleName' => 'required|unique:roles,guard_name|max:255',
			// 'roleGuardName' => 'required|unique:roles,guard_name|max:255',
			'roleGuardName' => 'required|string|max:255',
			'roleGuardName' => 'required|string|max:255',

		]); 

        if ($validator->fails()) {
			return redirect()->route('role.edit',$id)->withErrors($validator)->withInput();
		}

        $model->name = $request->input('roleName');
        $model->guard_name = $request->input('roleGuardName');
        if ($model->update()) {
            return redirect()->route('role.index')->with('success','role ' . __('msg.successfully updated'));
        }
        
        return redirect()->route('role.edit',$id)->withInput();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Role::findOrFail($id);
        
        if (!empty($model)) {
            $model->delete();
            return redirect()->route('role.index')->with('success','role ' . __('successfully deleted'));
        }
        
    }
}
