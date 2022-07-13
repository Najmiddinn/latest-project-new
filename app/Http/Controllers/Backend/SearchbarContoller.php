<?php

namespace App\Http\Controllers\Backend;

use App\components\StaticFunctions;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Student;
use App\Models\Borowwing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Null_;
use PhpOption\None;

class SearchbarContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        return view('backend.searchbar.index',[
          
        ]);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'search_key' =>  ['required','string','max:100'],
        ]); 
        
        if ($validator->fails())
        {
            return redirect()->route('searchbar.index')->withErrors($validator)->withInput();
        }

        $search_key = $request->input('search_key');
      
        
        $students = Student::where('first_name','like','%'.$search_key.'%')
        ->orWhere('last_name','like','%'.$search_key.'%')
        ->orderBy('id','desc')->paginate(50);
        
        
        $books = Book::where('title','like','%'.$search_key.'%')
        ->orderBy('id','desc')->paginate(50);


        if (!$books->isEmpty()) {
            $booksmodel = $books;
        }else{
            $booksmodel = null;
        }

        if (!$students->isEmpty()) {
            $studentsmodel = $students;
        }else{
            $studentsmodel = null;
        }
        
        if ( $studentsmodel == null && $booksmodel == null) {
            $noresult = 'xech nima topilmadi';
        }else{
            $noresult = null;
        }
        
        return view('backend.searchbar.searchResult',[
            'studentsmodel'=>$studentsmodel,
            'booksmodel'=>$booksmodel,
            'noresult'=>$noresult,
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
