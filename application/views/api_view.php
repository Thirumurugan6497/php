<html>
<head>
    <title>CURD REST API in Codeigniter</title>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    
</head>
<body>
    <div class="container">
        <br />
        <h3 align="center">Create CRUD REST API in Codeigniter - 4</h3>
        <br />
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="panel-title">CRUD REST API in Codeigniter</h3>
                    </div>
                    <div class="col-md-6" align="right">
                        <button type="button" id="add_button" class="btn btn-info btn-xs">Add</button>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <span id="success_message"></span>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Password</th>
                            <th style="width:8%;">Profile</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<div id="userModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="user_form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add User</h4>
                </div>
                <div class="modal-body">
                    <label>Enter First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" />
                    <span id="first_name_error" class="text-danger"></span>
                    <br />
                    <label>Enter Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" />
                    <span id="last_name_error" class="text-danger"></span>
                    <br />
                    <label>Enter Email</label>
                    <input type="text" name="email" id="email" class="form-control" />
                    <span id="email_error" class="text-danger"></span>
                    <br />
                    <label>Enter Phone Number</label>
                    <input type="text" maxlength="13" name="phone" id="phone" class="form-control" />
                    <span id="phone_error" class="text-danger"></span>
                    <br />
                    <label>Enter Password</label>
                    <input type="password" name="password" id="password" class="form-control" />
                    <span id="password_error" class="text-danger"></span>
                    <br />
                    <label>Profile</label>
                    <input type="file" id="image" name="image" size="33" class="form-control" />
                    <!-- <input type="text" name="image" id="image" class="form-control" /> -->
                   
                    
                    <br />
                    <input type="hidden" id="hidden" name="hidden">
                    <div id="demo">
 
                    </div>
                    <span id="image_error" class="text-danger"></span>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_id" id="user_id" />
                    <input type="hidden" name="data_action" id="data_action" value="Insert" />
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" language="javascript" >
$(document).ready(function(){
    
    function fetch_data()
    {
        $.ajax({
            url:"<?php echo base_url(); ?>test_api/action",
            method:"POST",
            data:{data_action:'fetch_all'},
            success:function(data)
            {
                //alert(data);return false;
                $('tbody').html(data);
            }
        });
    }

    fetch_data();

    $('#add_button').click(function(){
        $('#user_form')[0].reset();
        $('.modal-title').text("Add User");
        $('#action').val('Add');
        $('#data_action').val("Insert");
        $('#demo').html("");
        $('#userModal').modal('show');
    });

    $(document).on('submit', '#user_form', function(event){
        event.preventDefault();
        //alert($("#image").val());
        var phone = $("#phone").val();
       if(phone.indexOf("+91") != 0)
       {
         if(phone.trim() != "+91"+phone)
         {
            //alert(phone);return false;
             $('#phone_error').html("Please add +91 infront of this number");return false;
             
         }
        }
         

            $.ajax({
                url:"<?php echo base_url() . 'test_api/action' ?>",
                method:"POST",
                data:new FormData(this),
                 processData:false,
                 contentType:false,
                 cache:false,
                 async:false,
                dataType:"json",
                success:function(data)
                {
                    //alert(data.success);return false;
                    if(data.success == 'true')
                    {
                        //alert("ad");return false;
                        $('#user_form')[0].reset();
                        $('#userModal').modal('hide');
                        fetch_data();
                        /*if($('#data_action').val() == "Insert")
                        {*/

                            $('#success_message').html('<div class="alert alert-success">'+data.message+'</div>');
                        //}
                    }

                    if(data.success == 'false')
                    {
                        //alert(data.data.email_error);return false;
                        $('#first_name_error').html(data.data.first_name_error);
                        $('#last_name_error').html(data.data.last_name_error);
                        $('#email_error').html(data.data.email_error);
                        $('#phone_error').html(data.data.phone_error);
                        $('#password_error').html(data.data.password_error);
                        $('#image_error').html(data.data.image_error);
                    }
                }
            });
    });

    $(document).on('click', '.edit', function(){
        var user_id = $(this).attr('id');
        $.ajax({
            url:"<?php echo base_url(); ?>test_api/action",
            method:"POST",
            data:{user_id:user_id, data_action:'fetch_single'},
            dataType:"json",
            success:function(data)
            {
                //alert(data.first_name);return false;
                $('#userModal').modal('show');
                $('#first_name').val(data.first_name);
                $('#last_name').val(data.last_name);
                $('#email').val(data.email);
                $('#phone').val(data.phone);
                $('#password').val(data.password);
                $('#demo').html("<img id='eimage' src='http://localhost/codeigniter/images/"+ data.image+"'>");
                $("#hidden").val(data.image);

                $('.modal-title').text('Edit User');
                $('#user_id').val(user_id);
                $('#action').val('Edit');
                $('#data_action').val('Edit');
            }
        })
    });

    $(document).on('click', '.delete', function(){
        var user_id = $(this).attr('id');
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"<?php echo base_url(); ?>test_api/action",
                method:"POST",
                data:{user_id:user_id, data_action:'Delete'},
                dataType:"JSON",
                success:function(data)
                {
                    if(data.success)
                    {
                        $('#success_message').html('<div class="alert alert-success">'+data.message+'</div>');
                        fetch_data();
                    }
                }
            })
        }
    });
    $(document).on('keyup','#first_name',function(e)
    {
       if (this.value.match(/[^a-zA-Z0-9 ]/g)) {
        this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');
        }
    });
    $(document).on('keyup','#last_name',function(e)
    {
       if (this.value.match(/[^a-zA-Z0-9 ]/g)) {
        this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');
        }
    });
    $(document).on('change',"#image",function()
    {
        //alert("sdf");return false;
        var fileInput =  document.getElementById('image');             
        var filePath = fileInput.value; 
        //alert(filePath);return false;
      
        // Allowing file type 
        var allowedExtensions =  
                /(\.jpg|\.jpeg|\.png)$/i; 
          
        if (!allowedExtensions.exec(filePath)) { 
            $("#image_error").html('invalid file type'); 
            fileInput.value = ''; 
            return false; 
        }  
        else
        {
            return true;
        }
    });
    
    
});
</script>

<style>
#demo
{
    width:20%;
}
#eimage
{
    width:100%;
}
</style>
 