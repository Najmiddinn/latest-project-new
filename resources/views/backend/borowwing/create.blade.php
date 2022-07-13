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
                            <li class="breadcrumb-item"><a href="{{route('student.index')}}">Talaba</a></li>
                            <li class="breadcrumb-item active">{{__('msg.Create')}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="" id="student_success" role="alert"></div>
    <div class="" id="student_error" role="alert"></div>

    <form id="student_form" action="{{route('student.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="first_nameId">Familyasi</label>
                            <input type="text" name="first_name" id="first_nameId" class="form-control @error('first_name') error-data-input is-invalid @enderror" value="{{ old('first_name') }}" required>
                            <span class="error-data">@error('first_name'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="last_nameId">Ismi</label>
                            <input type="text" name="last_name" id="last_nameId" class="form-control @error('last_name') error-data-input is-invalid @enderror" value="{{ old('last_name') }}" required>
                            <span class="error-data">@error('last_name'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="birth_dateId">Tug'ilgan yili</label>
                            <input type="text" name="birth_date" id="birth_dateId" class="form-control @error('birth_date') error-data-input is-invalid @enderror" value="{{ old('birth_date') }}" required>
                            <span class="error-data">@error('birth_date'){{$message}}@enderror</span>
                        </div>

                        <div class="form-group" id="course_id_div">
                            <label for="course_id">Kurs</label>
                            <select required name="course_id" id="course_id"  data-placeholder="Select a role" class="form-control select2 @error('course_id') is-invalid error-data-input @enderror" value="{{ old('course_id') }}">
                                @if(!empty($course))
                                    <option value="">------------</option>
                                    @foreach($course as $model)
                                        <option value="{{$model->id}}">{{$model->course_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="error-data">@error('course_id'){{$message}}@enderror</span>
                        </div>

                        <div class="card-footer">
                            <a href="{{route('student.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
                            <button type="submit" class="btn btn-success">{{__('msg.Create')}}</button>
                            <button type="submit" id="create_and_continue" class="btn btn-success">{{__('msg.create and continue')}}</button>
                        </div>

                    </div>
                </div>
            </div>
         
        </div>
    </form>

    <style>
        .form-input-item{
            border:1px solid red !important;
        }
        .form-select-item{
            border: 1px solid red !important;
        }
    </style>

@endsection


@section('scripts')
    <script>
        $(document).ready(function () {
          $("#birth_dateId").inputmask("9999-99-99",{ "placeholder": "yyyy-mm-dd" });

          
          $('form #create_and_continue').on('click',function(e){
                // alert("sadas");
                $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();
                let first_name = $("#first_nameId").val();
                let last_name = $("#last_nameId").val();
                let birth_date = $("#birth_dateId").val();
                let course_id = $("#course_id").val();

                $.ajax({
                    type: "POST", 
                    dataType: "json",
                    url: "/admin/student/store", 
                    data: {
                        first_name:first_name,
                        last_name:last_name,
                        birth_date:birth_date,
                        course_id:course_id,
                    },
                    success: function(data) {
                        $("#student_form")[0].reset();
                        $(`input`).removeClass('form-input-item');
                        $('#student_error').removeClass('alert alert-danger');
                        $('#student_error').text("");
                        $('#student_success').removeClass('alert alert-success');
                        $('#student_success').text("");
                        $('#student_success').addClass('alert alert-success');
                        $('#student_success').append(data.message);
                        // console.log("success");
                    },
                    error: function(e){
                        // console.log(e);
                        // console.log('xatolik');

                        $('#student_success').removeClass('alert alert-success');
                        $('#student_success').text("");
                        
                        $('#student_error').removeClass('alert alert-danger');
                        $('#student_error').text("");
                        
                        $.each(e.responseJSON, function (key, value) {
                            $('#student_error').addClass('alert alert-danger');
                            
                            Object.entries(value).forEach(([key, val]) => {
                                $(`input[name=${key}]`).addClass('form-input-item');
                                $(`.select2`).addClass('form-select-item');
                                $('#student_error').append(` ${key} => ${val} `);
                                // console.log(key); // the name of the current key.
                                // console.log(val); // the value of the current key.
                            });
                        });

                        

                    }

                });


            });

        });
    </script>
@endsection




