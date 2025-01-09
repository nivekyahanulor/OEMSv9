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
                          
                        </div>
                        <div class="card-body">
                            <table id="datatables" class="table table-bordered ">
                                <thead>
                                    <tr>
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
                url:"fetch/reports.php",
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