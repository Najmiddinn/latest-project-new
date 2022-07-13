<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MenuContoller extends Controller
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
        $models = Menu::orderBy('id','desc')->paginate(50);
		return view('backend.menu.index',[
			'models'=>$models,
		]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   $models = Menu::all();
        return view('backend.menu.create')->with([
            'models'=>$models
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
			'name' => ['required','string','max:200'],
			'url' => ['required','string','max:200'],
			'type' => ['required','integer'],
			'parent' => ['nullable','integer'],
			'order_by' => ['nullable','integer'],

		]); 

        if ($validator->fails()) {
			return redirect()->route('menu.create')->withErrors($validator)->withInput();
		}

        $model = Menu::create([
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'type' => $request->get('type'),
            'parent' => $request->get('parent'),
            'order_by' => $request->get('order_by'),

        ]);

        if ($model) {
            return redirect()->route('menu.index')->with('success','course ' . __('msg.successfully created'));

        }

        return redirect()->route('menu.create')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Menu::findorFail($id);
        $models = Menu::all();
        return view('backend.menu.edit')->with([
			'model'=>$model,
			'models'=>$models,
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = Menu::findorFail($id);
        
        $validator = Validator::make($request->all(),[
			'name' => ['required','string','max:200'],
			'url' => ['required','string','max:200'],
			'type' => ['required','integer'],
			'parent' => ['nullable','integer'],
			'order_by' => ['nullable','integer'],

		]); 

        if ($validator->fails()) {
			return redirect()->route('menu.edit',$id)->withErrors($validator)->withInput();
		}

        $model->name = $request->input('name');
        $model->url = $request->input('url');
        $model->type = $request->get('type');
        $model->parent = $request->get('parent');
        $model->order_by = $request->input('order_by');
        $model->status = $request->get('status');

        if ($model->update()) {
            return redirect()->route('menu.index')->with('success','menu ' . __('msg.successfully updated'));
        }
        
       
        return redirect()->route('menu.edit',$id)->withInput();
    }

    public function destroyMultiple(Request $request)
    {
        $booksId = $request->ids;
        if ($booksId) {
            DB::table("menu")->whereIn('id',explode(",",$booksId))->delete();
            return response()->json(['success'=>"Talabalar muvaffaqiyatli o'chirildi"]);
        }
		
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Menu::findOrFail($id);
        $model->delete();
        return redirect()->route('menu.index')->with('success','menu ' . __('successfully deleted'));
        
    }
}
