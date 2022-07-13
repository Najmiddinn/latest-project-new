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
                            <li class="breadcrumb-item"><a href="{{route('role-has-permissions.index')}}">Role</a></li>
                            <li class="breadcrumb-item active">Role</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form action="{{route('role-has-permissions.update',['permission_id'=>$model->getPermission->id,'role_id'=>$model->getRole->id])}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">


                        <div class="form-group">
                            <label for="permission_id">{{__('msg.Permission')}}</label>
                            <select name="permission_id" id="permission_id" required class="form-control select2 @error('permission_id') is-invalid error-data-input @enderror" value="{{$model->getPermission->name, old('permission_id') }}">
                                @if(!empty($permissions))
                                @foreach($permissions as $p)
                                    @if($model->getPermission->id == $p->id)
                                        <option value="{{$p->id}}" selected > {{$p->name}} </option>
                                    @endif
                                    <option value="{{$p->id}}">{{$p->name}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span class="error-data">@error('permission_id'){{$message}}@enderror</span>
                        </div>

                        <div class="form-group">
                            <label for="role_id">{{__('msg.Role')}}</label>
                            <select name="role_id" id="role_id" required class="form-control select2 @error('role_id') is-invalid error-data-input @enderror" value="{{$model->getRole->name, old('role_id') }}">
                                @if(!empty($roles))
                                @foreach($roles as $r)
                                    @if($model->getRole->id == $r->id)
                                        <option value="{{$r->id}}" selected > {{$r->name}} </option>
                                    @endif
                                    <option value="{{$r->id}}">{{$r->name}}</option>
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



