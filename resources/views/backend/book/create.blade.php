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
                            <li class="breadcrumb-item"><a href="{{route('book.index')}}">Kitoblar</a></li>
                            <li class="breadcrumb-item active">{{__('msg.Create')}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="" id="book_success" role="alert"></div>
    <div class="" id="book_error" role="alert"></div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    

    <form id="book_form" action="{{route('book.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">

                   
                    <div class="card-body">

                        <div class="form-group">
                            <label for="titleId">{{__('msg.Name')}}</label>
                            <input type="text" name="title" id="titleId" class="form-control @error('title') error-data-input is-invalid @enderror" value="{{ old('title') }}" required>
                            <span class="error-data">@error('title'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="authorId">Muallifi</label>
                            <input type="text" name="author" id="authorId" class="form-control @error('author') error-data-input is-invalid @enderror" value="{{ old('author') }}" required>
                            <span class="error-data">@error('author'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="publisherId">Nashriyot</label>
                            <input type="text" name="publisher" id="publisherId" class="form-control @error('publisher') error-data-input is-invalid @enderror" value="{{ old('publisher') }}" required>
                            <span class="error-data">@error('publisher'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="category_id">kategoriyasi</label>
                                  <select name="category" id="category_id" class="form-control" required>
                                    @if(!empty($models))
                                        <option value="">------------</option>
                                        @foreach($models as $model)
                                            <option value="{{$model->id}}">{{$model->name}}</option>
                                        @endforeach
                                    @endif
                            </select>
                            <span class="error-data">@error('category'){{$message}}@enderror</span>
                        </div>
                        {{-- <div class="form-group">
                            <label for="book_codeId">Kitobning kodi(faqat son kiritiladi)</label>
                            <input type="text" name="book_code" id="book_codeId" class="form-control @error('book_code') error-data-input is-invalid @enderror" value="{{ old('book_code') }}" maxlength="9">
                            <span class="error-data">@error('book_code'){{$message}}@enderror</span>
                        </div> --}}
                        <div class="form-group">
                            <label for="book_countId">Soni(faqat son kiritiladi)</label>
                            <input type="text" name="book_count" id="book_countId" class="form-control @error('book_count') error-data-input is-invalid @enderror" value="{{ old('book_count') }}" required maxlength="9">
                            <span class="error-data">@error('book_count'){{$message}}@enderror</span>
                        </div>

                        <div class="card-footer">
                            <a href="{{route('book.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
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
        //   $('#book_yearId').inputmask('format':'dd/mm/yyyy');
          $("#book_yearId").inputmask("9999-9999",{ "placeholder": "yyyy-yyyy" });

            $('form #create_and_continue').on('click',function(e){
                // alert("sadas");
                $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();
                let category_id = $("#category_id").val();
                let title = $("#titleId").val();
                let author = $("#authorId").val();
                let publisher = $("#publisherId").val();
                let book_code = $("#book_codeId").val();
                let book_count = $("#book_countId").val();

                $.ajax({
                    type: "POST", 
                    dataType: "json",
                    url: "/admin/book/store", 
                    data: {
                        category:category_id,
                        title:title,
                        author:author,
                        publisher:publisher,
                        book_code:book_code,
                        book_count:book_count,
                    },
                    success: function(data) {
                        $("#book_form")[0].reset();
                        $(`input`).removeClass('form-input-item');
                        $(`input`).removeClass('form-input-item');
                        $('#book_error').removeClass('alert alert-danger');
                        $('#book_error').text("");
                        $('#book_success').removeClass('alert alert-success');
                        $('#book_success').text("");
                        $('#book_success').addClass('alert alert-success');
                        $('#book_success').append(data.message);
                        // console.log("success");
                    },
                    error: function(e){
                        // console.log(e);
                        // console.log('xatolik');

                        $('#book_success').removeClass('alert alert-success');
                        $('#book_success').text("");
                        
                        $('#book_error').removeClass('alert alert-danger');
                        $('#book_error').text("");
                        
                        $.each(e.responseJSON, function (key, value) {
                            $('#book_error').addClass('alert alert-danger');
                            
                            Object.entries(value).forEach(([key, val]) => {
                                $(`input[name=${key}]`).addClass('form-input-item');
                                $(`select[name=${key}]`).addClass('form-select-item');
                                $('#book_error').append(` ${key} => ${val} `);
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




