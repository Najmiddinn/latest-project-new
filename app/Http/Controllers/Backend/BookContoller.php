<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\ElektronBook;
use App\Models\Borowwing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BookContoller extends Controller
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
        $models = Book::orderBy('id','desc')->paginate(50);
        $bookcategories = BookCategory::all();
        $bookCount = Book::all();
        // dd($bookCount->sum->book_count);die;
        // $bookCount = count($bookCount);
        
		return view('backend.book.index',[
			'models'=>$models,
			'bookcategories'=>$bookcategories,
			'bookCount'=>$bookCount,
		]);
    }
    public function byCategory($id)
    {
        $models = Book::where('category',$id)->orderBy('id','desc')->paginate(50);
        $bookcategory = BookCategory::findOrfail($id);
		return view('backend.book.byCategory',[
			'models'=>$models,
			'bookcategory'=>$bookcategory,
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
        return view('backend.book.create')->with([
            'models'=>$models,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   
    protected $rules = [ 
        
        'title' => 'required|unique:books|string|max:250',
        // 'book_code' => 'nullable|unique:books|integer|max:4000000',
        // 'book_code' => 'nullable|unique:books,book_code|integer|max:4000000',
    ];
    
    public function store(Request $request)
    {
        $rules = $this->rules;
     
        if(!$request->ajax()){
            $validator = Validator::make($request->all(),[
                
                'title'=>$rules['title'],
                // 'book_code'=>$rules['book_code'],
                'author' => ['required', 'string','max:150'],
                'publisher' => ['required', 'string','max:200'],
                'book_count' =>  ['required', 'integer','max:4000000'],
                'category' =>  ['required', 'integer'],
                

            ]); 

            if ($validator->fails()) {
                return redirect()->route('book.create')->withErrors($validator)->withInput();
            }

            $model = Book::create([
                'category' => $request->get('category'),
                // 'book_code' => $request->input('book_code'),
                'book_count' => $request->input('book_count'),
                'title' => $request->input('title'),
                'author' => $request->input('author'),
                'publisher' => $request->input('publisher'),

            ]);

            if ($model) {
                return redirect()->route('book.index')->with('success','book ' . __('msg.successfully created'));

            }

            return redirect()->route('book.create')->withInput();
        }
        
        //ajax request   
        if($request->ajax()){
            $validator = Validator::make($request->all(),[
                'title'=>$rules['title'],
                // 'book_code'=>$rules['book_code'],
                'category' => ['required', 'integer'],
                'author' => ['required', 'string','max:150'],
                'publisher' => ['required', 'string','max:200'],
                'book_count' =>  ['required', 'integer','max:4000000'],
                
            ]); 
            if ($validator->fails())
            {
                return response()->json([
                    'success' => false,
                    'message' => $validator->getMessageBag()->toArray()

                ], 400); 
            }

            $model = Book::create([
                'category' => $request->get('category'),
                // 'book_code' => $request->input('book_code'),
                'book_count' => $request->input('book_count'),
                'title' => $request->input('title'),
                'author' => $request->input('author'),
                'publisher' => $request->input('publisher'),
    
            ]);

            return response()->json([
                'success' => true,
                'message'=> $model->title .' kitobi muvaffaqiyatli qo\'shildi'
                ]);   
            
        }
        
    }
    
    // public function store_and_continue(Request $request)
    // {
        
    // }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Book::findorFail($id);
        $category = BookCategory::all();
        return view('backend.book.edit')->with([
			'model'=>$model,
			'category'=>$category,
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = Book::findorFail($id);
        
        // $rules = $this->rules;
        // $rules['title'] = $rules['title'].',id,'.$id;
        // $rules['book_code'] = $rules['book_code'].',id,'.$id;
        
        $validator = Validator::make($request->all(),[
            // $rules['title'],
            // $rules['book_code'],
            'title' => 'required|string|max:250|unique:books'.',id,'.$id,
            // 'book_code' => 'nullable|unique:books,book_code|integer|max:4000000'.',id,'.$id,
			'author' => ['required', 'string','max:150'],
			'publisher' => ['required', 'string','max:200'],
			'book_count' =>  ['required', 'integer'],
            'category' =>  ['required', 'integer'],

		]); 

        if ($validator->fails()) {
			return redirect()->route('book.edit',$id)->withErrors($validator)->withInput();
		}

        $model->category = $request->get('category');
        // $model->book_code = $request->input('book_code');
        $model->book_count = $request->input('book_count');
        $model->title = $request->input('title');
        $model->author = $request->input('author');
        $model->publisher = $request->input('publisher');
        

        if ($model->update()) {
            return redirect()->route('book.index')->with('success','book ' . __('msg.successfully updated'));
        }
        
       
        return redirect()->route('book.edit',$id)->withInput();
    }




    public function pdfReport(){

        $bookcategories = BookCategory::all();
        $bookCount = Book::all();
        // $bookCount = count($bookCount);
        $eBookCount = ElektronBook::all();
        $eBookCount = count($eBookCount);
        view()->share('model',[
            'bookcategories' => $bookcategories,
            'bookCount' => $bookCount,
            'eBookCount' => $eBookCount,
        ]);

        $pdf = \PDF::loadView('backend.book.pdfReport', [
            'bookcategories' => $bookcategories,
            'bookCount' => $bookCount,
            'eBookCount' => $eBookCount,
        ]);
        

        // //pdfga sahifa raqami qo'yilyapti
        // $dom_pdf = $pdf->getDomPDF();
        // $canvas = $dom_pdf ->get_canvas();
        // $canvas->page_text(45, 790, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        // $canvas->page_text(45, 805, route('frontend.index'), null, 10, array(0, 0, 0));

        return $pdf->download('Hisobot - '. date('Y-m-d') . '.pdf');

    }

    public function pdfReportBorowwing(){

        $bookcategories = BookCategory::all();
        $brbooks = Borowwing::with('getBook')->where(['status'=>0])->get();
       
        // $borowwingBooks->

        // print_r($brbooks);die;

        view()->share('model',[
            'bookcategories' => $bookcategories,
            'brbooks' => $brbooks,
            
        ]);

        $pdf = \PDF::loadView('backend.book.pdfReportBorowwing', [
            'bookcategories' => $bookcategories,
            'brbooks' => $brbooks,
        ]);
        

        // //pdfga sahifa raqami qo'yilyapti
        // $dom_pdf = $pdf->getDomPDF();
        // $canvas = $dom_pdf ->get_canvas();
        // $canvas->page_text(45, 790, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        // $canvas->page_text(45, 805, route('frontend.index'), null, 10, array(0, 0, 0));

        return $pdf->download('Hisobot - '. date('Y-m-d') . '.pdf');

    }


    public function destroyMultiple(Request $request)
    {
        $booksId = $request->ids;
        if ($booksId) {
            DB::table("books")->whereIn('id',explode(",",$booksId))->delete();
            return response()->json(['success'=>"kitoblar muvaffaqiyatli o'chirildi"]);
        }
		
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Book::findOrFail($id);
        
        if (!empty($model)) {
            $model->delete();
            return redirect()->route('book.index')->with('success','book ' . __('successfully deleted'));
        }
    }

}
