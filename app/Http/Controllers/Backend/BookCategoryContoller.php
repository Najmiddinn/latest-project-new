<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookCategoryContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:data-all|data-create|data-edit|data-show|data-delete', ['only' => ['index']]);
        $this->middleware('permission:data-create|data-all', ['only' => ['create','store']]);
        $this->middleware('permission:data-show|data-all', ['only' => ['show']]);
        $this->middleware('permission:data-edit|data-all', ['only' => ['edit','update']]);
        $this->middleware('permission:data-delete|data-all', ['only' => ['destroy']]);
        
    }
    public function index()
    {
        $models = BookCategory::orderBy('id','desc')->paginate(100);
		return view('backend.book-category.index',[
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
        $models = BookCategory::all();
        return view('backend.book-category.create')->with([
            'models'=>$models,
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
			'name' =>   ['string','required','max:100'],
			'order_by' => ['integer','nullable','max:20000'],
			'parent' => ['integer','nullable','max:20000'],

		]); 

        if ($validator->fails()) {
			return redirect()->route('book-category.create')->withErrors($validator)->withInput();
		}

        $model = BookCategory::create([
            'parent' => $request->parent,
            'name' => $request->input('name'),
            'order_by' => $request->input('order_by'),

        ]);

        if ($model) {
            return redirect()->route('book-category.index')->with('success','course ' . __('msg.successfully created'));

        }

        return redirect()->route('book-category.create')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BookCategory  $bookCategory
     * @return \Illuminate\Http\Response
     */
    public function show(BookCategory $bookCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookCategory  $bookCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $category = BookCategory::all();
        $model = BookCategory::findorFail($id);
        return view('backend.book-category.edit')->with([
			'model'=>$model,
			'category'=>$category,
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookCategory  $bookCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $model = BookCategory::findorFail($id);
        
        $validator = Validator::make($request->all(),[
			'name' =>   ['string','required','max:100'],
			'order_by' => ['integer','nullable','max:20000'],
			'parent' => ['integer','nullable','max:20000'],
			'status' => ['integer','required','max:20'],

		]); 

        if ($validator->fails()) {
			return redirect()->route('book-category.edit',$id)->withErrors($validator)->withInput();
		}

        $model->name = $request->input('name');
        $model->order_by = $request->input('order_by');
        $model->parent = $request->parent;
        $model->status = $request->input('status');

        if ($model->update()) {
            return redirect()->route('book-category.index')->with('success','book-category ' . __('msg.successfully updated'));
        }
        
       
        return redirect()->route('book-category.edit',$id)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookCategory  $bookCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = BookCategory::findOrFail($id);
        
        if (!empty($model)) {
            $model->delete();
            return redirect()->route('book-category.index')->with('success','course ' . __('successfully deleted'));
        }
    }
}
