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
                            <li class="breadcrumb-item"><a href="{{route('user-type.index')}}">Role</a></li>
                            <li class="breadcrumb-item active">{{__('msg.Create')}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('user-type.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="nameId">{{__('msg.Name')}}</label>
                            <input type="text" name="name" id="nameId" class="form-control @error('name') error-data-input is-invalid @enderror" value="{{ old('name') }}" required>
                            <span class="error-data">@error('name'){{$message}}@enderror</span>
                        </div>

                        

                        <div class="card-footer">
                            <a href="{{route('user-type.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
                            <button type="submit" class="btn btn-success">{{__('msg.Create')}}</button>
                        </div>

                    </div>
                </div>
            </div>
            {{-- <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="menuTypeId">{{__('msg.Type')}}</label>
                            <select name="menuType" id="menuTypeId" class="form-control @error('menuType') error-data-input @enderror" value="{{ old('menuType') }}">

                                <option value="0">Header</option>
                                <option value="1">Footer</option>
                            </select>
                            <span class="error-data">@error('menuType'){{$message}}@enderror</span>
                        </div>

                    <div class="form-group">
                        <label for="menuParent">{{__('msg.Parent')}}</label>
                              <select name="menuParent" id="menuParentId" class="form-control" value="{{ old('menuParent') }}">
                            @if(!empty($models))
                                <option value="">------------</option>
                                @foreach($models as $model)
                                    <option value="{{$model->id}}">{{$model->getTranslation('name',\App::getLocale())}}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="error-data">@error('menuParent'){{$message}}@enderror</span>

                    </div>
                        <div class="form-group">
                            <label for="menuOrderId" >{{__('msg.Order By')}}</label>
                            <input type="number" name="menuOrder" id="menuOrderId" class="form-control" value="{{ old('menuOrder') }}">
                            <span class="error-data">@error('menuORder'){{$message}}@enderror</span>
                        </div>

                        <div class="form-group">
                            <label for="menuStatusId">{{__('msg.Status')}}</label>
                            <select name="menuStatus" id="menuStatus" class="form-control @error('menuStatus') error-data-input is-invalid @enderror" value="{{ old('menuStatus') }}" >
                                <option value="1" >active</option>
                                <option value="0" >no active</option>
                            </select>
                            <span class="error-data">@error('menuStatus'){{$message}}@enderror</span>
                        </div>

                    </div>
                </div>
            </div> --}}
        </div>
    </form>

@endsection





