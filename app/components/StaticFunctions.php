<?php



namespace App\components;

use Illuminate\Http\Request;

class StaticFunctions {

    public static function passwordGeneration() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789#$%&*()-_=+";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    public static function binary_search($list,$item){
        $low = 0;
        $high = count($list)-1;
        while($low <= $high){
            $mid = (int)(($low + $high) / 2);
            $guess = $list[$mid];
            if ($guess == $item) {
                return $mid;
            }elseif ($guess > $item) {
                $high = $mid - 1;
            }else{
                $low = $mid + 1;
            }
        }
        return $mid;
    }

    public static function kcfinder($text) {

        $replace = config('params.frontend').'/kcfinder/upload/images/';
    
        $replace2 = config('params.frontend').'/kcfinder/upload/files/';
    
        $result = str_replace('/kcfinder/upload/images/',$replace,$text);
    
        $result = str_replace('/kcfinder/upload/files/',$replace2,$result);
    
        return $result;
    
        }


//    public function resizeImage(){
//
//    }

    public static function set_active( $route ) {
        if( is_array( $route ) ){
            return in_array(Request::path(), $route) ? 'active' : '';
        }
        return Request::path() == $route ? 'active' : '';
    }
    // bu view.php filega yoziladu <li class = "{{ set_active('admin/users') }}"><a href="{{ url('/admin/users/') }}">Users</a></li> 


}