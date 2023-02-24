@extends('admin.layout')
@section("content_header")
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Student</h1>
    </div><!-- /.col -->
    <div class="col-sm-6 text-right">
        <a class="btn btn-outline-primary" href="{{url("admin/student/create")}}">Create a new student</a>
    </div>
@endsection
@section("main_content")
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Bordered Table</h3>
            <div class="card-tools">

            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>Telephone</th>

                </tr>
                </thead>
                <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->age}}</td>
                        <td>{{$item->address}}</td>
                        <td>{{$item->telephone}}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            {{--            <ul class="pagination pagination-sm m-0 float-right">--}}
            {{--                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>--}}
            {{--                <li class="page-item"><a class="page-link" href="#">1</a></li>--}}
            {{--                <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
            {{--                <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
            {{--                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>--}}
            {{--            </ul>--}}
            {!! $data->appends(app("request")->input())->links("pagination::bootstrap-4") !!}
        </div>
    </div>
    <!-- /.card -->
@endsection
