<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Frontend\SiteController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AdminUserController;
use App\Http\Controllers\Backend\BookCategoryContoller;
use App\Http\Controllers\Backend\BookContoller;
use App\Http\Controllers\Backend\BorowwingContoller;
use App\Http\Controllers\Backend\BorowwingNoReturnContoller;
use App\Http\Controllers\Backend\BorowwingReturnContoller;
use App\Http\Controllers\Backend\CourseContoller;
use App\Http\Controllers\Backend\ElektronBookContoller;
use App\Http\Controllers\Backend\MenuContoller;
use App\Http\Controllers\Backend\ModelHasPermissionController;
use App\Http\Controllers\Backend\ModelHasRoleController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\PersonalAccessTokenController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\RoleHasPermissonController;
use App\Http\Controllers\Backend\SearchbarContoller;
use App\Http\Controllers\Backend\StudentContoller;
use App\Http\Controllers\Backend\UserConroller;
use App\Http\Controllers\Backend\UserTypeController;
use App\Http\Controllers\Frontend\FBookCategoryController;
use App\Http\Controllers\User\UserDashboardController;
use App\Models\ModelHasPermission;
use App\Models\ModelHasRole;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\App;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//=========================frontend=============================
//from vendor/laravel/ui package ga qara
Auth::routes();

// $locale = App::currentLocale();
// if ($locale == 'ru') {
// }
// if (App::isLocale('en')) {
//     //
// }
//=========================frontend=============================
Route::get('locale/{locale}',function ($locale){
    session(['APP_LOCALE'=>$locale]);
    return redirect()->back();
})->name('fswitchLocale');


Route::group(['prefix'=>'/','middleware'=>['auth']],function(){
   
    Route::get('/',[SiteController::class, 'index'])->name('site.index');
    Route::get('/book-download/id/{id}/filename/{filename}',[SiteController::class, 'download'])->name('site.book-download');
    Route::get('/book-category/{id}',[FBookCategoryController::class, 'show'])->name('fbook-category.show');
    Route::get('/search',[SiteController::class, 'search'])->name('site.search');

});


//=========================user=============================
Route::group(['prefix'=>'user','middleware'=>['auth']],function(){
    Route::get('/', [UserDashboardController::class, 'index'])->name('home');

});

