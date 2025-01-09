<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Payment';
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
                <div class="row">
                    <div class="form-group col-12 col-md-12 datatables_items">
                        <table id="datatables_items" class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Product</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group col-12 col-md-12 bartender">
                        <table id="datatables_bartender" class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>Bartender</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group col-12 col-md-6 ">
                        <label>Services Amount</label>
                        <input type="number" min="0" class="form-control services_amount" disabled />
                    </div>
                    <div class="form-group col-12 col-md-6 total_amount">
                        <label>Total Amount</label>
                        <input type="text" class="form-control total_amounts" disabled/>
                    </div>
                </div>
            </div>
            <div class="modal-footer ">
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
                        data: { id:id, status: 'Completed', btn_action: 'Reservation_status' },
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
                            dataTable.ajax.reload();
                        },error:function()
                        {
                            toastr.error('Please try again.');
                        }
                    })
                }
                else if (result.isDenied) { }
            })
        });

        $(document).on('click', '.view', function(){
            var id = $(this).attr("id");
            reservation_id = $(this).data("reservation_id");
            bartender = $(this).data("bartender");
            var services_amount = $(this).data("services_amount");
            var total_amount = $(this).data("total_amount");
            $('#questionModal').modal('show');
            $('.modal-title').html('#'+reservation_id);
            $('.services_amount').val(services_amount);
            $('.total_amounts').val(total_amount);
               
            var types = $(this).data("types");
            if (types == 'Cocktails')
            {
                $('.datatables_items').addClass('hidden');
            }
            else
            {
                $('.datatables_items').removeClass('hidden');
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data: { reservation_id:reservation_id, title: 'quotation', btn_action: 'items_list' },
                    dataType:"json",
                    success:function(data)
                    {
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

            if (bartender !== 0)
            {
                $('.bartender').removeClass('hidden');
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data: { reservation_id:reservation_id, title: 'view', btn_action: 'bartender_list' },
                    dataType:"json",
                    success:function(data)
                    {
                        $('#datatables_bartender').DataTable().destroy();
                        $('#datatables_bartender').html(data.table);  
                        $("#datatables_bartender").DataTable({
                            "paging": false,
                            "lengthChange": false,
                            "searching": false,
                            "ordering": false,
                            "info": false,
                            "autoWidth": false,
                            "responsive": true,
                            "pageLength": -1, 
                        });
                        $('#datatables_bartender').DataTable().draw();
                    },error:function()
                    {
                        toastr.error('Please try again.');
                    }
                })
            }
            else
            {
                $('.bartender').addClass('hidden');
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
                url:"fetch/payment.php",
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