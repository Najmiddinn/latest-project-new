@extends('layouts.mainBackend')


@section('title')
    {{__('msg.Update')}}
@endsection

@section('content')
    <div class="page-header card">
    </div>
    <div class="card">
        <div class="content-header">
            <div class="container-fluid card-block">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"> {{__('msg.Update')}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.index')}}"> {{__('msg.Home')}}</a></li>
                            <li class="breadcrumb-item"><a href="{{route('role.index')}}">Role</a></li>
                            <li class="breadcrumb-item active">Role</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form action="{{route('role.update',$model->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="roleNameId">{{__('msg.Name')}}</label>
                            <input type="text" name="roleName" id="roleNameId" class="form-control @error('roleName') error-data-input is-invalid @enderror" value="{{$model->name, old('roleName') }}" required>
                            <span class="error-data">@error('roleName'){{$message}}@enderror</span>
                        </div>

                        <div class="form-group">
                            <label for="roleGuardNameId">{{__('msg.guard name')}}</label>
                            <input type="text" name="roleGuardName" id="roleGuardNameId" class="form-control @error('roleGuardName') error-data-input is-invalid @enderror" value="{{$model->guard_name, old('roleGuardName') }}" required>
                            <span class="error-data">@error('roleGuardName'){{$message}}@enderror</span>
                        </div>

                    <div class="card-footer">
                        <a href="{{route('role.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
                        <button type="submit" class="btn btn-success">{{__('msg.Create')}}</button>
                    </div>

                    </div>
                </div>
            </div>
        </div>

    </form>


@endsection



