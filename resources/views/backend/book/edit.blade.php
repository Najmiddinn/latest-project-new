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
                            <li class="breadcrumb-item"><a href="{{route('book.index')}}">Kitob</a></li>
                            <li class="breadcrumb-item active">{{__('msg.Update')}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{route('book.update',$model->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">

                       
                        <div class="form-group">
                            <label for="titleId">{{__('msg.Name')}}</label>
                            <input type="text" name="title" id="titleId" class="form-control @error('title') error-data-input is-invalid @enderror" value="{{$model->title, old('title') }}" required>
                            <span class="error-data">@error('title'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="authorId">Muallifi</label>
                            <input type="text" name="author" id="authorId" class="form-control @error('author') error-data-input is-invalid @enderror" value="{{ $model->author,old('author') }}" required>
                            <span class="error-data">@error('author'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="publisherId">Nashriyot</label>
                            <input type="text" name="publisher" id="publisherId" class="form-control @error('publisher') error-data-input is-invalid @enderror" value="{{ $model->publisher,old('publisher') }}" required>
                            <span class="error-data">@error('publisher'){{$message}}@enderror</span>
                        </div>
                        <div class="form-group">
                            <label for="category_id">{{__('msg.Category')}}</label>
                              <select style="width: 100%;" name="category" id="category_id" required class="form-control select2 @error('category') is-invalid error-data-input @enderror" >
                                @if(!empty($category))
                                <option value="">--------------------</option>
                                    @foreach($category as $ct)
                                        @if($model->category == $ct->id)
                                            <option value="{{$ct->id}}" selected > {{$ct->name}} </option>
                                        @endif
                                        <option value="{{$ct->id}}">{{$ct->name}}</option>
                                    @endforeach
                                @endif
                              </select>

                            <span class="error-data">@error('category'){{$message}}@enderror</span>
                        </div>

                        
                        
                        {{-- <div class="form-group">
                            <label for="book_codeId">Kitobning kodi</label>
                            <input type="text" name="book_code" id="book_codeId" class="form-control @error('book_code') error-data-input is-invalid @enderror" value="{{ $model->book_code,old('book_code') }}" >
                            <span class="error-data">@error('book_code'){{$message}}@enderror</span>
                        </div> --}}
                        <div class="form-group">
                            <label for="book_countId">Soni</label>
                            <input type="text" name="book_count" id="book_countId" class="form-control @error('book_count') error-data-input is-invalid @enderror" value="{{ $model->book_count,old('book_count') }}" required>
                            <span class="error-data">@error('book_count'){{$message}}@enderror</span>
                        </div>
                       

                    <div class="card-footer">
                        <a href="{{route('book.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
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
        //   $('#book_yearId').inputmask('format':'dd/mm/yyyy');
          $("#book_yearId").inputmask("9999-9999",{ "placeholder": "yyyy-yyyy" });

        });
    </script>
@endsection

