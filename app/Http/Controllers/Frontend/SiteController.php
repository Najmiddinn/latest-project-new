<?php

namespace App\Http\Controllers\frontend;


use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Contact;
use App\Models\ElektronBook;
use App\Models\Images;
use App\Models\Menu;
use App\Models\News;
use App\Models\WrongWord;
use App\Models\YoutubeVideos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SiteController extends Controller
{

    public function index()
    {
        $models = ElektronBook::where(['status'=>1])->orderBy('id','desc')->paginate(20);
        
        return view('frontend.index')->with([
           'models'=>$models

        ]);

    }
    public function download($id,$filename)
    {
        $filePath = public_path(). "/uploads/books/".$id;
        $file = public_path(). "/uploads/books/".$id.'/'.$filename;
        $headers = ['Content-Type: application/pdf'];
        // $headers = ['Content-Type: image/jpeg'];
        if (file_exists($file)) {
           return response()->download($file, $filename, $headers);
        } else {
            return redirect()->back()->with('success','failed');
        }
        
    }

    public function search(Request $request)
    {
        // kiritilgan malumotlarni shu funksiya bn tekshirsa kodlarni va ortiqcha narsalarni olmaydi .
        // function test_input($data) {
        //     $data = trim($data);
        //     $data = stripslashes($data);
        //     $data = htmlspecialchars($data);
        //     return $data;
        //   }

        $validator = Validator::make($request->all(),[
            'q' =>  ['required','string','max:100'],
        ]); 
        
        if ($validator->fails())
        {
            return redirect()->route('site.index')->withErrors($validator)->withInput();
        }
        $data = $request->all();
        $search_key = $request->input('q');
      
        
        $models = ElektronBook::where('title','like','%'.$search_key.'%')->orderBy('id','desc')->paginate(20);
        
        
        return view('frontend.searchbar',[
            'models'=>$models,
            'data'=>$data
        ]);


    }

  






    


}