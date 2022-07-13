<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BookCategory;
use App\Models\ElektronBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ElektronBookContoller extends Controller
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
        $models = ElektronBook::orderBy('id','desc')->paginate(50);
        $bookcategories = BookCategory::all();
        $elektronBookCount = ElektronBook::all();
		return view('backend.elektron-book.index',[
			'models'=>$models,
			'bookcategories'=>$bookcategories,
			'elektronBookCount'=>$elektronBookCount,
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
        return view('backend.elektron-book.create')->with([
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
            
            'category' => ['integer','required'],
            // 'book' => ['required','mimes:pdf,png,jpg'],
            'book' => ['required'],
            'book.*' => 'mimes:pdf,png,jpg',

        ]);
        if ($validator->fails()) {
			return redirect()->route('elektron-book.create')->withErrors($validator)->withInput();
		}

        if ($request->hasFile('book')) {
            $category = $request->category;
           
            // foreach ($request->file('book') as $file) 
            // {
            //     $filenameget = $file->getClientOriginalName();
            //     $filename = str_replace(".pdf"," ",$filenameget);
            //     $filenamehash = md5(time()). '.' . $file->getClientOriginalExtension();
            //     $size = $file->getSize();
            //     $model = ElektronBook::create([
            //         'category' => $category,
            //         'title' => $filename,
            //         'file' => $filenamehash,
            //         'extension' => $file->getClientOriginalExtension(),
            //         'size' => $size,
        
            //     ]);
                
            //     \Storage::makeDirectory(public_path('uploads/books/' . $model->id));
            //     $file->move(public_path('uploads/books/' . $model->id), $filenamehash);
               
            // }
                // dd( $_FILES['book']['name']);die;
            $file = $request->file('book');
            // $filenameget = $file->getClientOriginalName();
            $filenameget = $_FILES['book']['name'];
            $filename = str_replace(".pdf"," ",$filenameget);
            $filenamehash = md5(time()). '.' . $file->getClientOriginalExtension();
            $size = $file->getSize();
            $model = ElektronBook::create([
                'category' => $category,
                'title' => $filename,
                'file' => $filenamehash,
                'extension' => $file->getClientOriginalExtension(),
                'size' => $size,
    
            ]);
                
            \Storage::makeDirectory(public_path('uploads/books/' . $model->id));
            $file->move(public_path('uploads/books/' . $model->id), $filenamehash);

            // if ($model) {
                return redirect()->route('elektron-book.index')->with('success','kitob muvaffaqiyatli yaratildi');
    
            // }

        }

        return redirect()->route('elektron-book.create')->withInput();
		
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ElektronBook  $elektronBook
     * @return \Illuminate\Http\Response
     */
    public function show(ElektronBook $elektronBook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ElektronBook  $elektronBook
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = ElektronBook::findorFail($id);
        $category = BookCategory::all();
       	return view('backend.elektron-book.edit')->with([
       	    'model'=>$model,
            'category'=>$category

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ElektronBook  $elektronBook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {

        $validator = Validator::make($request->all(),[
            'category' => ['integer','required'],
            'status' => ['integer','required'],
            'title' => ['string','max:255','required'],
            'description' => ['string','max:500','nullable'],
            'book' => ['nullable','mimes:pdf,png,jpg'],
            // 'book' => ['required'],
            // 'book.*' => 'mimes:pdf,png,jpg',

        ]);
        if ($validator->fails()) {
			return redirect()->route('elektron-book.edit',$id)->withErrors($validator)->withInput();
		}

        $model = ElektronBook::findOrFail($id);

        $model->category = $request->get('category');
        $model->title = $request->get('title');
        $model->description = $request->get('description');
        $model->status = $request->get('status');

        if ($request->hasFile('book')) {
            $book_old = $model->file;
            \File::delete(public_path('uploads/books/'.$model->id.'/'.$book_old));
            
            $file = $request->file('book');

            $filenameget = $file->getClientOriginalName();
            $filename = str_replace(".pdf"," ",$filenameget);
            $filenamehash = md5(time()). '.' . $file->getClientOriginalExtension();
            $size = $file->getSize();

            // $model->title = $filename;
            $model->file = $filenamehash;
            $model->extension = $file->getClientOriginalExtension();
            $model->size = $size;

            $model->save();

            \Storage::makeDirectory(public_path('uploads/books/' . $model->id));
            $file->move(public_path('uploads/books/' . $model->id), $filenamehash);
            
            
            
        }

        if ($model->update()) {
            return redirect()->route('elektron-book.index')->with('success','kitob muvaffaqiyatli tahrirlandi');

        }

        return redirect()->route('elektron-book.edit',$id)->withInput();
    }


    public function destroyMultiple(Request $request)
    {
        $booksId = $request->ids;
        if ($booksId) {
            DB::table("elektron_books")->whereIn('id',explode(",",$booksId))->delete();
            return response()->json(['success'=>"Kitoblar muvaffaqiyatli o'chirildi"]);
        }
		
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ElektronBook  $elektronBook
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = ElektronBook::findOrFail($id);
        \File::deleteDirectory(public_path('uploads/books/'.$model->id));
        $model->delete();
    	return redirect()->route('elektron-book.index')->with('success','elektron-book deleted successfully');
    }
}
