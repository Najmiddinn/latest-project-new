<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 1) {
                return $next($request); // user_type=1 bolgan foydalanuvchilar otkaziladi 
            }else{
            //    return redirect('/login');
               return redirect('/'); // user_type=1 bolmagan foydalanuvchilar bosh sahifaga qaytarib yuboriladi 
            }
        }else{
            return redirect()->with('warning','please login first');
        }
        
    }
}
