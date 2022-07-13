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
                            <li class="breadcrumb-item"><a href="{{route('course.index')}}">Course</a></li>
                            <li class="breadcrumb-item active">{{__('msg.Create')}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('course.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="course_nameId">{{__('msg.Name')}}</label>
                            <input type="text" name="course_name" id="course_nameId" class="form-control @error('course_name') error-data-input is-invalid @enderror" value="{{ old('course_name') }}" required>
                            <span class="error-data">@error('course_name'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="course_yearId">O'quv yili</label>
                            <input type="text" name="course_year" id="course_yearId" class="form-control @error('course_year') error-data-input is-invalid @enderror" value="{{ old('course_year') }}" required>
                            <span class="error-data">@error('course_year'){{$message}}@enderror</span>
                        </div>

                        <div class="card-footer">
                            <a href="{{route('course.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
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
          $("#course_yearId").inputmask("9999-9999",{ "placeholder": "yyyy-yyyy" });

        });
    </script>
@endsection




