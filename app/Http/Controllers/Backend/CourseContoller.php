<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseContoller extends Controller
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
        $models = Course::orderBy('course_name','asc')->paginate(100);
		return view('backend.course.index',[
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
        return view('backend.course.create')->with([]);
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
			'course_name' => 'required|max:25',
			'course_year' => 'required|max:10',

		]); 

        if ($validator->fails()) {
			return redirect()->route('course.create')->withErrors($validator)->withInput();
		}

        $model = Course::create([
            'course_name' => $request->input('course_name'),
            'course_year' => $request->input('course_year'),

        ]);

        if ($model) {
            return redirect()->route('course.index')->with('success','course ' . __('msg.successfully created'));

        }

        return redirect()->route('course.create')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Course::findorFail($id);
        return view('backend.course.edit')->with([
			'model'=>$model,
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // if ($id == 3) {
        //     return redirect()->route('course.index')->with('warning','buni taxrirlayolmasyiz');
        // }
        $model = Course::findorFail($id);
        
        $validator = Validator::make($request->all(),[
			'course_name' => 'required|max:25',
			'course_year' => 'required|max:10',

		]); 

        if ($validator->fails()) {
			return redirect()->route('course.edit',$id)->withErrors($validator)->withInput();
		}
        
        $model->course_name = $request->input('course_name');
        $model->course_year = $request->input('course_year');
        if ($model->update()) {
            return redirect()->route('course.index')->with('success','course ' . __('msg.successfully updated'));
        }
        
       
        return redirect()->route('course.edit',$id)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Course::findOrFail($id);
        
        if ($model->id == 3 || $model->id == 8) {
            return redirect()->route('course.index')->with('warning','buni o\'chiraolmaysiz');
        }
        $model->delete();
        return redirect()->route('course.index')->with('success','course ' . __('successfully deleted'));
    
    }
}
