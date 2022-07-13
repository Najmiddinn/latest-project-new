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
                            <li class="breadcrumb-item"><a href="{{route('student.index')}}">Talaba</a></li>
                            <li class="breadcrumb-item active">{{__('msg.Update')}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form action="{{route('student.update',$model->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">

                        
                        <div class="form-group">
                            <label for="first_nameId">Familyasi</label>
                            <input type="text" name="first_name" id="first_nameId" class="form-control @error('first_name') error-data-input is-invalid @enderror" value="{{ $model->first_name, old('first_name') }}" required>
                            <span class="error-data">@error('first_name'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="last_nameId">Ismi</label>
                            <input type="text" name="last_name" id="last_nameId" class="form-control @error('last_name') error-data-input is-invalid @enderror" value="{{$model->last_name, old('last_name') }}" required>
                            <span class="error-data">@error('last_name'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="birth_dateId">Tug'ilgan yili</label>
                            <input type="text" name="birth_date" id="birth_dateId" class="form-control @error('birth_date') error-data-input is-invalid @enderror" value="{{$model->birth_date, old('birth_date') }}" required>
                            <span class="error-data">@error('birth_date'){{$message}}@enderror</span>
                        </div>


                        <div class="form-group">
                            <label for="course_nameId">{{__('msg.Category')}}</label>
                              <select style="width: 100%;" name="course_name" id="course_nameId" required class="form-control select2 @error('course_name') is-invalid error-data-input @enderror" value="{{ old('course_name') }}">
                                @if(!empty($course))
                                @foreach($course as $cours)
                                    @if($model->course_id == $cours->id)
                                        <option value="{{$cours->id}}" selected > {{$cours->course_name}} </option>
                                    @endif
                                    <option value="{{$cours->id}}">{{$cours->course_name}}</option>
                                @endforeach
                                @endif
                              </select>

                               <span class="error-data">@error('course_name'){{$message}}@enderror</span>
                            </div>

                    <div class="card-footer">
                        <a href="{{route('student.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
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
          $("#birth_dateId").inputmask("9999-99-99",{ "placeholder": "yyyy-mm-dd" });

        });
    </script>
@endsection

