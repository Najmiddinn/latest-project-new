@extends('layouts.mainBackend')
@section('title') Kitoblar @endsection



@section('content')

    <div class="page-header card">
    </div>
    <div class="card">
        <div class="content-header">
            <div class="container-fluid card-block">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Kitoblar</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">{{__('msg.Home')}}</a></li>
                            <li class="breadcrumb-item active">Kitoblar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="breadcrumb-and-filter">
                <div class="row">
                    <div class="col-md-4">
                        <div class="action-content">
                            <button style="margin-bottom: 10px" class="btn btn-danger delete_all" data-url="{{ route('book.destroyMultiple') }}">Belgilangan kitoblarni o'chirish</button>
                        </div>
                    </div>
                    <div class="col-md-2" >
                        <div class="create-data" style="float: left;">
                            <a href="{{route('book.pdfReport')}}" class=" style-add btn btn-info">Mavjud kitoblar</a>
                        </div>
                    </div>
                    <div class="col-md-3" >
                        <div class="create-data" style="float: left;">
                            <a href="{{route('book.pdfReportBorowwing')}}" class=" style-add btn btn-info">Berilgan kitoblar </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="create-data" style="float: right;">
                            <a href="{{route('book.create')}}" class=" style-add btn btn-primary">{{__('msg.Create')}}</a>  
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

        <div class="card-body">
            <p>Barcha Kitoblar soni <b>{{$bookCount->sum->book_count}}</b> ta</p>
            @foreach ($bookcategories as $bookcategory)
                <li class="list-group-item"><a href="{{route('book.by-category',$bookcategory->id)}}">{{$bookcategory->name}} kategoriyasida <b>({{ $bookcategory->book->sum->book_count}})</b> ta kitob bor</a></li>
            @endforeach
            <table id="dashboard_datatable" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th><input type="checkbox" id="master"></th>
                    <th>#</th>
                    <th>{{__('msg.Name')}}</th>
                    <th>Muallifi</th>
                    <th>Nashriyot</th>
                    <th>kategoriyasi</th>
                    <th>Soni</th>
                    <th>{{__('msg.Actions')}}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($models as $key => $model)
                    <tr>
                        <td><input type="checkbox" class="sub_chk" data-id="{{$model->id}}"></td>
                        <th scope="row">{{ $models->firstItem()+$key }}</th>
                        <td>{{ $model->title }}</td>
                        <td>{{ $model->author }}</td>
                        <td>{{ $model->publisher }}</td>
                        <td>{{ $model->getCategory['name'] }}</td>
                        <td>{{ $model->book_count }}</td>
                        {{-- <td>{{ $model->book_code }}</td> --}}
                        <td>
                            <div style="text-align: center;">
                                <a href="{{route('book.edit',$model->id)}}" class="btn btn-primary" title="update">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form style="display: inline-block;" action="{{route('book.destroy',$model->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-data-item btn btn-danger" title="delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $models->links() }}
        </div>
    </div>

<style>

</style>
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

        $('#master').on('click', function(e) {
         if($(this).is(':checked',true))  
         {
            $(".sub_chk").prop('checked', true);  
         } else {  
            $(".sub_chk").prop('checked',false);  
         }  
        });

        $('.delete_all').on('click', function(e) {

            var allVals = [];  
            $(".sub_chk:checked").each(function() {  
                allVals.push($(this).attr('data-id'));
            });  

            if(allVals.length <=0)  
            {  
                alert("Please select row.");  
            }  else {  

                var check = confirm("Belgilangan qatorlarni o'chirishga ishonchingiz komilmi?");  
                if(check == true){  

                    var join_selected_values = allVals.join(","); 

                    $.ajax({
                        url: $(this).data('url'),
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: 'ids='+join_selected_values,
                        success: function (data) {
                            if (data['success']) {
                                $(".sub_chk:checked").each(function() {  
                                    $(this).parents("tr").remove();
                                });
                                alert(data['success']);
                            } else if (data['error']) {
                                alert(data['error']);
                            } else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });

                  $.each(allVals, function( index, value ) {
                      $('table tr').filter("[data-row-id='" + value + "']").remove();
                  });
                }  
            }  
        });

        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function (event, element) {
                element.trigger('confirm');
            }
        });

        $(document).on('confirm', function (e) {
            var ele = e.target;
            e.preventDefault();

            $.ajax({
                url: ele.href,
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    if (data['success']) {
                        $("#" + data['tr']).slideUp("slow");
                        alert(data['success']);
                    } else if (data['error']) {
                        alert(data['error']);
                    } else {
                        alert('Whoops Something went wrong!!');
                    }
                },
                error: function (data) {
                    alert(data.responseText);
                }
            });

            return false;
        });
    });
</script>
@endsection







