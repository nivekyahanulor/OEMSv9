<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Products';
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
                <div class="col-12">
					<?php if(isset($_GET['added'])){?>
					  <div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<strong>Success!</strong> Product Added!
						</div>
					<?php } ?>
					<?php if(isset($_GET['updated'])){?>
					  <div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<strong>Success!</strong> Product Updated!
						</div>
					<?php } ?>
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
                                        <th>Category</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <!-- <th>Description</th> -->
                                        <!-- <th>Date Added</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php

include('footer.php');

?>

<div id="addModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog ">
        <form method="post" action="action.php" enctype="multipart/form-data">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-4">
                            <label>Category</label>
                              <select class="form-control" name="category_id" id="category_id" required >
                                <option value="">Select</option>
                                <?php
                                    $output = '';
                                    $rslt = fetch_all($connect,"SELECT * FROM $CATEGORY_TABLE WHERE status = 'Active' " );
                                    foreach($rslt as $row)
                                    {
                                        $output .= '<option value="'.$row["id"].'">'.$row['category'].'</option>';
                                    }
                                    echo $output;
                                ?>
                              </select>
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <label>Product Name</label>
                            <input type="text" name="product" id="product" class="form-control " placeholder="Product Name" required />
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <label>Price</label>
                            <input type="number" min="0" name="price" id="price" class="form-control " placeholder="Price" required />
                        </div>
						 <div class="form-group col-12 ">
                                    <div class="text-center">
                                        <div class="image-upload " >
                                            <label for="files">
                                                <img class="img-thumbnail files" src="assets/image-placeholder.png" 
                                                    style="cursor:pointer; width: 200px; height: 200px;"/>
                                            </label>
                                            <input type="file" accept=".png, .jpg, .jpeg" onchange="readURL(this);" name="files" id="files"  required/>
                                        </div>
                                        <i>Click image to upload</i>
                                    </div> 
                                </div>
                        <!-- <div class="form-group col-12 col-md-12">
                            <textarea name="description" id="description" class="form-control " placeholder="Description" ></textarea>
                        </div> -->
                    </div>
                </div>
                <div class="modal-footer ">
                    <input type="hidden" name="id" id="id"/>
                    <input type="hidden" value="Products_add" name="btn_action" id="btn_action"/>
                    <button type="submit" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="action" id="action" >Save</button>
                    <button type="button" class="btn btn-default text-bold pl-3 pr-3 elevation-2" data-dismiss="modal" >Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

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
        
        $('#add_button').click(function(){
            $('#forms')[0].reset();
            $('.modal-title').html("<?php echo $title; ?>");
            $('#action').html("Save");
            $('#action').val('<?php echo $title; ?>_add');
            $('#btn_action').val('<?php echo $title; ?>_add');
        });
    
        $(document).on('submit','#forms', function(event){
            event.preventDefault();
            $('#action').attr('disabled','disabled');

            var form_data = $(this).serialize();
            $.ajax({
                url:"action.php",
                method:"POST",
                data:new FormData(this),
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
    
        $(document).on('click', '.update', function(){
            var id = $(this).attr("id");
            var btn_action = '<?php echo $title; ?>_fetch';
            $.ajax({
                url:"action.php",
                method:"POST",
                data:{id:id, btn_action:btn_action},
                dataType:"json",
                success:function(data)
                {
                    $('#id').val(id);
                    $('#category_id').val(data.category_id);
                    $('#product').val(data.product);
                    $('#price').val(data.price);
                    // $('#description').val(data.description);
                    $('#addModal').modal('show');
                    $('.modal-title').html("<?php echo $title; ?>");
                    $('#action').html("Save");
                    $('#action').val('<?php echo $title; ?>_update');
                    $('#btn_action').val('<?php echo $title; ?>_update');
                },error:function()
                {
                    toastr.error('Please try again.');
                }
            })
        });
    
        $(document).on('click', '.status', function(){
            var id = $(this).attr('id');
            var status = $(this).data("status");
            var btn_action = '<?php echo $title; ?>_status';
            Swal.fire({
                icon: 'question',
                title: 'Change status to '+ status+'?',
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
                        data:{id:id, status:status, btn_action:btn_action},
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
                url:"fetch/products.php",
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

    });
</script>

  
</body>
</html>