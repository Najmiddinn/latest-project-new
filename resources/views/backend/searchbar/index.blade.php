@extends('layouts.mainBackend')
@section('title') Izlash @endsection


@section('content')

    <div class="page-header card">
    </div>
    <div class="card">
        <div class="content-header">
            <div class="container-fluid card-block">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Izlash</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">{{__('msg.Home')}}</a></li>
                            <li class="breadcrumb-item active">Izlash</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>


        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif
            
        <form id="student_form" action="{{route('searchbar.search')}}" method="GET" enctype="multipart/form-data">
            {{-- @csrf --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
    
                            <div class="form-group">
                                <label for="search_key_id">kalit so'z</label>
                                <input type="text" name="search_key" id="search_key_id" class="form-control @error('search_key') error-data-input is-invalid @enderror" value="{{ old('search_key') }}" required>
                                <span class="error-data">@error('search_key'){{$message}}@enderror</span>
                            </div>
                            
    
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">izlash</button>
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
        $("#dashboard_datatable").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "paging": false
            
        });

        

    });
</script>
@endsection







