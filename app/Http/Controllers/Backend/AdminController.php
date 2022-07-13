<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Borowwing;
use App\Models\Contact;
use Illuminate\Http\Request;

use App\Models\Menu;
use App\Models\WrongWord;
use kcfinder\session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash as FacadesHash;

class AdminController extends Controller
{
	public function __construct()
    {
        $this->middleware('permission:data-create|data-edit|data-show|data-delete|data-all', ['only' => ['index']]);
        $this->middleware('permission:data-create|data-all', ['only' => ['create','store']]);
        $this->middleware('permission:data-show|data-all', ['only' => ['show']]);
        $this->middleware('permission:data-edit|data-all', ['only' => ['edit','update']]);
        $this->middleware('permission:data-delete|data-all', ['only' => ['destroy']]);
        
    }
    
	public function index()
	{
		// echo date("Y-m-d", strtotime("-1 months"));die;
		$borowwingReturn = Borowwing::where(['status'=>1,'date_return'=>date('Y-m-d')])->get();
		$borowwingReturn = count($borowwingReturn);

		$borowwingNoReturn = Borowwing::where(['status'=>0,'date_borrowwed'=>date('Y-m-d')])->get();
		$borowwingNoReturn = count($borowwingNoReturn);

		return view('backend.index')->with([
			'borowwingReturn'=>$borowwingReturn,
			'borowwingNoReturn'=>$borowwingNoReturn,
			
		]);

	}


	








}
