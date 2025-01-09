<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Service Offers';
include('header.php');

include('sidebar.php');

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0"><?php echo $title; ?></h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if ($_SESSION["user_type"] !== 'Superadmin') { ?>
                    <div class="col-12 col-md-5">
                        <div class="card card-dark elevation-2">
                            <form method="post" id="forms_profile">
                                <div class="card-header bg-dark ">
                                    <div class="card-title text-lg text-bold">Personal Information</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-12 col-md-4 ">
                                            <input type="text" name="last_name" id="last_name" class="form-control " placeholder="Last Name" disabled />
                                        </div>
                                        <div class="form-group col-12 col-md-4 ">
                                            <input type="text" name="first_name" id="first_name" class="form-control " placeholder="First Name" disabled />
                                        </div>
                                        <div class="form-group col-12 col-md-4 ">
                                            <input type="text" name="middle_name" id="middle_name" class="form-control " placeholder="Middle Name" disabled />
                                        </div>
                                        <div class="form-group col-12 col-md-8">
                                            <input type="email" name="email" id="email" class="form-control " required />
                                        </div>
                                        <div class="form-group col-12 col-md-4">
                                            <input type="number" min="0" name="contact" id="contact" class="form-control " placeholder="Contact" required/>
                                        </div>
                                        <div class="form-group col-12 col-md-12">
                                            <textarea name="address" id="address_profile" class="form-control " placeholder="Address" required ></textarea>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <input type="password" name="password_profile" id="password_profile" class="form-control " placeholder="Password" required />
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <input type="password" name="retype_profile" id="retype_profile" class="form-control " placeholder="Retype Password" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <input type="hidden" name="btn_action" id="btn_action" value="profile_edit"/>
                                    <button type="submit" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="action" id="action" >Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } else {?>
                <div class="col-12">
                    <div class="card card-dark elevation-2">
                        <div class="card-header text-right">
                                <button type="button" name="add" id="add_button" data-toggle="modal" data-target="#addModal" class="btn btn-default elevation-2 text-bold pl-3 pr-3" >
                                    Add</button>
                        </div>
                        <div class="card-body">
                            <table id="datatables" class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Date Uploaded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

</div>

<?php

include('footer.php');

?>

<?php if ($_SESSION["user_type"] == 'Superadmin') { ?>
    <div id="addModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
        <div class="modal-dialog">
            <form method="post" id="forms">
                <div class="modal-content" >
                    <div class="modal-header bg-dark">
                        <h4 class="modal-title"><i class="fa fa-plus-circle"></i></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-12 ">
                                <label class="details"></label>
                                <div class="text-center">
                                    <div class="image-upload " >
                                        <label for="files">
                                            <img class="img-thumbnail files" src="assets/image-placeholder.png" 
                                                style="cursor:pointer; width: 200px; height: 200px;"/>
                                        </label>
                                        <input type="file" accept=".png, .jpg, .jpeg" onchange="readURL(this);" name="files" id="files"  />
                                    </div>
                                    <i>Click image to upload</i>
                                </div> 
                            </div>
                            <div class="form-group col-12 col-md-12">
                                <label>Title</label>
                                <input type="text" name="title" id="title" class="form-control"  />
                            </div>
                            <div class="form-group col-12 col-md-12">
                                <label>Description</label>
                                <textarea name="description" id="description" class="form-control" ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" id="id"/>
                        <input type="hidden" name="btn_action" id="btn_action"/>
                        <button type="submit" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="action" id="action" >Save</button>
                        <button type="button" class="btn btn-default text-bold pl-3 pr-3 elevation-2" data-dismiss="modal" >Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } ?>
  
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('.files').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(function () {
        <?php if ($_SESSION["user_type"] !== 'Superadmin') { ?>
            loadProfile();
            function loadProfile()
            {
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data: {  btn_action: 'profile_load' },
                    dataType:"json",
                    success:function(data)
                    {
                        $('#email').val(data.email);
                        $('#last_name').val(data.last_name);
                        $('#first_name').val(data.first_name);
                        $('#middle_name').val(data.middle_name);
                        $('#contact').val(data.contact);
                        $('#address_profile').val(data.address);
                    },error:function()
                    {
                        toastr.error('Please try again.');
                    }
                })
            }

            $(document).on('submit','#forms_profile', function(event){
                event.preventDefault();
                if ($('#password_profile').val() == $('#retype_profile').val())
                {
                    $('#action').attr('disabled','disabled');
                    var form_data = $(this).serialize();
                    $.ajax({
                        url:"action.php",
                        method:"POST",
                        data:form_data,
                        dataType:"json",
                        success:function(data)
                        {
                            $('#action').attr('disabled', false);
                            if (data.status == true)
                            {
                                $('#forms_profile')[0].reset();
                                loadProfile();
                                toastr.success(data.message);
                            }
                            else 
                            {
                                toastr.error(data.message);
                            }
                        },error:function()
                        {
                            $('#action').attr('disabled', false);
                            toastr.error('Please try again.');
                        }
                    })
                }
                else
                {
                    toastr.error('Password does not match.');
                }
            });
        <?php } else {?>
        
            $('#add_button').click(function(){
                $('#forms')[0].reset();
                $('.modal-title').html("<?php echo $title; ?>");
                $('#action').html("Save");
                $('#action').val('<?php echo $title; ?>_add');
                $('#btn_action').val('<?php echo $title; ?>_add');
                $('.files').attr('src', "assets/image-placeholder.png" );
            });
    
            $(document).on('submit','#forms', function(event){
                event.preventDefault();
                $('#action').attr('disabled','disabled');
                var form_data = $(this).serialize();
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    dataType:"json",
                    success:function(data)
                    {
                        $('#action').attr('disabled', false);
                        if (data.status == true)
                        {
                            $('#forms')[0].reset();
                            $('#addModal').modal('hide');
                            dataTable.ajax.reload();
                        }
                        else 
                        {
                            toastr.error(data.message);
                        }
                    },error:function()
                    {
                        $('#action').attr('disabled', false);
                        toastr.error('Please try again.');
                    }
                })
            });
        
            $(document).on('click', '.delete', function(){
                var id = $(this).attr('id');
                var btn_action = '<?php echo $title; ?>_delete';
                Swal.fire({
                    icon: 'question',
                    title: 'Are you sure you want to delete this?',
                    showCancelButton: true,
                    showDenyButton: false,
                    confirmButtonText: 'OK',
                    cancelButtonText: `Cancel`,
                }).then((result) => {
                    if (result.isConfirmed) 
                    {
                        $.ajax({
                            url:"action.php",
                            method:"POST",
                            data:{id:id, btn_action:btn_action},
                            dataType:"json",
                            success:function(data)
                            {
                                if (data.status == true)
                                {
                                    dataTable.ajax.reload();
                                }
                                else 
                                {
                                    toastr.error(data.message);
                                }
                            },error:function()
                            {
                                toastr.error('Please try again.');
                            }
                        })
                    } 
                    else if (result.isDenied) { }
                })
            });

            var dataTable = $("#datatables").DataTable({
                "responsive": true, 
                "lengthChange": true, 
                "autoWidth": false,
                "processing":true,
                "serverSide":true,
                "ordering": false,
                "order":[],
                "ajax":{
                    url:"fetch/settings.php",
                    type:"POST"
                },
                "columnDefs":[
                    {
                    "targets":[0],
                    "orderable":false,
                    },
                ],
                "pageLength": 10, 
            });

        <?php } ?>

    });
</script>

</body>
</html>