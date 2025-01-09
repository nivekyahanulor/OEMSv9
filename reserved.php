<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Reserved';
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
        <form method="post" id="forms">
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
                                        <th>Action</th>
                                        <th>Category</th>
                                        <th>Product</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group col-12 col-md-12 bartender bartender_view">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label class="bartender_text text-lg"></label>
                                </div>
                                <div class="form-group col-12 col-md-8">
                                    <label>Bartender Name</label>
                                    <input type="text" class="form-control" name="name" id="name_bartender" />
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <label class="d-none d-md-block">&nbsp;</label>
                                    <button type="button" name="btn_add_bartender" id="btn_add_bartender" class="btn btn-dark text-bold pl-3 pr-3 elevation-2 btn-block" >Add</button>
                                </div>
                                <div class="form-group col-12 col-md-12">
                                    <table id="datatables_bartender" class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Bartender</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-12 cocktails_details">
                            <div class="row">
                                <div class="form-group col-12 col-md-6">
                                    <label>Event</label>
                                    <select class="form-control" name="events_id" id="events_id"  >
                                    <option value="">Select</option>
                                        <?php
                                            $output = '';
                                            $rslt = fetch_all($connect,"SELECT * FROM $EVENTS_TABLE WHERE status = 'Active' " );
                                            foreach($rslt as $row)
                                            {
                                                $output .= '<option value="'.$row["event"].'">'.$row['event'].'</option>';
                                            }
                                            echo $output;
                                        ?>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Other</label>
                                    <input type="text"class="form-control" name="other" id="other" disabled />
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Date</label>
                                    <div class="input-group date" id="event_dates" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input " data-toggle="datetimepicker" data-target="#event_dates" 
                                            name="event_date" id="event_date" />
                                        <div class="input-group-append" data-target="#event_dates" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Time</label>
                                    <div class="input-group date" id="event_times" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input " data-toggle="datetimepicker" data-target="#event_times" 
                                            name="event_time" id="event_time" />
                                        <div class="input-group-append" data-target="#event_times" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-12">
                                    <label>Event Address</label>
                                    <textarea class="form-control" name="address" id="address" ></textarea>
                                </div>
                                <!-- <div class="form-group col-12 col-md-6">
                                    <label>Cocktail</label>
                                    <select class="form-control " name="product_id" id="product_id"  >
                                        <option value="">Select</option>
                                        <?php
                                            $output = '';
                                            $rslt = fetch_all($connect,"SELECT * FROM $PRODUCT_TABLE WHERE status = 'Active' AND category_id = '1' " ); //  
                                            foreach($rslt as $row)
                                            {
                                                $output .= '<option value="'.$row["id"].'">'.$row['product'].'</option>';
                                            }
                                            echo $output;
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label>Quantity</label>
                                    <input type="number" min="1" class="form-control" name="quantity" id="quantity"  />
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label class="d-none d-md-block">&nbsp;</label>
                                    <button type="button" name="btn_add" id="btn_add1" class="btn btn-dark text-bold pl-3 pr-3 elevation-2 btn-block" >Add</button>
                                </div>
                                <div class="form-group col-12 col-md-12 ">
                                    <table id="datatables_items1" class="table table-bordered ">
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
                                </div> -->
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6 ">
                            <label>Services Amount</label>
                            <input type="number" min="0" class="form-control" name="services_amount" id="services_amount" />
                        </div>
                        <div class="form-group col-12 col-md-6 total_amount">
                            <label>Total Amount</label>
                            <input type="text" class="form-control total_amounts" disabled/>
                        </div>
                        <div class="form-group col-12">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file" id="file" accept=".docs, .pdf" >
                                    <label style="text-align: left" class="custom-file-label" for="file">Upload contract(docs, .pdf)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <input type="hidden" name="id" id="id"/>
                    <input type="hidden" name="types" id="types"/>
                    <input type="hidden" name="status" id="status"/>
                    <input type="hidden" name="btn_action" id="btn_action"/>
                    <button type="submit" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="btn_save" id="btn_save" >Save</button>
                    <button type="button" class="btn btn-default text-bold pl-3 pr-3 elevation-2" data-dismiss="modal" >Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(function () {
        const socket = io('<?php echo $server_link; ?>');

        function sendData(data) 
        {
            socket.emit('message', JSON.stringify(data))
        }
        
        $('#events_id').change(function(){
            $('#other').attr('disabled','disabled').removeAttr('required','required');
            if ($(this).val() == 'Other')
            {
                $('#other').removeAttr('disabled','disabled').attr('required','required');
            }
        });

        var reservation_id = '';
        var types = '';
        var bartender = 0;
        var total_price = 0;
        $(document).on('click', '.status', function(){
            var id = $(this).attr("id");
            var status = $(this).data("status");
            reservation_id = $(this).data("reservation_id");
            types = $(this).data("types");
            bartender = $(this).data("bartender");
            $('#forms')[0].reset();
            $('#questionModal').modal('show');
            $('.modal-title').html('#'+reservation_id);
            $('#id').val(id);
            $('#types').val(types);
            $('#status').val("Quoted");
            $('#btn_action').val("Reservation_status");
            $('#services_amount').val('');
            if (bartender !== 0)
            {
                $('.bartender_text').html('Please add '+bartender+' bartender/s.');
                $('.bartender').removeClass('hidden');
                loadBartender('add');
            }
            else
            {
                $('.bartender').addClass('hidden');
            }
            if (types == 'Beverage')
            {
                $('.datatables_items').removeClass('hidden');
                $('.cocktails_details').addClass('hidden');
                // load items
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data: { reservation_id:reservation_id, title: 'quotation', btn_action: 'items_list' },
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
                        total_price = data.total_price;
                        if ($('#services_amount').val() != '')
                        {
                            $('.total_amounts').val( parseFloat(total_price) + parseFloat($('#services_amount').val()) );  
                        }
                        else
                        {
                            $('.total_amounts').val(total_price);  
                        }
                    },error:function()
                    {
                        toastr.error('Please try again.');
                    }
                })
            }
            else
            {
                $('.cocktails_details').removeClass('hidden');
                $('.datatables_items').addClass('hidden');
                $('#events_id').val('');
                $('#other').attr('disabled','disabled').removeAttr('required','required').val('');
                $('#event_date').val('');
                $('#event_time').val('');
                $('#address').val('');
                // load cocktails
                loadCocktails();
            }
        });

        $("#services_amount").on('change keyup paste', function () {
            if (this.value == '')
            {
                $(this).val("0");
            }
            
            $('.total_amounts').val( parseFloat(total_price) + parseFloat($('#services_amount').val()) );  
        });

        function loadCocktails()
        {
            $.ajax({
                url:"action.php",
                method:"POST",
                data: { reservation_id:reservation_id, title: '', btn_action: 'items_list' },
                dataType:"json",
                success:function(data)
                {
                    $('#datatables_items1').DataTable().destroy();
                    $('#datatables_items1').html(data.table);  
                    $("#datatables_items1").DataTable({
                        "paging": false,
                        "lengthChange": false,
                        "searching": false,
                        "ordering": false,
                        "info": false,
                        "autoWidth": false,
                        "responsive": true,
                        "pageLength": -1, 
                    });
                    $('#datatables_items1').DataTable().draw();
                    total_price = data.total_price;
                    if ($('#services_amount').val() != '')
                    {
                        $('.total_amounts').val( parseFloat(total_price) + parseFloat($('#services_amount').val()) );  
                    }
                    else
                    {
                        $('.total_amounts').val(total_price);  
                    }
                },error:function()
                {
                    toastr.error('Please try again.');
                }
            })
        }
        
        $('#btn_add1').click(function(){
            if ($('#product_id').val() == "" || $('#quantity').val() == "")
            {
                toastr.error('Please select cocktail and enter quantity.');
            }
            else
            {
                var category_id = 1;
                var product_id = $('#product_id').val();
                var quantity = $('#quantity').val();
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data: { category_id:category_id, product_id:product_id, quantity:quantity, reservation_id:reservation_id, btn_action: 'items_add' },
                    dataType:"json",
                    success:function(data)
                    {
                        if (data.status)
                        {
                            loadCocktails();
                            $('#product_id').val('');
                            $('#quantity').val('');
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
        });
        
        var total_bartender = 0;
        function loadBartender(title)
        {
            $.ajax({
                url:"action.php",
                method:"POST",
                data: { reservation_id:reservation_id, title: title, btn_action: 'bartender_list' },
                dataType:"json",
                success:function(data)
                {
                    total_bartender = data.count;
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

        $('#btn_add_bartender').click(function(){
            if (bartender !== total_bartender)
            {
                if ($('#name_bartender').val() == "")
                {
                    toastr.error('Please enter bartender.');
                }
                else
                {
                    $.ajax({
                        url:"action.php",
                        method:"POST",
                        data: { reservation_id:reservation_id, name:$('#name_bartender').val(), btn_action: 'bartender_add' },
                        dataType:"json",
                        success:function(data)
                        {
                            $('#name_bartender').val('');
                            loadBartender('add');
                        },error:function()
                        {
                            toastr.error('Please try again.');
                        }
                    })
                }
            }
            else
            {
                toastr.error('You already added '+bartender+' bartender.');
            }
        });
    
        $(document).on('click', '.delete', function(){
            var id = $(this).attr("id");
            var title = $(this).data("title");
            if ( title == "items")
            {
                var btn_action = 'items_delete';
            }
            else if ( title == "bartender")
            {
                var btn_action = 'bartender_delete';
            }
            $.ajax({
                url:"action.php",
                method:"POST",
                data:{id:id, btn_action:btn_action},
                dataType:"json",
                success:function(data)
                {
                    if ( title == "items")
                    {
                        loadCocktails(); 
                    }
                    else if ( title == "bartender")
                    {
                        loadBartender('add');
                    }
                    // else
                    // {
                    //     loadQuotation(reservation_id, 'add');
                    // }
                },error:function()
                {
                    toastr.error('Please try again.');
                }
            })
        });
    
        $(document).on('submit','#forms', function(event){
            event.preventDefault();
            if (bartender != 0)
            {
                if (bartender !== total_bartender)
                {
                    toastr.error('Please add '+bartender+' bartender.');
                    return;
                }
            }
            if (types == 'Cocktails')
            {
                if ( $('#event_date').val() == '' || $('#event_time').val() == '' || $('#events_id').val() == ''|| $('#address').val() == '') //  
                {
                    toastr.error('Please enter event, date, time and address.');
                    return;
                }
                // else if ( total_price == 0 ) 
                // {
                //     toastr.error('Please add cocktails in the table.');
                //     return;
                // }
            }
            if ($('#services_amount').val() == '') 
            {
                toastr.error('Please enter amount in services.');
                return;
            }
            if ($('#file').val() == '') 
            {
                toastr.error('Please upload a contact file.');
                return;
            }
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
                    $('#btn_save').attr('disabled','disabled');
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
                            $('#btn_save').attr('disabled', false);
                        },error:function()
                        {
                            $('#btn_save').attr('disabled', false);
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
                url:"fetch/reserved.php",
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

        $('#event_dates').datetimepicker({
            format: 'MM-DD-YYYY',
            minDate: moment(new Date()).add(2, 'days').startOf('day'),
        });

        $('#event_times').datetimepicker({
            icons: {
                time: "fa fa-clock",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'hh:00 A',
        });
        bsCustomFileInput.init();

    });
</script>
  
</body>
</html>