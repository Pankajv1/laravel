<!DOCTYPE html>
<html lang="en">
<head>
<style>
span{
	color:red;
}
</style>
<meta charset="UTF-8">
<title>Laravel 8 AJAX CRUD using DataTable js Tutorial From Scratch - Tutsmake.com</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
</head>
<body>
<div class="container mt-2">
<div class="row">
<div class="col-lg-12 margin-tb">
<div class="pull-left">
<h2>Laravel 8 Ajax CRUD DataTables Crud</h2>
</div>
<div class="pull-right mb-2">
<a class="btn btn-success" onClick="add()" href="javascript:void(0)"> Create Company</a>
</div>
</div>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
<p>{{ $message }}</p>
</div>
@endif
<div class="card-body">
<table class="table table-bordered" id="ajax-crud-datatable">
<thead>
<tr>
<th>Id</th>
<th>Product Name</th>
<th>Image</th>
<th>Price</th>
<th>Min Qauntity</th>
<th>Max Quantity</th>
<th>Description</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
</table>
</div>
</div>
<!-- boostrap company model -->
<div class="modal fade" id="company-modal" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title" id="CompanyModal"></h4>
</div>
<div class="modal-body">
<form action="javascript:void(0)" id="CompanyForm" name="CompanyForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
<input type="hidden" name="id" id="id">
<input type="hidden" name="image_hidden" id="image_hidden">
<div class="form-group">
<label for="name" class="col-sm-2 control-label">Product Name<span color="red">*</span></label>
<div class="col-sm-12">
<input type="text" class="form-control" id="name" name="name" placeholder="Enter Company Name" maxlength="50" >
</div>
</div>  
<div class="form-group">
<label for="name" class="col-sm-2 control-label">Image<span color="red">*</span></label>
<div class="col-sm-12">
<input type="file" class="form-control" id="image" name="image" accept="image/*">
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">Price<span color="red">*</span></label>
<div class="col-sm-12">
<input type="number" class="form-control" id="price" name="price" placeholder="Enter Product Price">
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">Product Min Quantity</label>
<div class="col-sm-12">
<input type="number" class="form-control" id="min_quan" name="min_quan" placeholder="Enter Product Qunatity" >
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">Product Max Qauntity</label>
<div class="col-sm-12">
<input type="number" class="form-control" id="max_quan" name="max_quan" placeholder="Enter Product Qunatity">
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">Description<span color="red">*</span></label>
<div class="col-sm-12">
<input type="text" class="form-control" id="description" name="description" placeholder="Product Description" >
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">Status<span color="red">*</span></label>
<div class="col-sm-12">
 <select id="status" name="status" class="form-control" >
   <option value=""> Select Status </option>
   <option value="Active"> Active</option>
   <option value="DeActive">DeActive </option>
 </select>
</div>
</div>
<div class="col-sm-offset-2 col-sm-10">
<button type="submit" class="btn btn-primary" id="btn-save">Save changes
</button>
</div>
</form>
</div>
<div class="modal-footer">
</div>
</div>
</div>
</div>
<!-- end bootstrap model -->
</body>
<script type="text/javascript">
$(document).ready( function () {
$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
$('#ajax-crud-datatable').DataTable({
processing: true,
serverSide: true,
ajax: "{{ url('ajax-crud-datatable') }}",
columns: [
{ data: 'id', name: 'id' },
{ data: 'name', name: 'name' },

{
    "data": "image",
    "render": function(data, type, row) {
        return '<img src="images/product/'+data+'" height="100" width="100" />';
    }
},
{ data: 'price', name: 'price' },
{ data: 'min_quan', name: 'min_quan' },
{ data: 'max_quan', name: 'max_quan' },
{ data: 'description', name: 'description' },
{ data: 'status', name: 'status' },

{data: 'action', name: 'action', orderable: true},
],
order: [[0, 'desc']]
});
});
function add(){
$('#CompanyForm').trigger("reset");
$('#CompanyModal').html("Add Company");
$('#company-modal').modal('show');
$('#id').val('');
}   
function editFunc(id){
$.ajax({
type:"POST",
url: "{{ url('edit-company') }}",
data: { id: id },
dataType: 'json',
success: function(res){
$('#CompanyModal').html("Edit Company");
$('#company-modal').modal('show');
$('#id').val(res.id);
$('#name').val(res.name);
$('#price').val(res.price);
$('#min_quan').val(res.min_quan);
$('#max_quan').val(res.max_quan);
$('#description').val(res.description);
$('#image_hidden').val(res.image);
$('#status').val(res.status);

}
});
}  
function deleteFunc(id){
if (confirm("Delete Record?") == true) {
var id = id;
// ajax
$.ajax({
type:"POST",
url: "{{ url('delete-company') }}",
data: { id: id },
dataType: 'json',
success: function(res){
var oTable = $('#ajax-crud-datatable').dataTable();
oTable.fnDraw(false);
}
});
}
}
$('#CompanyForm').submit(function(e) {
var name = $('#name').val(); 
var price = $('#price').val();
var description = $('#description').val();
var status = $('#status').val();
var id = $('#id').val();
// if(name =="")
// {
	// alert('Name Is required');
	// return false;
// }
// else if(id == "")
// {
	// if( document.getElementById("image").files.length == 0 ){
    // alert('Please Upload file');
	// return false;
  // }
// }
// else if(price =="")
// {
	// alert('Price Is required');
	// return false;
// }
// else if(description =="")
// {
	// alert('Description Is required');
	// return false;
// }
// else if(status =="")
// {
	// alert('Status Is required');
	// return false;
// }
e.preventDefault();
var formData = new FormData(this);
$.ajax({
type:'POST',
url: "{{ url('store-company')}}",
data: formData,
cache:false,
contentType: false,
processData: false,
dataType:'json',
success: (data) => {
	if (data.status == 400) {
                        alert('Please Enter Mandatory field');
                    }
					else{
						$("#company-modal").modal('hide');
var oTable = $('#ajax-crud-datatable').dataTable();
oTable.fnDraw(false);
$("#btn-save").html('Submit');
$("#btn-save"). attr("disabled", false);
					}

},
error: function(data){
console.log(data);
}
});
});
</script>
</html>