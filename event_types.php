<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Event Types';
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
                <?php if ($_SESSION["user_type"] !== 'Client') { ?>
                    <!-- <div class="col-12 col-md-4">
                        <div class="small-box bg-dark elevation-2">
                            <div class="inner text-white">
                                <h3 id="events"><?php echo get_total_count($connect, "SELECT * FROM $EVENTS_TABLE "); ?></h3>
                                <p class="text-bold">Total Events</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar-day text-white"></i>
                            </div>
                        </div>
                    </div> -->
                <?php } ?>
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
                                        <th>Event</th>
                                        <!-- <th>Pax</th>
                                        <th>Inclusion</th>
                                        <th>Description</th> -->
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
        <form method="post" id="forms">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-12">
                            <label>Event</label>
                            <input type="text" name="event" id="event" class="form-control " placeholder="Event" required />
                        </div>
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
                    $('#id').val(id);
                    $('#event').val(data.event);
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
                url:"fetch/events.php",
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