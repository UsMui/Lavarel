@extends('admin.layout')
@section("custom_js")
    <script src="/admin/plugins/select2/js/select2.full.min.js"></script>
    <script type="text/javascript">
        $('.select2').select2();
    </script>
@endsection
@section("content_header")
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Create a student</h1>
    </div><!-- /.col -->
@endsection
@section("main_content")
    <div class="row">
        <!-- left column -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Product information</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="post" action="{{url("/admin/student/create")}}" role="form" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="card-body">
                            @include("admin.html.form.input",[
                                "label"=>"Student Name",
                                "key"=>"name",
                                "type"=>"text",
                                "required"=>true
                            ])
                            @include("admin.html.form.input",[
                                "label"=>"Student Age",
                                "key"=>"age",
                                "type"=>"number",
                                "required"=>true
                            ])
                            @include("admin.html.form.input",[
                               "label"=>"Address",
                               "key"=>"address",
                               "type"=>"text",
                               "required"=>true
                           ])
                            @include("admin.html.form.input",[
                              "label"=>"Telephone",
                              "key"=>"telephone",
                              "type"=>"text",
                              "required"=>true
                          ])

                        </div>
                        <!-- /.card-body -->


                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
@endsection


