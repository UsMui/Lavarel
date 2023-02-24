<div class="fixed-bottom col-4" style="right: 10px;left: auto">
@if(session()->has("error"))
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
    {{session("error")}}
</div>
@endif
@if(session()->has("success"))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h5><i class="icon fas fa-check"></i> Alert!</h5>
    {{session("success")}}

</div>
@endif
</div>

