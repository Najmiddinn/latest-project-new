<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Course;
use App\Models\Student;
use App\Models\Borowwing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentContoller extends Controller
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
        $models = Student::where('course_id' , '!=' , 3)->where('course_id' , '!=' , 8)->orderBy('id','desc')->paginate(50);
        $studentcategories = Course::where('id' , '!=' , 3)->where('id' , '!=' , 8)->orderBy('course_name','asc')->get();
        $studentCount = Student::where('course_id' , '!=' , 3)->where('course_id' , '!=' , 8)->get();
        $studentCount = count($studentCount);
		return view('backend.student.index',[
			'models'=>$models,
			'studentcategories'=>$studentcategories,
			'studentCount'=>$studentCount,
		]);
    }

    public function teacher()
    {
        $models = Student::where(['course_id'=>3])->orderBy('id','desc')->paginate(50);
        // $studentcategories = Course::all();
        $studentCount = Student::where(['course_id'=>3])->get();
        $studentCount = count($studentCount);
		return view('backend.teacher.index',[
			'models'=>$models,
			// 'studentcategories'=>$studentcategories,
			'studentCount'=>$studentCount,
		]);
    }

    public function worker()
    {
        $models = Student::where(['course_id'=>8])->orderBy('id','desc')->paginate(50);
        // $studentcategories = Course::all();
        $studentCount = Student::where(['course_id'=>8])->get();
        $studentCount = count($studentCount);
		return view('backend.worker.index',[
			'models'=>$models,
			// 'studentcategories'=>$studentcategories,
			'studentCount'=>$studentCount,
		]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $course = Course::all();
        return view('backend.student.create')->with([
            'course'=>$course
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
        if(!$request->ajax()){
            $validator = Validator::make($request->all(),[
                'course_id' =>  ['required', 'integer'],
                'first_name' => ['required', 'string','max:100'],
                'last_name' => ['required', 'string','max:100'],
                'birth_date' => ['required','date']

            ]); 

            if ($validator->fails()) {
                return redirect()->route('student.create')->withErrors($validator)->withInput();
            }

            $model = Student::create([
                'course_id' => $request->input('course_id'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'birth_date' => $request->input('birth_date'),

            ]);

            if ($model->course_id == 3) {
                return redirect()->route('teacher.index')->with('success','O\'qituvchi ' . __('msg.successfully created'));
            }elseif($model->course_id == 8){
                return redirect()->route('worker.index')->with('success','Ishchi xodim ' . __('msg.successfully created'));
             }
            else{
                return redirect()->route('student.index')->with('success','Talaba ' . __('msg.successfully created'));
            }

            return redirect()->route('student.create')->withInput();
        }

        if($request->ajax()){
           $validator = Validator::make($request->all(),[
                'course_id' =>  ['required', 'integer'],
                'first_name' => ['required', 'string','max:100'],
                'last_name' => ['required', 'string','max:100'],
                'birth_date' => ['required','date']

            ]); 
            if ($validator->fails())
            {
                return response()->json([
                    'success' => false,
                    'message' => $validator->getMessageBag()->toArray()

                ], 400); 
            }

            $model = Student::create([
                'course_id' => $request->input('course_id'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'birth_date' => $request->input('birth_date'),

            ]);
            if ($model->course_id == 3) {
                return response()->json([
                    'success' => true,
                    'message'=> $model->title .' O\'qituvchi muvaffaqiyatli qo\'shildi'
                ]);
            }elseif($model->course_id == 8){
                return response()->json([
                    'success' => true,
                    'message'=> $model->title .' Ishchi xodim muvaffaqiyatli qo\'shildi'
                ]);
            }
            else{
                return response()->json([
                    'success' => true,
                    'message'=> $model->title .' Talaba muvaffaqiyatli qo\'shildi'
                    ]);       
            }

            
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studentBorowwing = Borowwing::where(['student_id'=>$id])->paginate(50);
        // $studentBorowwingNoReturn = Borowwing::where(['student_id'=>$id,'status'=>0])->get();
        // $studentBorowwingReturn = Borowwing::where(['student_id'=>$id,'status'=>1])->get();
        $model = Student::findorFail($id);
        return view('backend.student.show')->with([
			'model'=>$model,
			'studentBorowwing'=>$studentBorowwing,
			// 'studentBorowwingNoReturn'=>$studentBorowwingNoReturn,
			// 'studentBorowwingReturn'=>$studentBorowwingReturn,
		]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course = Course::all();
        $model = Student::findorFail($id);
        return view('backend.student.edit')->with([
			'model'=>$model,
			'course'=>$course,
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = Student::findorFail($id);
        
        $validator = Validator::make($request->all(),[
			'course_name' =>  ['required', 'integer'],
			'first_name' => ['required', 'string','max:100'],
			'last_name' => ['required', 'string','max:100'],
			'birth_date' => 'required',

		]); 

        if ($validator->fails()) {
			return redirect()->route('student.edit',$id)->withErrors($validator)->withInput();
		}

        $model->course_id = $request->input('course_name');
        $model->first_name = $request->input('first_name');
        $model->last_name = $request->input('last_name');
        $model->birth_date = $request->input('birth_date');
        $model->update();

        if ($model->course_id == 3) {
            return redirect()->route('teacher.index')->with('success','O\'qituvchi ' . __('msg.successfully created'));
        }elseif($model->course_id == 8){
            return redirect()->route('worker.index')->with('success','Ishchi xodim ' . __('msg.successfully created'));
         
        }
        else{
            return redirect()->route('student.index')->with('success','Talaba ' . __('msg.successfully created'));
        }

       
        return redirect()->route('student.edit',$id)->withInput();
    }


    public function borowwing_update(Request $request, $id)
    {
        json_decode($request->title_edit);
        $model = Borowwing::findOrFail($id);

        $validator = Validator::make($request->all(),[
            'student_edit' =>  ['required', 'integer'],
            // 'book_edit' => ['required','integer'],
            'title_edit' => ['required','integer'],
            'date_borrowwed_edit' => ['required','date'],
            'date_return_edit' => ['nullable','date'],
            'status' => ['nullable','integer'],
            'book_code' => [
                'required','string','max:20',
                // Rule::unique('borowwing')->where('status',0)->ignore($id),
                Rule::unique('borowwing')->where('status', 0)->where('book_id', $request->title_edit)->ignore($id),
            ],

        ]); 
        
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $book = Book::findOrFail($request->title_edit);
         
        if ($request->title_edit != $model->book_id && $book->book_count == 0) {
            return redirect()->back()->with('warning','Bu kitob qolmagan ');
            
        }

        // $bookTable = Book::where(['id'=>$model->book_id])->first();
        $bookTable = Book::findOrFail($model->book_id);
        if ($request->title_edit != $bookTable->id || $request->status==1) {
            
            $bookTable->book_count +=1;
            $bookTable->save();
        }
       

        $model->student_id = $request->get('student_edit');
        $model->book_id = $book->id;
        $model->book_code =  $request->get('book_code');
        $model->status = $request->get('status');
        $model->date_borrowwed = $request->get('date_borrowwed_edit');
        $model->date_return = $request->get('date_return_edit');

        if ($model->update()){
            return redirect()->back()->with('success','' . __('msg.successfully updated')); 
        }
            
       
       
        return redirect()->back()->withInput();
      
    }


    public function borowwing_destroy($id)
    {
        $model = Borowwing::findOrFail($id);
        
        if (!empty($model)) {
            $model->delete();
            // return redirect()->route('borowwing.index')->with('success','borowwing ' . __('successfully deleted'));
            return redirect()->back()->with('success','' . __('msg.successfully deleted'));
            
        }
    }

    public function destroyMultiple(Request $request)
    {
        $booksId = $request->ids;
        if ($booksId) {
            DB::table("students")->whereIn('id',explode(",",$booksId))->delete();
            return response()->json(['success'=>"Belgilangan qatorlar muvaffaqiyatli o'chirildi"]);
        }
		
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Student::findOrFail($id);
        
        if (!empty($model)) {
            $model->delete();
            return redirect()->route('student.index')->with('success', __('successfully deleted'));
        }
    }
}
