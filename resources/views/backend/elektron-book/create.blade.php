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
                            <li class="breadcrumb-item"><a href="{{route('elektron-book.index')}}">Elektron kitoblar</a></li>
                            <li class="breadcrumb-item active">{{__('msg.Create')}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('elektron-book.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="book">Fayl</label>
                            {{-- <input type="file" name="book[]" multiple id="book" class="form-control @error('book') error-data-input is-invalid @enderror" required> --}}
                            <input type="file" name="book" id="book" class="form-control @error('book') error-data-input is-invalid @enderror" required>
                            <span class="error-data">@error('book'){{$message}}@enderror</span>
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
                        <div class="card-footer">
                            <a href="{{route('elektron-book.index')}}" class="btn btn-danger">{{__('msg.Cancel')}}</a>
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




