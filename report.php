<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Report';
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
                                        <th>Reservation </th>
                                        <th>Client Name </th>
                                        <th>Client Contact</th>
                                        <th>Item Name</th>
                                        <th>Date Added</th>
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
    <div class="modal-dialog">
        <form method="post" id="forms">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-12">
                            <label>Item</label>
                            <select class="form-control" name="item_id" id="item_id" required >
                                <option value="">Select</option>
                                <?php
                                    $output = '';
                                    $rslt = fetch_all($connect,"SELECT * FROM $INVENTORY_TABLE ORDER BY id DESC " );
                                    foreach($rslt as $row)
                                    {
                                        $output .= '<option data-qty="'.$row["quantity"].'" value="'.$row["id"].'">'.$row['item_name'].' </option>';
                                    }
                                    echo $output;
                                ?>
                            </select>
                        </div>
						 <div class="form-group col-12 col-md-12">
                            <label>Reservation</label>
                            <select class="form-control" name="reservation_id" id="reservation_id" required >
                                <option value="">Select</option>
                                <?php
                                    $output = '';
                                    $rslt = fetch_all($connect,"SELECT * FROM $RESERVATION_TABLE ORDER BY id DESC " );
                                    foreach($rslt as $row)
                                    {
                                        $output .= '<option  value="'.$row["id"].'">'.$row['reservation_id'].' </option>';
                                    }
                                    echo $output;
                                ?>
                            </select>
                        </div>
                        <!--<div class="form-group col-12 col-md-12">
                            <label>Quantity</label>
                            <input type="number" min="0" step="1" name="quantity" id="quantity" class="form-control " placeholder="Quantity" required />
                        </div>-->
                        <!-- <div class="form-group col-12 col-md-12">
                            <select class="form-control" name="equipment" id="equipment" required >
                                <option value="">-Select Item-</option>
                                <option value="Sealer Machine">Sealer Machine</option>
                                <option value="Milk Tea and Straw">Milk Tea and Straw</option>
                                <option value="Plastic Shaker Cups">Plastic Shaker Cups</option>
                                <option value="Metal Stirrers">Metal Stirrers</option>
                                <option value="Measuring Cups">Measuring Cups</option>
                                <option value="Syrup Pumps">Syrup Pumps</option>
                                <option value="A Scale">A Scale</option>
                                <option value="Insulated Tea Dispenserr">Insulated Tea Dispenserr</option>
                                <option value="Coffee Maker">Coffee Maker</option>
                                <option value="Coffee Grinder ">Coffee Grinder </option>
                                <option value="Blender">Blender</option>
                            </select>
                        </div> -->
                    </div>
                </div>
                <div class="modal-footer ">
                    <input type="hidden" name="id" id="id"/>
                    <input type="hidden" name="btn_action" id="btn_action"/>
                    <button type="submit" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="action" id="action" >Save</button>
                    <button type="button" class="btn btn-default text-bold pl-3 pr-3 elevation-2" data-dismiss="modal" >Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
  
<script>
    $(function () {
        
        $('#item_id').change(function(){
            $('#quantity').removeAttr('max','max');
            if ($(this).val() != '')
            {
                $('#quantity').attr('max',$(this).find(':selected').data('qty'));
            }
        });
        
        $('#add_button').click(function(){
            $('#forms')[0].reset();
            $('.modal-title').html("<?php echo $title; ?>");
            $('#action').html("Save");
            $('#action').val('<?php echo $title; ?>_add');
            $('#btn_action').val('<?php echo $title; ?>_add');
            $('#quantity').removeAttr('max','max');
        });
    
        $(document).on('submit','#forms', function(event){
            event.preventDefault();
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
                    $('#quantity').removeAttr('max','max');
                    $('#id').val(id);
                    $('#item_id').val(data.item_id);
                    $('#reservation_id').val(data.reservation);
                    $('#quantity').val(data.quantity).attr('max',data.qty);
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

        var dataTable = $("#datatables").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "processing":true,
            "serverSide":true,
            "ordering": false,
            "order":[],
            "ajax":{
                url:"fetch/report.php",
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