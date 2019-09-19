<!DOCTYPE html>

<html>

<head>

    <title>Laravel 5.8 Technical Test</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />

    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

</head>

    

<div class="container">

    <h1>Laravel 5.8 Technical Test - Redtix</h1>

    <a class="btn btn-success" href="javascript:void(0)" id="createNewuser"> Create New User</a>

    <hr />

<div class="col-lg-5">
  <label> Email </label>
   <input type="text" name="email" class="form-control searchEmail" placeholder="Search for Email Only...">
</div> 

<br />

<hr />

    <table class="table table-bordered data-table">

        <thead>

            <tr>

                <th>No</th>

                <th>Firstname</th>

                <th>Lastname</th>

                <th>Email</th>

                <th>Status</th>

                <th width="280px">Action</th>

            </tr>

        </thead>

        <tbody>

        </tbody>

    </table>

</div>

   

<div class="modal fade" id="ajaxModel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title" id="modelHeading"></h4>

            </div>

            <div class="modal-body">

                <form id="userForm" name="userForm" class="form-horizontal">

                   <input type="hidden" name="user_id" id="user_id">

                  

                    <div class="form-group">

                        <label for="first_name" class="col-sm-2 control-label">First Name</label>

                        <div class="col-sm-12">

                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" value="" maxlength="50" required="">

                        </div>

                    </div>

                     <div class="form-group">

                        <label for="last_name" class="col-sm-2 control-label">Last Name</label>

                        <div class="col-sm-12">

                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" value="" maxlength="50" required="">

                        </div>

                    </div>


                     <div class="form-group">

                        <label for="email" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-12">

                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="" maxlength="50" required="">

                        </div>

                    </div>

                     <div class="form-group">

                        <label for="status" class="col-sm-2 control-label">Status</label>

                        <div class="col-sm-12">

                           

                            <select name="status" class="form-control">
    <option value="">Select Status</option>
    <option value="active">Active</option>
    <option value="inactive">InActive</option>
</select>

                        </div>

                    </div>


                    <div class="col-sm-offset-2 col-sm-10">

                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes

                     </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>




    

</body>

    

<script type="text/javascript">

  $(function () {

     

      $.ajaxSetup({

          headers: {

              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

          }

    });

    

    var table = $('.data-table').DataTable({


        processing: true,

        serverSide: true,
 
        searching: false,
       

                ajax: {

          url: "{{ route('ajaxusers.index') }}",

          data: function (d) {

                d.email = $('.searchEmail').val(),


                d.search = $('input[type="search"]').val()

            }

        },

        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},

            {data: 'first_name', name: 'First Name'},
            {data: 'last_name', name: 'Last Name'},
            {data: 'email', name: 'Email'},
            {data: 'status', name: 'Status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},

        ],

        

    });



         $(".searchEmail").keyup(function(){

        table.draw();

    });







    $('#createNewuser').click(function () {

        $('#saveBtn').val("create-user");

        $('#user_id').val('');

        $('#userForm').trigger("reset");

        $('#modelHeading').html("Create New User");

        $('#ajaxModel').modal('show');

    });

    

    $('body').on('click', '.edituser', function () {

      var user_id = $(this).data('id');

      $.get("{{ route('ajaxusers.index') }}" +'/' + user_id +'/edit', function (data) {

          $('#modelHeading').html("Edit user");

          $('#saveBtn').val("edit-user");

          $('#ajaxModel').modal('show');

          $('#user_id').val(data.id);

          $('#first_name').val(data.first_name);

          $('#last_name').val(data.last_name);
          
          $('#email').val(data.email);

          $('#status').val(data.status);


      })

   });

    

    $('#saveBtn').click(function (e) {

        e.preventDefault();

        $(this).html('Saving..');

    

        $.ajax({

          data: $('#userForm').serialize(),

          url: "{{ route('ajaxusers.store') }}",

          type: "POST",

          dataType: 'json',

          success: function (data) {

     

              $('#userForm').trigger("reset");

              $('#ajaxModel').modal('hide');

              table.draw();

         

          },

          error: function (data) {

              console.log('Error:', data);

              $('#saveBtn').html('Save Changes');

          }

      });

    });

    

    $('body').on('click', '.deleteuser', function () {

     

        var user_id = $(this).data("id");

        confirm("Are You sure want to delete !");

      

        $.ajax({

            type: "DELETE",

            url: "{{ route('ajaxusers.store') }}"+'/'+user_id,

            success: function (data) {

                table.draw();

            },

            error: function (data) {

                console.log('Error:', data);

            }

        });

    });

     

  });

</script>

</html>