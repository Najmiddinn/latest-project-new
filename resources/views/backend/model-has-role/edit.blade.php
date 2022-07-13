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
                            <li class="breadcrumb-item"><a href="{{route('model-has-role.index')}}">model has role</a></li>
                            <li class="breadcrumb-item active">model has role</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form action="{{route('model-has-role.update',['role_id'=>$model->role_id,'model_id'=>$model->model_id])}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="user_id">{{__('msg.Permission')}}</label>
                            <select name="user_id" id="user_id" required class="form-control select2 @error('user_id') is-invalid error-data-input @enderror" value="{{$model->getModel->name, old('user_id') }}">
                                @if(!empty($users))
                                @foreach($users as $user)
                                    @if($model->model_id == $user->id)
                                        <option value="{{$user->id}}" selected > {{$user->name}} </option>
                                    @endif
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span class="error-data">@error('user_id'){{$message}}@enderror</span>
                        </div>

                        <div class="form-group">
                            <label for="role_id">{{__('msg.Role')}}</label>
                            <select name="role_id" id="role_id" required class="form-control select2 @error('role_id') is-invalid error-data-input @enderror" value="{{$model->getRole->name, old('role_id') }}">
                                @if(!empty($roles))
                                @foreach($roles as $role)
                                    @if($model->role_id == $role->id)
                                        <option value="{{$role->id}}" selected > {{$role->name}} </option>
                                    @endif
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span class="error-data">@error('role_id'){{$message}}@enderror</span>
                        </div>

                    <div class="card-footer">
                        <a href="{{route('model-has-role.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
                        <button type="submit" class="btn btn-success">{{__('msg.Create')}}</button>
                    </div>

                    </div>
                </div>
            </div>
        </div>

    </form>


@endsection



