@extends('layouts.mainBackend')

@section('title') O'qituvchilar @endsection

@section('content')

    <div class="page-header card">
    </div>
    <div class="card">
        <div class="content-header">
            <div class="container-fluid card-block">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{__('msg.Menu')}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">{{__('msg.Home')}}</a></li>
                            <li class="breadcrumb-item active">O'qituvchilar</li>
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
                    <div class="col-md-9">
                        @role('super admin')
                        <div class="action-content">
                            <button style="margin-bottom: 10px" class="btn btn-danger delete_all" data-url="{{ route('student.destroyMultiple') }}">Belgilangan qatorlarni o'chirish</button>
                        </div>
                        @endrole
                    </div>
                    <div class="col-md-3">
                        <div class="create-data" style="float: right;">
                            @role('super admin')
                                <a href="{{route('student.create')}}" class=" style-add btn btn-primary">{{__('msg.Create')}}</a>
                            @endrole
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
            <p>Barcha O'qituvchilar soni <b>{{$studentCount}}</b> ta</p>
            <table id="dashboard_datatable" class="table table-bordered table-hover">
                <thead>
                <tr>
                    @role('super admin')
                    <th><input type="checkbox" id="master"></th>
                    @endrole
                    <th>#</th>
                    <th>Familyasi</th>
                    <th>Ismi</th>
                    <th>Tug'ilgan sanasi</th>
                    <th>Kategoriyasi</th>
                    <th>{{__('msg.Actions')}}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($models as $key => $model)
                    <tr>
                        @role('super admin')
                        <td><input type="checkbox" class="sub_chk" data-id="{{$model->id}}"></td>
                        @endrole
                        <th scope="row">{{ $models->firstItem()+$key }}</th>
                        <td>{{ $model->first_name }}</td>
                        <td>{{ $model->last_name }}</td>
                        <td>{{ $model->birth_date }}</td>
                        <td>{{ $model->getCourse->course_name }}</td>
                        <td>
                            <div style="text-align: center;">
                               
                                <a href="{{route('student.show',$model->id)}}" class="btn btn-primary" title="show">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @role('super admin')
                                    <a href="{{route('student.edit',$model->id)}}" class="btn btn-primary" title="update">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    
                                    <form style="display: inline-block;" action="{{route('student.destroy',$model->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-data-item btn btn-danger" title="delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endrole
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







