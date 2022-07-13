<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borowwing;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BorowwingContoller extends Controller
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
        $models = Borowwing::orderBy('id','desc')->take(10)->get();
        // $models = Borowwing::orderBy('id','desc')->limit(20)->get();

        return view('backend.borowwing.index',[
			'models'=>$models,
		]);
    }

    public function paginate($items, $perPage = 50, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    
    public function borowwing_filter(Request $request)
    {   
        $validator = Validator::make($request->all(),[
            'from_year' =>  ['date'],
            'to_year' => ['date'],
        ]); 
        
        if ($validator->fails())
        {
            return redirect()->route('borowwing-no-return.index')->withErrors($validator)->withInput();
        }
        
        $data = $request->all();
        if ($request->from_year && $request->to_year) {
            $models1 = Borowwing::where(['status'=>0])->whereBetween('date_borrowwed',[$request->from_year,$request->to_year])->orderBy('date_borrowwed','desc')->get();
            $models2 = Borowwing::where(['status'=>1])->whereBetween('date_return',[$request->from_year,$request->to_year])->orderBy('date_return','desc')->get();
            
            $borowwingNoReturn = Borowwing::where(['status'=>0])->whereBetween('date_borrowwed',[$request->from_year,$request->to_year])->get();
            $borowwingReturn = Borowwing::where(['status'=>1])->whereBetween('date_return',[$request->from_year,$request->to_year])->get();
            $models = $models1->merge($models2)->sortDesc()->all();
            $models = $this->paginate($models)->withPath('/admin/borowwing-filter/index');

            $filterbydatefrom = $request->from_year;
            $filterbydateto = $request->to_year;
        }else{
            $models1 = Borowwing::where(['status'=>0])->whereBetween('date_borrowwed',[date("Y-m-d", strtotime("-1 months")),date("Y-m-d")])->get();
            $models2 = Borowwing::where(['status'=>1])->whereBetween('date_return',[date("Y-m-d", strtotime("-1 months")),date("Y-m-d")])->get();
            $models = $models1->merge($models2)->sortDesc()->all();

            $models = $this->paginate($models)->withPath('/admin/borowwing-filter/index');

            // $models = Borowwing::whereBetween('date_borrowwed',[date("Y-m-d", strtotime("-1 months")),date("Y-m-d")])->orWhereBetween('date_return',[date("Y-m-d", strtotime("-1 months")),date("Y-m-d")])->orderBy('id','desc')->paginate(50);
            $borowwingNoReturn = Borowwing::where(['status'=>0])->whereBetween('date_borrowwed',[date("Y-m-d", strtotime("-1 months")),date("Y-m-d")])->get();
            $borowwingReturn = Borowwing::where(['status'=>1])->WhereBetween('date_return',[date("Y-m-d", strtotime("-1 months")),date("Y-m-d")])->get();
            $filterbydatefrom = '';
            $filterbydateto = '';
        }
        // dump($models);die;
        return view('backend.borowwing-filter.index',[
			'models'=>$models,
            'data'=>$data,
            'borowwingNoReturn'=>$borowwingNoReturn,
            'borowwingReturn'=>$borowwingReturn,
            'filterbydatefrom'=>$filterbydatefrom,
            'filterbydateto'=>$filterbydateto,
		]);
    }
    public function borowwing_no_return(Request $request)
    {   
        $validator = Validator::make($request->all(),[
            'from_year' =>  ['date'],
            'to_year' => ['date'],
        ]); 
        
        if ($validator->fails())
        {
            return redirect()->route('borowwing-no-return.index')->withErrors($validator)->withInput();
        }
        $data = $request->all();
        if ($request->from_year && $request->to_year) {
            $borowwingNoReturn = Borowwing::where(['status'=>0])->get();
            $borowwingNoReturn = count($borowwingNoReturn);
            $models = Borowwing::whereBetween('date_borrowwed',[$request->from_year,$request->to_year])->where(['status'=>0])->orderBy('date_borrowwed','desc')->paginate(50);
        }else{
            $models = Borowwing::where(['status'=>0])->orderBy('date_borrowwed','desc')->paginate(50);
            $borowwingNoReturn = Borowwing::where(['status'=>0])->get();
            $borowwingNoReturn = count($borowwingNoReturn);
        }
        // dump($models);die;
        return view('backend.borowwing-no-return.index',[
			'models'=>$models,
            'data'=>$data,
			'borowwingNoReturn'=>$borowwingNoReturn,
		]);
    }
    public function borowwing_return(Request $request)
    {   
        $validator = Validator::make($request->all(),[
            'from_year' =>  ['date'],
            'to_year' => ['date'],
        ]); 
        
        if ($validator->fails())
        {
            return redirect()->route('borowwing-return.index')->withErrors($validator)->withInput();
        }
        $data = $request->all();
        if ($request->from_year && $request->to_year) {
            $borowwingReturn = Borowwing::where(['status'=>1])->get();
            $borowwingReturn = count($borowwingReturn);
            $models = Borowwing::whereBetween('date_return',[$request->from_year,$request->to_year])->where(['status'=>1])->orderBy('date_return','desc')->paginate(50);
        }else{
            $models = Borowwing::where(['status'=>1])->orderBy('date_return','desc')->paginate(50);
            $borowwingReturn = Borowwing::where(['status'=>1])->get();
            $borowwingReturn = count($borowwingReturn);
        }
        // $models = Borowwing::where(['status'=>1])->orderBy('id','desc')->paginate(50);
        return view('backend.borowwing-return.index',[
			'models'=>$models,
			'data'=>$data,
			'borowwingReturn'=>$borowwingReturn,
		]);
    }

    public function students()
    {
        $students = Student::all();

	    return response()->json([
          'students' => $students,
	    ]);

    }
    public function books()
    {
        $books = Book::all();

	    return response()->json([
          'books' => $books,
	    ]);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        json_decode($request->title);
        $validator = Validator::make($request->all(),[
            'student' =>  ['required', 'integer'],
            // 'book' => ['required','integer'],
            'title' => ['required','integer'],
            'date_borrowwed' => ['required','date'],
            'book_code' => [
                'required','string','max:20',
                Rule::unique('borowwing')->where('status', 0)->where('book_id', $request->title),
                // Rule::unique('borowwing')->where('status', 0),
                
            ],
           

            ]); 
        
        if ($validator->fails())
        {
            return redirect()->route('borowwing.index')->withErrors($validator)->withInput();
        }
        $book = Book::findOrFail($request->title);
        if ($book->book_count==0) {
            return redirect()->route('borowwing.index')->with('warning','Bu kitob qolmagan ');
        }
        $book->book_count -=1;
        $book->save();
        
        $model = Borowwing::create([
            'student_id' => $request->get('student'),
            'book_id' => $book->id,
            'book_code' => $request->get('book_code'),
            'date_borrowwed' => $request->get('date_borrowwed'),

        ]);

        if ($model) {
            return redirect()->route('borowwing.index')->with('success','book ' . __('msg.successfully created'));

        }

        return redirect()->route('borowwing.create')->withInput();
      
      
 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Borowwing  $borowwing
     * @return \Illuminate\Http\Response
     */
    public function show(Borowwing $borowwing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Borowwing  $borowwing
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $borowwing = Borowwing::findOrFail($id);
        $bookitem = Book::findOrFail($borowwing->book_id);
        $student = Student::findOrFail($borowwing->student_id);
        $booksdataedit = Book::all();
        $students = Student::all();

	    return response()->json([
	      'borowwing' => $borowwing,
	    //   'book_code' => $book->book_code,
	      'booksdataedit' => $booksdataedit,
	      'bookitem' => $bookitem,
	      'student_id' => $student->id,
	      'student_first_name' => $student->first_name,
	      'student_last_name' => $student->last_name,
          'students' => $students,
	    ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Borowwing  $borowwing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
            return redirect()->route('borowwing.index')->withErrors($validator)->withInput();
        }

        $book = Book::findOrFail($request->title_edit);
         
        if ($request->title_edit != $model->book_id && $book->book_count == 0) {
            return redirect()->route('borowwing.index')->with('warning','Bu kitob qolmagan ');
            
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

    public function destroyMultiple(Request $request)
    {
        $booksId = $request->ids;
        if ($booksId) {
            DB::table("borowwing")->whereIn('id',explode(",",$booksId))->delete();
            return response()->json(['success'=>"Talabalar muvaffaqiyatli o'chirildi"]);
        }
		
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Borowwing  $borowwing
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Borowwing::findOrFail($id);
        
        if (!empty($model)) {
            $model->delete();
            // return redirect()->route('borowwing.index')->with('success','borowwing ' . __('successfully deleted'));
            return redirect()->back()->with('success','' . __('msg.successfully deleted'));
            
        }
    }
}
