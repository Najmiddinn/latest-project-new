<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
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
        $models = Permission::orderBy('id','desc')->paginate(100);
		return view('backend.permission.index',[
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
		
        return view('backend.permission.create')->with([
	
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
			'name' => 'required|max:255',
			'guard_name' => 'required|max:255',

		]); 

        if ($validator->fails()) {
			return redirect()->route('permission.create')->withErrors($validator)->withInput();
		}

        $model = Permission::create($request->all());

        if ($model) {
            // Session::flash('success','ajoyib');
            return redirect()->route('permission.index')->with('success','permission ' . __('msg.successfully created'));

        }

        return redirect()->route('permission.create')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Permission::findorFail($id);
        return view('backend.permission.show')->with([
			'model'=>$model,
		]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Permission::findorFail($id);
        return view('backend.permission.edit')->with([
			'model'=>$model,
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(),[
		
			'name' => 'required|string|max:255',
			'guard_name' => 'required|string|max:255',

		]); 

        if ($validator->fails()) {
			return redirect()->route('permission.edit',$id)->withErrors($validator)->withInput();
		}
        
        $model = Permission::whereId($id)->update([
            'name' => $request->input('name'),
            'guard_name' => $request->input('guard_name'),
        ]);

        if ($model) {
            return redirect()->route('permission.index')->with('success','permission ' . __('msg.successfully updated'));
        }
        
        return redirect()->route('permission.edit',$id)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Permission::findOrFail($id);
        
        if (!empty($model)) {
            $model->delete();
            return redirect()->route('permission.index')->with('success','role ' . __('successfully deleted'));
        }
    }
}



