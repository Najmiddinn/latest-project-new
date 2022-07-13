@extends('layouts.mainBackend')


@section('title')
  Admin Gradient able
@endsection


@section('content')



{{-- 

  @role('super admin')
    I am  a super admin...
  @else
      I am not a super admin...
  @endrole

 
  @role('information secretary')
    I am a information secretary!
  @else
    I am not a information secretary!
  @endrole

 --}}





<div class="row" style="margin-top: 10px">
  
  <div class="col-lg-3 col-6">

    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{$borowwingReturn}} ta</h3>
        <p>Bugun qaytarilgan kitoblar soni</p>
      </div>
      <div class="icon">
        {{-- <i class="far fa-envelope" style="color: #fff"></i> --}}
      </div>
      <a href="{{route('borowwing-return.index')}}" class="small-box-footer">Batafsil <i class="fas fa-message"></i></a>
    </div>

  </div>

  <div class="col-lg-3 col-6">

    <div class="small-box " style="background: #FD7E14">
      <div class="inner">
        <h3 style="color: #fff">{{$borowwingNoReturn}} ta</h3>
        
        <p style="color: #fff">bugun Berilgan kitoblar soni</p>
        
      </div>
      <div class="icon">
        {{-- <i class="fas fa-exclamation" style="color: #fff"></i> --}}
      </div>
      <a href="{{route('borowwing-no-return.index')}}" class="small-box-footer">Batafsil <i class="fas fa-message"></i></a>
    </div>

  </div>
  
</div>


@endsection



@section('scripts')

@endsection




