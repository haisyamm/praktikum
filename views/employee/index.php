<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>



        <title>PHP CRUD Operations with JSON File</title>
    </head>
    <body>

        <div class="container">
            <h1 class="mt-4 mb-4 text-center text-danger">JSON CRUD operations in PHP | Load Data in jQuery DataTables</h1>
            <span id="message"></span>
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col col-sm-9">Employee</div>
                        <div class="col col-sm-3">
                            <button type="button" id="add_data" class="btn btn-success btn-sm float-end">Add</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="sample_data">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Specialist</th>
                                    <th>Age</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" id="action_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" id="sample_form">
                        <div class="modal-header">
                            <h5 class="modal-title" id="dynamic_modal_title"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control" />
                                <span id="name_error" class="text-danger"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email  " name="email" id="email" class="form-control" />
                                <span id="email_error" class="text-danger"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Specialist</label>
                                    <input type="text" name="designation" id="designation" class="form-control" />
                                    <span id="designation_error" class="text-danger"></span>
                                </div>
                            <div class="mb-3">
                                <label class="form-label">Age</label>
                                <input type="number" name="age" id="age" class="form-control" />
                                <span id="age_error" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id" id="id" />
                            <input type="hidden" name="action" id="action" value="Add" />
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="action_button">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                showAll();
                $('#add_data').click(function(){

                $('#dynamic_modal_title').text('Add Data');

                $('#sample_form')[0].reset();

                $('#action').val('Add');

                $('#action_button').text('Add');

                $('.text-danger').text('');

                $('#action_modal').modal('show');

                });

                $('#sample_form').on('submit', function(event){

                    event.preventDefault();
                    var formData = JSON.stringify(jQuery('#sample_form').serializeArray());
                    $.ajax({
                        url:"http://localhost/api_praktikum/api/employee/create.php",
                        method:"POST",
                        data: {datas},
                        dataType:"JSON",
                        beforeSend:function()
                        {
                            $('#action_button').attr('disabled', 'disabled');
                        },
                        success:function(data)
                        {
                            $('#action_button').attr('disabled', false);
                            if(data.error)
                            {
                                if(data.error.name_error)
                                {
                                    $('#name_error').text(data.error.name_error);
                                }
                                if(data.error.email_error)
                                {
                                    $('#email_error').text(data.error.email_error);
                                }
                                if(data.error.age_error)
                                {
                                    $('#age_error').text(data.error.age_error);
                                }
                            }
                            else
                            {
                                $('#message').html('<div class="alert alert-success">'+data.success+'</div>');

                                $('#action_modal').modal('hide');

                                $('#sample_data').DataTable().destroy();

                                showAll();

                                setTimeout(function(){
                                    $('#message').html('');
                                }, 5000);
                            }
                        }
                    });
                });
            });
            function showAll() {
                $.ajax({
                        type: "GET",
                        contentType: "application/json",
                        url: "http://localhost/api_praktikum/api/employee/read.php",
                        success: function(response) { 
                            // console.log(response);
                            var json = response.body;
                            
                            var dataSet=[];
                            for (var i = 0; i < json.length; i++) {
                                var sub_array = {
                                    'name' : json[i].name,
                                    'email' : json[i].email,
                                    'designation' : json[i].designation,
                                    'age' : json[i].age,
                                    'action' : '<button onclick="showOne('+json[i].id+')" class="btn btn-sm btn-warning">Edit</button>'  
                                };
                                dataSet.push(sub_array);
                            }
                            $('#sample_data').DataTable({
                                data: dataSet,
                                columns : [
                                    { data : "name" },
                                    { data : "email" },
                                    { data : "designation" },
                                    { data : "age" },
                                    { data : "action" }
                                ]
                            });
                        },
                        error: function(err) {
                            console.log(err);
                        }
                });
            } 
            function showOne(id) {
                $.ajax({
                        type: "GET",
                        contentType: "application/json",
                        url: "http://localhost/api_praktikum/api/employee/read.php?id"+id,
                        success: function(response) { 
                            console.log(response);
                            var json = response.body;
                            
                        },
                        error: function(err) {
                            console.log(err);
                        }
                });
            }
        </script>
    </body>
</html>
