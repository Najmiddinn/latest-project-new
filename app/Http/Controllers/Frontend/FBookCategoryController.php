<?php

namespace App\Http\Controllers\frontend;


use App\Http\Controllers\Controller;
use App\Models\BookCategory;
use App\Models\ElektronBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class FBookCategoryController extends Controller
{

    public function show($id)
    {
        $models = ElektronBook::where(['status'=>1,'category'=>$id])->orderBy('id','desc')->paginate(20);
        $category = BookCategory::findOrFail($id);
        return view('frontend.book-category.bc-show')->with([
           'models'=>$models,
           'category'=>$category

        ]);

    }
   

  






    


}