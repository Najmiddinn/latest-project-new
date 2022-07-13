<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ModelHasPermission;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Spatie\Permission\PermissionRegistrar;

class UserConroller extends Controller
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
        // $products = Product::latest()->paginate(5);
        // return view('products.index',compact('products'))
        //     ->with('i', (request()->input('page', 1) - 1) * 5);

        $models = User::orderBy('id','desc')->paginate(100);
		return view('backend.user.index',[
			'models'=>$models,
		]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   $roles =  Role::pluck('name','name')->all();
        // $permissions = Permission::pluck('name','name')->all();
        $permissions = Permission::all();
        $models = UserType::all();

        // print_r($permissions);die;
        
        return view('backend.user.create')->with([
			'models'=>$models,
        	'roles'=>$roles,
        	'permissions'=>$permissions
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
            'name' =>  ['required', 'string','max:150'],
            'user_email' => 'required|string|max:150|unique:users,email',
            'password' => ['required', 'string', 'min:8','max:40', 'confirmed'],
            'password_confirmation' => ['nullable', 'string', 'min:8','max:40', 'same:password'],
            'user_type' =>  ['required'],
            // 'permission' =>  "array",
            // 'role' =>  "array",
            // 'permission.*' =>  "array",
            // 'role.*' =>  "array",
        ]); 

        //    print_r($request->get('permission'));die;
        // Array ( [0] => super admin )
        //Array ( [0] => data-show )

        if($validator->fails()){
            return redirect()->route('user.create')->withErrors($validator)->withInput();
        } 
        
        // htmlspecialchars( $request->input('user_email'), ENT_QUOTES),
        
        $model = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('user_email'), 
            'password' => FacadesHash::make($request->input('password')),
            'user_type' => $request->get('user_type'),

        ]); 


        // pick a permission name
        // $permission_name = $request->get('permission');
        // // lookup all defined permissions, regardless of guard
        // $permissions = app(PermissionRegistrar::class)->getPermissions()
        // // filter down to just those matching the specified name
        //     ->filter(function ($permission) use ($permission_name) {
        //         return $permission->name === $permission_name;
        //     });
        // grant those specific permissions to the user
        // $model->givePermissionTo($permissions);
        // app()[PermissionRegistrar::class]->forgetCachedPermissions();
        
        // foreach($request->get('permission') as $p){
        //     $model->givePermissionTo($p);     
        // }
        // $perm = new ModelHasPermission();
        if(!empty($request->get('permission'))){
            foreach ($request->get('permission') as $permItem) {
            // $perm->permission_id =  $permItem;
            // $perm->model_type = 'App\Models\User';
            // $perm->model_id =  $model->id;
            // $perm->save();
            $model->givePermissionTo($permItem);
            }
        }
        if(!empty($request->get('role'))){
            $role = $request->get('role');
            $model->assignRole([$role]);
           
        }

        // $permission = $request->get('permission');
        // $model->givePermissionTo([$permission]);
        
       
        
        
        if($model->save()){
            return redirect()->route('user.index')->with('success','user muvaffaqiyatli yaratildi');
        }

       return redirect()->route('user.create')->withInput();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = User::findOrFail($id);

        $roles = Role::pluck('name','name')->all();
        $permissions = Permission::all();
        // $user_permission = $model->permissions->pluck('name','name')->all();
        $user_role = $model->roles->pluck('name','name')->all();
        $user_permission = $model->permissions->all();
        // $user_type = UserType::all();
        // print_r($user_permission);die;
        // print_r($model->roles->pluck('name','name'));die;
        
        return view('backend.user.edit')->with([
    	    // 'user_type'=>$user_type,
            'model'=>$model,
        	'roles'=>$roles,
        	'permissions'=>$permissions,
        	'user_role'=>$user_role,
        	'user_permission'=>$user_permission,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // print_r( implode(',', $request->get('permission')));die;
        
        $model = User::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'name' =>  ['required', 'string','max:150'],
            'email' => ['required', 'string','max:150'],
            'user_type' =>  ['required'],
            'current_password' => ['nullable', 'string', 'min:8','max:100'],
            // 'password' => ['nullable', 'string', 'min:8','max:40', 'confirmed'],
            'password' => ['nullable', 'string', 'min:8','max:40'],
            'password_confirmation' => ['nullable', 'string', 'min:8','max:40', 'same:password'],
            'permission_id.*' =>  'array',
            'role_id.*' =>  'array',
        ]); 
           
        if ($validator->fails()) {
			return redirect()->route('user.edit',$id)->withErrors($validator)->withInput();
		}

        $model->name = $request->input('name');
        $model->email = $request->input('email');
        $model->user_type = $request->get('user_type');

        if (!empty($request->get('role')) || !empty($request->get('permission'))) {
            DB::table('model_has_roles')->where(['model_id'=>$id])->delete();
            DB::table('model_has_permissions')->where(['model_id'=>$id])->delete();
            
            foreach ($request->get('permission') as $permItem) {
                
                $model->givePermissionTo($permItem);
            }

            // $model->givePermissionTo($request->get('permission'));
            $model->assignRole($request->get('role'));
            
        
        }
        
        
        if(!empty($request->input('current_password'))) {
            if(!FacadesHash::check($request->input('current_password'), $model->password)){
                return back()->with('current_password', 'Current password does not match!');
            }else{
                $model->fill(['password' => FacadesHash::make($request->input('password'))])->save();
            }
        }
        
       if ($model->update()) {
            return redirect()->route('user.index')->with('success','user muvaffaqiyatli tahrirlandi');
       }

       return redirect()->route('user.edit',$id)->withInput();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = User::findOrFail($id);
     
        if($model->id == 7){
            return redirect()->route('user.index')->with('warning',"super adminni o'chiraolmaysiz");
        }
        $model->delete();
        return redirect()->route('user.index')->with('success','user muvaffaqiyatli o\'chirildi');
    }
}













