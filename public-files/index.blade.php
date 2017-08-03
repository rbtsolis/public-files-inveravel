@extends('layouts.master')

@section('title')
  Students
@stop

@section('page_header')
  Students
@stop

@section('breadcrumb')
  <li><a href="/"><i class="fa fa-dashboard"></i>Dashboard</a></li>
  <li class="active">Students</li>
@stop

@section('head')
    <link rel="stylesheet" href="/datatables/css/datatables.bootstrap.min.css">
@stop

@section('content')

<div class="row">
  <div class="col-xs-12 ">

    <div class="box">
      <div class="box-header">
        <h3 class="box-title">List of the students</h3>
        <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#modal-student">
          Add Student
        </button>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table id="student-table" class="table table-striped table-hover table-responsive">
          <thead>
            <tr>
              <th>ID</th>
              <th>ID Card</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($students as $student)
              <tr role="row" class="even">
                <td>{{ $student -> id }}</td>
                <td>{{ $student -> id_card }}</td>
                <td>{{ $student -> first_name }}</td>
                <td>{{ $student -> last_name }}</td>
                <td>{{ $student -> email }}</td>
                <td>
                  <button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                  <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                </td>
                
              </tr>
            @empty
                <tr>No students</tr>
            @endforelse
          </tbody>
          <tfoot>
                <tr>
                  <th>ID</th>
                  <th>ID Card</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Actions</th>
                </tr>
          </tfoot>
        </table>
      </div>

    </div>
  </div>
</div>


  <div class="modal fade" id="modal-student">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modal-header">Add Student</h4>
        </div>
        <div class="modal-body" id="modal-body">

          <form role="form" data-toggle="validator" id="student-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="box-body">

              <div class="form-group has-feedback">
                <label for="id-card" class="control-label">ID Card</label>
                <input type="text" class="form-control" name="id_card" id="id-card" pattern="^[0-9]{9,12}$" placeholder="Enter an ID Card:" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                <div class="help-block with-errors"></div>
              </div>

              <div class="form-group has-feedback">
                <label for="first-name" class="control-label">First Name:</label>
                <input type="text" name="first_name" class="form-control" id="first-name" maxlength="50" placeholder="Enter a First Name:" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                <div class="help-block with-errors"></div>
              </div>

              <div class="form-group has-feedback">
                <label for="last-name" class="control-label">Last Name:</label>
                <input type="text" name="last_name" class="form-control" id="last-name" placeholder="Enter a Last Name:" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                <div class="help-block with-errors"></div>
              </div>

              <div class="form-group has-feedback">
                <label for="email" class="control-label">Email:</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Enter a Email:" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                <div class="help-block with-errors"></div>
              </div>

            </div>
            <!-- /.box-body -->
          </form>

        </div>
        <div class="modal-footer">
          <div class="progress-form normal-hidden" id="progress-form">
            <div class="progress progress-sm active">
              <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
              </div>          
            </div>
            <p class="text-center">Enviando...</p>
          </div>
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="update-create-student">Save</button>
          <p></p>
          <div id="notifications"></div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>


@section('scripts')

<script src="/datatables/js/jquery.datatables.min.js"></script>
<script src="/datatables/js/datatables.bootstrap.min.js"></script>

<script>
$(document).ready(function(){
  $('#modal-student').on('hidden.bs.modal', function () {
    $("#student-form")[0].reset()
  });
  $("#update-create-student").click(validate_form);
  $('#student-table').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      "order": [[ 3, "asc" ]]  });
});

function validate_form() {
  //$("#student-form").validator('validate');
  
  if($('#student-form').validator('validate').has('.has-error').length === 0) {
    var data = new FormData($('#student-form').get(0));
    $.ajax({
      url: '/students/',
      type: "POST",
      data: data,
      cache: false,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $("#progress-form").fadeIn();
      },
      success: function(response)
      {
        response = JSON.parse(response);

        add_student_row(response.id, response.id_card, response.first_name, response.last_name, response.email);

        set_notification('success', 'check', 
          'Succesful!', 'Student added succesful!'
        );
        $("#progress-form").fadeOut();
        Delay(hide_modal, 1200);
      },
      error: function(){
        $("#progress-form").fadeOut();        
      }
    });
  }

}


function delete_student() {

}


function hide_modal() {
  $('#modal-student').modal('toggle');
}

function add_student_row(id, id_card, first_name, last_name, email) {

  var student_row = ' \
    <tr role="row" class="even"> \
      <td>{0}</td> \
      <td>{1}</td> \
      <td>{2}</td> \
      <td>{3}</td> \
      <td>{4}</td> \
    </tr>'.format(id, id_card, first_name, last_name, email);

  $("#student-table tbody").append(student_row);
};

function set_notification(type, icon, header, text) {
  var notification = 
    '<div class="alert alert-{0} alert-dismissible"> \
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> \
      <h4><i class="icon fa fa-{1}"></i>{2}</h4> \
      {3} \
    </div>'.format(type, icon, header, text);
    $("#notifications").html(notification);
}

</script>
@stop


@stop