Route::get('/reload-captcha-r',[LoginController::class, 'reloadCaptcha'])->name('reload-captcha-r');
Route::get('/reload-captcha-b',[LoginController::class, 'reloadCaptcha'])->name('reload-captcha-b');
//=========================backend==========================================
Route::group(['prefix'=>'admin','middleware'=>['auth','admin']],function(){
    Route::get('/',[AdminController::class, 'index'])->name('admin.index');
    // Route::resource('user', 'UserConroller');
    //=======================================users roles===========================================
    Route::get('/admin-user/index',[AdminUserController::class, 'index'])->name('admin-user.index');
    
    Route::group(['prefix'=>'user'],function(){
        Route::get('/index',[UserConroller::class, 'index'])->name('user.index');
        Route::get('/create',[UserConroller::class, 'create'])->name('user.create');
        Route::post('/store',[UserConroller::class, 'store'])->name('user.store');
        Route::get('/edit/{id}',[UserConroller::class, 'edit'])->name('user.edit');
        Route::put('/update/{id}',[UserConroller::class, 'update'])->name('user.update');
        // Route::get('/show/{id}',[UserConroller::class, 'show'])->name('user.show');
        Route::delete('/destroy/{id}',[UserConroller::class, 'destroy'])->name('user.destroy');
    });
    
    Route::group(['prefix'=>'role'],function(){
        Route::get('/index',[RoleController::class, 'index'])->name('role.index');
        Route::get('/create',[RoleController::class, 'create'])->name('role.create');
        Route::post('/store',[RoleController::class, 'store'])->name('role.store');
        Route::get('/edit/{id}',[RoleController::class, 'edit'])->name('role.edit');
        Route::put('/update/{id}',[RoleController::class, 'update'])->name('role.update');
        // Route::get('/show/{id}',[RoleController::class, 'show'])->name('role.show'); 
        Route::delete('/destroy/{id}',[RoleController::class, 'destroy'])->name('role.destroy');
    });
    Route::group(['prefix'=>'permission'],function(){
        Route::get('/index',[PermissionController::class, 'index'])->name('permission.index');
        Route::get('/create',[PermissionController::class, 'create'])->name('permission.create');
        Route::post('/store',[PermissionController::class, 'store'])->name('permission.store');
        Route::get('/edit/{id}',[PermissionController::class, 'edit'])->name('permission.edit');
        Route::put('/update/{id}',[PermissionController::class, 'update'])->name('permission.update');
        // Route::get('/show/{id}',[PermissionController::class, 'show'])->name('permission.show');
        Route::delete('/destroy/{id}',[PermissionController::class, 'destroy'])->name('permission.destroy');
    });
    Route::group(['prefix'=>'role-has-permissions'],function(){
        Route::get('/index',[RoleHasPermissonController::class, 'index'])->name('role-has-permissions.index');
        Route::get('/create',[RoleHasPermissonController::class, 'create'])->name('role-has-permissions.create');
        Route::post('/store',[RoleHasPermissonController::class, 'store'])->name('role-has-permissions.store');
        Route::get('/edit/permissionId/{permission_id}/roleId/{role_id}',[RoleHasPermissonController::class, 'edit'])->name('role-has-permissions.edit');
        Route::put('/update/permissionId/{permission_id}/roleId/{role_id}',[RoleHasPermissonController::class, 'update'])->name('role-has-permissions.update');
        // Route::get('/show/permissionId/{permission_id}/roleId/{role_id}',[RoleHasPermissonController::class, 'show'])->name('role-has-permissions.show');
        Route::delete('/destroy/permissionId/{permission_id}/roleId/{role_id}',[RoleHasPermissonController::class, 'destroy'])->name('role-has-permissions.destroy');
    });
    Route::group(['prefix'=>'personal-access-token'],function(){
        Route::get('/index',[PersonalAccessTokenController::class, 'index'])->name('personal-access-token.index');
        // Route::get('/create',[PersonalAccessToken::class, 'create'])->name('personal-access-token.create');
        // Route::post('/store',[PersonalAccessToken::class, 'store'])->name('personal-access-token.store');
        // Route::get('/edit/{id}',[PersonalAccessToken::class, 'edit'])->name('personal-access-token.edit');
        // Route::put('/update/{id}',[PersonalAccessToken::class, 'update'])->name('personal-access-token.update');
        // Route::get('/show/{id}',[PersonalAccessToken::class, 'show'])->name('personal-access-token.show');
        // Route::get('/destroy/{id}',[PersonalAccessToken::class, 'destroy'])->name('personal-access-token.destroy');
        // Route::delete('/destroy/{id}',[PersonalAccessToken::class, 'destroy'])->name('personal-access-token.destroy');
    });

    // Route::group(['prefix'=>'model-has-role'],function(){
        // Route::get('/index',[ModelHasRoleController::class, 'index'])->name('model-has-role.index');
        // Route::get('/create',[ModelHasRoleController::class, 'create'])->name('model-has-role.create');
        // Route::post('/store',[ModelHasRoleController::class, 'store'])->name('model-has-role.store');
        // Route::get('/edit/{role_id}/model/{model_id}',[ModelHasRoleController::class, 'edit'])->name('model-has-role.edit');
        // Route::put('/update/role/{role_id}/model/{model_id}',[ModelHasRoleController::class, 'update'])->name('model-has-role.update');
        // Route::get('/show/{id}',[ModelHasRoleController::class, 'show'])->name('model-has-role.show');
        // Route::delete('/destroy/role/{role_id}/model/{model_id}',[ModelHasRoleController::class, 'destroy'])->name('model-has-role.destroy');
    // });
    // Route::group(['prefix'=>'model-has-permission'],function(){
        // Route::get('/index',[ModelHasPermissionController::class, 'index'])->name('model-has-permission.index');
        // Route::get('/create',[ModelHasPermissionController::class, 'create'])->name('model-has-permission.create');
        // Route::post('/store',[ModelHasPermissionController::class, 'store'])->name('model-has-permission.store');
        // Route::get('/edit/permission/{permission_id}/model/{model_id}',[ModelHasPermissionController::class, 'edit'])->name('model-has-permission.edit');
        // Route::put('/update/permission/{permission_id}/model/{model_id}',[ModelHasPermissionController::class, 'update'])->name('model-has-permission.update');
        // Route::get('/show/{id}',[ModelHasPermissionController::class, 'show'])->name('model-has-permission.show');
        // Route::delete('/destroy/permission/{permission_id}/model/{model_id}',[ModelHasPermissionController::class, 'destroy'])->name('model-has-permission.destroy');
    // });

    // Route::group(['prefix'=>'user-type'],function(){
        // Route::get('/index',[UserTypeController::class, 'index'])->name('user-type.index');
        // Route::get('/create',[UserTypeController::class, 'create'])->name('user-type.create');
        // Route::post('/store',[UserTypeController::class, 'store'])->name('user-type.store');
        // Route::get('/edit/{id}',[UserTypeController::class, 'edit'])->name('user-type.edit');
        // Route::put('/update/{id}',[UserTypeController::class, 'update'])->name('user-type.update');
        // Route::get('/show/{id}',[UserTypeController::class, 'show'])->name('user-type.show');
        // Route::delete('/destroy/{id}',[UserTypeController::class, 'destroy'])->name('user-type.destroy');
    // });
    //===========================================users roles end=======================================
    
    Route::group(['prefix'=>'menu'],function(){
        Route::get('/index',[MenuContoller::class, 'index'])->name('menu.index');
        Route::get('/create',[MenuContoller::class, 'create'])->name('menu.create');
        Route::post('/store',[MenuContoller::class, 'store'])->name('menu.store');
        Route::get('/edit/{id}',[MenuContoller::class, 'edit'])->name('menu.edit');
        Route::put('/update/{id}',[MenuContoller::class, 'update'])->name('menu.update');
        // Route::get('/show/{id}',[MenuContoller::class, 'show'])->name('menu.show');
        Route::delete('/destroy-multiple/',[MenuContoller::class, 'destroyMultiple'])->name('menu.destroyMultiple');
        Route::delete('/destroy/{id}',[MenuContoller::class, 'destroy'])->name('menu.destroy');
    });
    Route::group(['prefix'=>'student'],function(){
        Route::get('/index',[StudentContoller::class, 'index'])->name('student.index');
        Route::get('/create',[StudentContoller::class, 'create'])->name('student.create');
        Route::post('/store',[StudentContoller::class, 'store'])->name('student.store');
        Route::get('/edit/{id}',[StudentContoller::class, 'edit'])->name('student.edit');
        Route::put('/update/{id}',[StudentContoller::class, 'update'])->name('student.update');
        Route::get('/show/{id}',[StudentContoller::class, 'show'])->name('student.show');
        Route::delete('/destroy-multiple/',[StudentContoller::class, 'destroyMultiple'])->name('student.destroyMultiple');
        Route::delete('/destroy/{id}',[StudentContoller::class, 'destroy'])->name('student.destroy');
        
        Route::put('/student-borowwing-update/{id}',[StudentContoller::class, 'borowwing_update'])->name('student.borowwing-update');
        Route::delete('/student-borowwing-destroy/{id}',[StudentContoller::class, 'borowwing_destroy'])->name('student.borowwing-destroy');
    });
    Route::group(['prefix'=>'teacher'],function(){
        Route::get('/index',[StudentContoller::class, 'teacher'])->name('teacher.index');
        
    });
    Route::group(['prefix'=>'worker'],function(){
        Route::get('/index',[StudentContoller::class, 'worker'])->name('worker.index');
        
    });
    Route::group(['prefix'=>'course'],function(){
        Route::get('/index',[CourseContoller::class, 'index'])->name('course.index');
        Route::get('/create',[CourseContoller::class, 'create'])->name('course.create');
        Route::post('/store',[CourseContoller::class, 'store'])->name('course.store');
        Route::get('/edit/{id}',[CourseContoller::class, 'edit'])->name('course.edit');
        Route::put('/update/{id}',[CourseContoller::class, 'update'])->name('course.update');
        // Route::get('/show/{id}',[CourseContoller::class, 'show'])->name('course.show');
        Route::delete('/destroy/{id}',[CourseContoller::class, 'destroy'])->name('course.destroy');
    });
    Route::group(['prefix'=>'book'],function(){
        Route::get('/index',[BookContoller::class, 'index'])->name('book.index');
        Route::get('/by-category/{id}',[BookContoller::class, 'byCategory'])->name('book.by-category');
        Route::get('/create',[BookContoller::class, 'create'])->name('book.create');
        Route::post('/store',[BookContoller::class, 'store'])->name('book.store');
        // Route::post('/store-and-continue',[BookContoller::class, 'store_and_continue'])->name('book.store_and_continue');
        Route::get('/edit/{id}',[BookContoller::class, 'edit'])->name('book.edit');
        Route::put('/update/{id}',[BookContoller::class, 'update'])->name('book.update');
        // Route::get('/show/{id}',[BookContoller::class, 'show'])->name('book.show');
        Route::get('/export-to-pdf',[BookContoller::class,'pdfReport'])->name('book.pdfReport');  
        Route::get('/export-to-pdf-borowwing',[BookContoller::class,'pdfReportBorowwing'])->name('book.pdfReportBorowwing');  
        Route::get('/show/{id}',[BookContoller::class, 'show'])->name('book.show');
        Route::delete('/destroy-multiple/',[BookContoller::class, 'destroyMultiple'])->name('book.destroyMultiple');
        Route::delete('/destroy/{id}',[BookContoller::class, 'destroy'])->name('book.destroy');
    });
    Route::group(['prefix'=>'borowwing'],function(){
        Route::get('/index',[BorowwingContoller::class, 'index'])->name('borowwing.index');
        Route::get('/students',[BorowwingContoller::class, 'students'])->name('borowwing.students');
        Route::get('/books',[BorowwingContoller::class, 'books'])->name('borowwing.books');
        // Route::get('/create',[BorowwingContoller::class, 'create'])->name('borowwing.create');
        Route::post('/store',[BorowwingContoller::class, 'store'])->name('borowwing.store');
        Route::get('/edit/{id}',[BorowwingContoller::class, 'edit'])->name('borowwing.edit');
        Route::put('/update/{id}',[BorowwingContoller::class, 'update'])->name('borowwing.update');
        // Route::get('/show/{id}',[BorowwingContoller::class, 'show'])->name('borowwing.show');
        Route::delete('/destroy-multiple/',[BorowwingContoller::class, 'destroyMultiple'])->name('borowwing.destroyMultiple');
        Route::delete('/destroy/{id}',[BorowwingContoller::class, 'destroy'])->name('borowwing.destroy');
        
    });
    Route::group(['prefix'=>'borowwing-no-return'],function(){
        Route::get('/index',[BorowwingContoller::class, 'borowwing_no_return'])->name('borowwing-no-return.index');
        Route::delete('/destroy-multiple/',[BorowwingContoller::class, 'destroyMultiple'])->name('borowwing-no-return.destroyMultiple');
        Route::delete('/destroy/{id}',[BorowwingContoller::class, 'destroy'])->name('borowwing-no-return.destroy');
    });
    Route::group(['prefix'=>'borowwing-filter'],function(){
        Route::get('/index',[BorowwingContoller::class, 'borowwing_filter'])->name('borowwing-filter.index');
       });
    Route::group(['prefix'=>'borowwing-return'],function(){
        Route::get('/index',[BorowwingContoller::class, 'borowwing_return'])->name('borowwing-return.index');
        Route::delete('/destroy-multiple/',[BorowwingContoller::class, 'destroyMultiple'])->name('borowwing-return.destroyMultiple');
        Route::delete('/destroy/{id}',[BorowwingContoller::class, 'destroy'])->name('borowwing-return.destroy');
    });
    Route::group(['prefix'=>'searchbar'],function(){
        Route::get('/index',[SearchbarContoller::class, 'index'])->name('searchbar.index');
        Route::get('/search',[SearchbarContoller::class, 'search'])->name('searchbar.search');
        
    });
    Route::group(['prefix'=>'book-category'],function(){
        Route::get('/index',[BookCategoryContoller::class, 'index'])->name('book-category.index');
        Route::get('/create',[BookCategoryContoller::class, 'create'])->name('book-category.create');
        Route::post('/store',[BookCategoryContoller::class, 'store'])->name('book-category.store');
        Route::get('/edit/{id}',[BookCategoryContoller::class, 'edit'])->name('book-category.edit');
        Route::put('/update/{id}',[BookCategoryContoller::class, 'update'])->name('book-category.update');
        // Route::get('/show/{id}',[BookCategoryContoller::class, 'show'])->name('book-category.show');
        Route::delete('/destroy/{id}',[BookCategoryContoller::class, 'destroy'])->name('book-category.destroy');
    });
    Route::group(['prefix'=>'elektron-book'],function(){
        Route::get('/index',[ElektronBookContoller::class, 'index'])->name('elektron-book.index');
        Route::get('/create',[ElektronBookContoller::class, 'create'])->name('elektron-book.create');
        Route::post('/store',[ElektronBookContoller::class, 'store'])->name('elektron-book.store');
        Route::get('/edit/{id}',[ElektronBookContoller::class, 'edit'])->name('elektron-book.edit');
        Route::put('/update/{id}',[ElektronBookContoller::class, 'update'])->name('elektron-book.update');
        // Route::get('/show/{id}',[ElektronBookContoller::class, 'show'])->name('elektron-book.show');
        Route::delete('/destroy-multiple/',[ElektronBookContoller::class, 'destroyMultiple'])->name('elektron-book.destroyMultiple');
        Route::delete('/destroy/{id}',[ElektronBookContoller::class, 'destroy'])->name('elektron-book.destroy');
    });

    



    
});








