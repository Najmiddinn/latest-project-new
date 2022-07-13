@extends('layouts.mainBackend')
@section('title'){{__('View')}}@endsection

@section('content')

<div class="page-header card">
</div>
<div class="card">
<div class="content-header">
  <div class="container-fluid card-block">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Talaba</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.index')}}">{{__("msg.Home")}}</a></li>
          <li class="breadcrumb-item"><a href="{{route('student.index')}}">Talaba</a></li>
          <li class="breadcrumb-item active">{{__('msg.Show')}}</li>
        </ol>
      </div>
    </div>
  </div>
</div>

</div>

<div class="card">
<div class="card-block table-border-style">
  <table class="table table-borderet table-hover">
      <thead >
          <tr>
              <th>#</th>
              <th>#</th>
          </tr>
      </thead>
      <tbody>
          <tr>
            <td>Familyasi</td>
            <td>{{$model->first_name}}</td>      
          </tr>
          <tr>  
            <td>Ismi</td>
            <td>{{$model->last_name}}</td>
          </tr>
          <tr>  
            <td>Tug'ilgan yili</td>
            <td>{{$model->birth_date}}</td>
          </tr>
          <tr>  
            <td>Kursi</td>
            <td>{{$model->getCourse->course_name}}</td>
          </tr>

      </tbody>
  </table> 
  
    <div class="action-content-view container" style="padding-bottom: 25px">
                
      <a href="{{route('student.index')}}" class="btn btn-primary" title="cancel">
          cancel
      </a>
      <a href="{{route('student.edit',$model->id)}}" class="btn btn-success" title="update">
          update
      </a>  
      <form style="display: inline-block;" action="{{route('student.destroy',$model->id)}}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" id="delete-data-item" class="btn btn-danger" title="delete">
          <i class="ti-trash"></i> delete
        </button>
      </form>
    </div>
  </div>
</div>

<style>

</style>
@endsection









