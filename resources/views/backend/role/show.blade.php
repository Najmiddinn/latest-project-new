@extends('layouts.mainBackend')


@section('title')
  View Role
@endsection

@section('content')



<div class="page-header card">
</div>
<div class="card">
<div class="content-header">
  <div class="container-fluid card-block">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">View Role</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.index')}}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('role.index')}}">Index Role</a></li>
          <li class="breadcrumb-item active">View Role</li>
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
                    <td>{{__('msg.Name') }}</td>
                    <td>{{ $model->name }}</td>
                </tr>
                <tr>
                    <td>{{__('msg.GuardName')}}</td>
                    <td>{{ $model->guard_name }}</td>
                </tr>
                <tr>
                    <td>{{__('msg.Created date')}}</td>
                    <td>{{ $model->created_at}}</td>
                </tr>
                <tr>
                    <td>{{__('msg.Updated date')}}</td>
                    <td>{{ $model->updated_at}}</td>
                </tr>

                 <tr>
                  <td>
                     <div class="action-content-view">
               
                     <a href="{{route('role.index')}}" class="btn btn-success" title="cancel">
                          cancel
                      </a>
                      <a href="{{route('role.edit',$model->id)}}" class="btn btn-primary" title="update">
                          update
                      </a>  
                     
                    <a href="{{route('role.destroy',$model->id)}}" class="delete-data-item btn btn-danger" title="delete">
                        delete
                    </a>
                    </div>
                  </td>
                  
                 </tr>
            </tbody>
        </table>
</div>

<style>

</style>
@endsection





