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
                            <li class="breadcrumb-item"><a href="{{route('user.index')}}">User</a></li>
                            <li class="breadcrumb-item active">User</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form action="{{route('user.update',$model->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="nameId">{{__('msg.Name')}}</label>
                            <input type="text" name="name" id="nameId" class="form-control @error('name') error-data-input is-invalid @enderror" value="{{$model->name, old('name') }}" required>
                            <span class="error-data">@error('name'){{$message}}@enderror</span>
                        </div>

                        <div class="form-group">
                            <label for="emailId">email</label>
                            <input type="email" name="email" id="emailId" class="form-control @error('email') error-data-input @enderror" value="{{$model->email, old('email') }}" required>
                            <span class="error-data">@error('email'){{$message}}@enderror</span>
                        </div>
                        
                        <div class="form-group">
                            <label for="user_type_id">{{__('msg.Category')}}</label>
                            <select name="user_type" id="user_type_id" required class="form-control select2 @error('user_type') is-invalid error-data-input @enderror" value="{{ old('user_type') }}">
                                @if(!empty($user_type))
                                  @foreach($user_type as $utp)
                                            @if($model->user_type == $utp->id)
                                                <option value="{{$utp->id}}" selected > {{$utp->name}} </option>
                                            @endif
                                    <option value="{{$utp->id}}">{{$utp->name}}</option>
                                  @endforeach
                                  @endif
                            </select>
                            <span class="error-data">@error('user_type'){{$message}}@enderror</span>
                        </div>

                        <div class="form-group">
                            <label for="current_password">{{__('msg.Password')}} (joriy parol)</label>
                            <input type="password" name="current_password"  id="current_password" class="form-control @error('current_password') is-invalid @enderror" value="{{ old('current_password') }}" >
                            <span class="error-data">@if (session('current_password')) {{ session('current_password') }}@endif @error('current_password'){{$message}}@enderror</span>
                        </div>
                        

                        <div class="form-group">
                            <label for="passwordId">{{__('msg.Password')}}</label>
                            <input type="password" name="password"  id="passwordId" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" >
                            <span class="error-data">@error('password'){{$message}}@enderror</span>
                        </div>
                
                        <div class="form-group">
                            <label for="password_confirmation_id">{{__('msg.Password confirmation')}}</label>
                            <input type="password" name="password_confirmation"  id="password_confirmation_id" class="form-control " value="{{ old('password') }}" >
                            <span class="error-data">@error('password_confirmation'){{$message}}@enderror</span>
                        </div>

                    <div class="card-footer">
                        <a href="{{route('user.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
                        <button type="submit" class="btn btn-success">{{__('msg.Create')}}</button>
                    </div>

                    </div>
                </div>
            </div>
        </div>

    </form>


@endsection



