<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Pending';
include('header.php');

include('sidebar.php');

?>

  <div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0"><?php echo $title." List"; ?> </h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-dark elevation-2">
                        <div class="card-body">
                            <table id="datatables" class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th>Reservation ID</th>
                                        <th>Client</th>
                                        <th>Details</th>
                                        <th>Date Reserved</th>
                                        <th>Action</th>
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

<div id="questionModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header bg-dark">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <label class="question text-lg"></label>
                <div class="row">
                    <div class="form-group col-12 col-md-12 reason">
                        <label>Reason</label>
                        <textarea class="form-control" placeholder="Reason for declining" name="reason" id="reason" ></textarea>
                    </div>
                    <div class="form-group col-12 col-md-12 datatables_items">
                        <table id="datatables_items" class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Category</th>
                                    <th>Product</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer ">
                <input type="hidden" name="id" id="id"/>
                <input type="hidden" name="status" id="status"/>
                <input type="hidden" name="total" id="total"/>
                <button type="button" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="btn_save" id="btn_save" ></button>
                <button type="button" class="btn btn-default text-bold pl-3 pr-3 elevation-2" data-dismiss="modal" >Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        const socket = io('<?php echo $server_link; ?>');

        function sendData(data) 
        {
            socket.emit('message', JSON.stringify(data))
        }

        $(document).on('click', '.status', function(){
            var id = $(this).attr("id");
            var status = $(this).data("status");
            var customer = $(this).data("customer");
            var reservation_id = $(this).data("reservation_id");
            $('#questionModal').modal('show');
            $('.modal-title').html('#'+reservation_id);
            $('#id').val(id);
            $('.datatables_items').addClass('hidden');
            if (status == 'Decline')
            {
                $('.reason').removeClass('hidden');
                $('.question').html('Are you sure to decline this reservation ?').removeClass('hidden');
                $('#btn_save').html("Yes, Decline").removeClass('hidden');
                $('#status').val("Declined");
            }
            else
            {
                $('.reason').addClass('hidden');
                $('.question').html('Are you sure to reserve this reservation ?').removeClass('hidden');
                $('#btn_save').html("Yes, Reserve").removeClass('hidden');
                $('#status').val("Reserved");
            }
        });

        $(document).on('click', '.view', function(){
            var reservation_id = $(this).data("reservation_id");
            $('#questionModal').modal('show');
            $('.modal-title').html('#'+reservation_id);
            $('.question').addClass('hidden');
            $('.reason').addClass('hidden');
            $('#btn_save').addClass('hidden');
            $('.datatables_items').removeClass('hidden');
            loadItems('view', reservation_id);
        });
        
        function loadItems(title, reservation_id)
        {
            $.ajax({
                url:"action.php",
                method:"POST",
                data: { reservation_id:reservation_id, title: title, btn_action: 'items_list' },
                dataType:"json",
                success:function(data)
                {
                    count = data.count;
                    $('#datatables_items').DataTable().destroy();
                    $('#datatables_items').html(data.table);  
                    $("#datatables_items").DataTable({
                        "paging": false,
                        "lengthChange": false,
                        "searching": false,
                        "ordering": false,
                        "info": false,
                        "autoWidth": false,
                        "responsive": true,
                        "pageLength": -1, 
                    });
                    $('#datatables_items').DataTable().draw();
                },error:function()
                {
                    toastr.error('Please try again.');
                }
            })
        }
        
        $('#btn_save').click(function(){
            if ($('#status').val() == 'Declined')
            {
                if ($('#reason').val() == '')
                {
                    toastr.error('Please enter the reason for declining.');
                }
                else
                {
                    Swal.fire({
                        icon: 'question',
                        title: 'Are you sure?',
                        showCancelButton: true,
                        showDenyButton: false,
                        confirmButtonText: 'Yes',
                        cancelButtonText: `No`,
                    }).then((result) => {
                        if (result.isConfirmed) 
                        {
                            $.ajax({
                                url:"action.php",
                                method:"POST",
                                data:{id:$('#id').val(), status:$('#status').val(), reason:$('#reason').val(), btn_action:'Reservation_status'},
                                dataType:"json",
                                success:function(data)
                                {
                                    if (data.status == true)
                                    {
                                        toastr.success(data.message);
                                        sendData( { title: data.title, body: data.body, action: 'customer', id: data.id } );
                                    }
                                    else
                                    {
                                        toastr.error(data.message);
                                    }
                                    $('#questionModal').modal('hide');
                                    dataTable.ajax.reload();
                                },error:function()
                                {
                                    toastr.error('Please try again.');
                                }
                            })
                        }
                        else if (result.isDenied) { }
                    })
                }
            }
            else
            {
                Swal.fire({
                    icon: 'question',
                    title: 'Are you sure?',
                    showCancelButton: true,
                    showDenyButton: false,
                    confirmButtonText: 'Yes',
                    cancelButtonText: `No`,
                }).then((result) => {
                    if (result.isConfirmed) 
                    {
                        $.ajax({
                            url:"action.php",
                            method:"POST",
                            data:{id:$('#id').val(), status:$('#status').val(), btn_action:'Reservation_status'},
                            dataType:"json",
                            success:function(data)
                            {
                                if (data.status == true)
                                {
                                    toastr.success(data.message);
                                    sendData( { title: data.title, body: data.body, action: 'customer', id: data.id } );
                                }
                                else
                                {
                                    toastr.error(data.message);
                                }
                                $('#questionModal').modal('hide');
                                dataTable.ajax.reload();
                            },error:function()
                            {
                                toastr.error('Please try again.');
                            }
                        }) 
                    }
                    else if (result.isDenied) { }
                })
            }

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
                url:"fetch/pending.php",
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