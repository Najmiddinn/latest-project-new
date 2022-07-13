@extends('layouts.mainBackend')
@section('title')
    {{__('msg.Create')}}
@endsection

@section('content')
    <div class="page-header card">
    </div>
    <div class="card">
        <div class="content-header">
            <div class="container-fluid card-block">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{__('msg.Create')}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.index')}}">{{__('msg.Home')}}</a></li>
                            <li class="breadcrumb-item"><a href="{{route('role-has-permissions.index')}}">Role</a></li>
                            <li class="breadcrumb-item active">{{__('msg.Create')}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('role-has-permissions.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="permission_id">Permission name</label>
                            <select name="permission_id" id="permission_id" required class="form-control select2 @error('permission_id') is-invalid error-data-input @enderror" value="{{ old('permission_id') }}">
                                @if(!empty($permissions))
                                    <option value="">------------</option>
                                    @foreach($permissions as $model)
                                        <option value="{{$model->id}}">{{$model->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="error-data">@error('permission_id'){{$message}}@enderror</span>
                        </div>

                        <div class="form-group">
                            <label for="role_id">Role name</label>   
                            <select name="role_id" id="role_id" required class="form-control select2 @error('role_id') is-invalid error-data-input @enderror" value="{{ old('role_id') }}">
                                @if(!empty($roles))
                                    <option value="">------------</option>
                                    @foreach($roles as $model)
                                        <option value="{{$model->id}}">{{$model->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="error-data">@error('role_id'){{$message}}@enderror</span>
                        </div>

                        <div class="card-footer">
                            <a href="{{route('role-has-permissions.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
                            <button type="submit" class="btn btn-success">{{__('msg.Create')}}</button>
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </form>

@endsection





