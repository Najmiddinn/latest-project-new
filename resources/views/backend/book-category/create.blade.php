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
                            <li class="breadcrumb-item"><a href="{{route('course.index')}}">Kitob kategoriyasi</a></li>
                            <li class="breadcrumb-item active">{{__('msg.Create')}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('book-category.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="name_id">{{__('msg.Name')}}</label>
                            <input type="text" name="name" id="name_id" class="form-control @error('name') error-data-input is-invalid @enderror" value="{{ old('name') }}" required>
                            <span class="error-data">@error('name'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="order_by_id">Tartibi</label>
                            <input type="text" name="order_by" id="order_by_id" class="form-control @error('order_by') error-data-input is-invalid @enderror" value="{{ old('order_by') }}">
                            <span class="error-data">@error('order_by'){{$message}}@enderror</span>
                        </div>

                        <div class="form-group">
                            <label for="parent_id">{{__('msg.Parent')}}</label>
                                  <select name="parent" id="parent_id" class="form-control" value="{{ old('parent') }}">
                                @if(!empty($models))
                                    <option value="">------------</option>
                                    @foreach($models as $model)
                                        <option value="{{$model->id}}">{{$model->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="error-data">@error('parent'){{$message}}@enderror</span>
                        </div>

                        <div class="card-footer">
                            <a href="{{route('book-category.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
                            <button type="submit" class="btn btn-success">{{__('msg.Create')}}</button>
                        </div>

                    </div>
                </div>
            </div>
         
        </div>
    </form>

@endsection


@section('scripts')
    <script>
        $(document).ready(function () {
        //   $('#course_yearId').inputmask('format':'dd/mm/yyyy');
        //   $("#course_yearId").inputmask("9999-9999",{ "placeholder": "yyyy-yyyy" });

        });
    </script>
@endsection




