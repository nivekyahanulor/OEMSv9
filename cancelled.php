<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Cancelled';
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
                                        <th>Action</th>
                                        <th>Reservation ID</th>
                                        <th>Details</th>
                                        <th>Date Reserved</th>
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
    <div class="modal-dialog modal-xl">
        <form method="post" id="forms">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="row">
                                <div class="form-group col-12 col-md-4">
                                    <select class="form-control" name="events_id" id="events_id" required >
                                        <option value="">Select Event</option>
                                        <?php
                                            $output = '';
                                            $rslt = fetch_all($connect,"SELECT * FROM $EVENTS_TABLE WHERE status = 'Active' " );
                                            foreach($rslt as $row)
                                            {
                                                $output .= '<option value="'.$row["event"].'">'.$row['event'].'</option>';
                                            }
                                            echo $output;
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <div class="input-group date" id="event_dates" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input " data-toggle="datetimepicker" data-target="#event_dates" name="event_date" id="event_date" placeholder="Event Date" required/>
                                        <div class="input-group-append" data-target="#event_dates" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <div class="input-group date" id="event_times" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input " data-toggle="datetimepicker" data-target="#event_times" name="event_time" id="event_time" placeholder="Event Time" required/>
                                        <div class="input-group-append" data-target="#event_times" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <input type="number" min="1" max="15" class="form-control" placeholder="Total Days" name="total_days" id="total_days" required />
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <input type="number" min="1" max="23" class="form-control" placeholder="Total Hours" name="total_hours" id="total_hours" required />
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <input type="number" min="1" class="form-control" placeholder="# of Guests" name="guests" id="guests" required />
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <select class="form-control" name="bartender" id="bartender"  >
                                        <option value="">Bartender?</option>
                                        <!-- <option value="Yes">Yes</option> -->
                                        <!-- <option value="No">No</option> -->
                                        <option value="1">1 bartender for 50 pax</option>
                                        <option value="2">2 bartender for 100 pax</option>
                                        <option value="3">3 bartender for 110-150 pax</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <input type="text" class="form-control" placeholder="Event Place" name="event_place" id="event_place" required />
                                </div>
                                <div class="form-group col-12 col-md-12">
                                    <textarea class="form-control" placeholder="Event Address" name="address" id="address" required></textarea>
                                </div>
                                <div class="form-group col-12 col-md-12">
                                    <textarea class="form-control" placeholder="Notes" name="notes" id="notes" ></textarea>
                                </div>
                                <div class="form-group col-12 col-md-12 datatables_quotation text-center">
                                    <hr class="p-0 m-0 mb-2">
                                    <label>QUOTATION</label>
                                    <table id="datatables_quotation1" class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="row">
                                <div class="form-group col-12 col-md-12">
                                    <div><h6><strong>PAYMENT RULES:</strong></h6></div>
                                    <ol>
                                        <li>Require to settle full payment if the event atleast <b>2 weeks</b> before the event.</li>
                                        <li>10% of total package must be settle to <b>guarantee</b> booking/block the date 1-5 days settlement when the status will be ACCEPTED or CANCELLED.</li>
                                        <li>40% must be settled  atleast <b>15 days</b> before the event.</li>
                                        <li>50% remaining will be settle atleast <b>1 week</b> before the event.</li>
                                    </ol>
                                </div>
                                <div class="form-group col-12 col-md-12">
                                    <hr class="p-0 m-0">
                                </div>
                                <div class="form-group col-12 col-md-6 category_id">
                                    <select class="form-control" name="category_id" id="category_id"  >
                                        <option value="">Select Category</option>
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
                                <div class="form-group col-12 col-md-6 btn_add">
                                    <button type="button" name="btn_add" id="btn_add" class="btn btn-dark text-bold pl-3 pr-3 elevation-2 btn-block" >Add</button>
                                </div>
                                <div class="form-group col-12 col-md-6 product_id">
                                    <select class="form-control " name="product_id" id="product_id"  >
                                        <option value="">Select Product</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-6 product_id">
                                    <input type="number" min="1" class="form-control" placeholder="Quantity" name="quantity" id="quantity"  />
                                </div>
                                <div class="form-group col-12 col-md-12">
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
                        <div class="col-12 col-md-12 payment_view">
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <input type="hidden" name="bartender_no" id="bartender_no"/>
                    <input type="hidden" name="btn_action" id="btn_action"/>
                    <button type="button" class="btn btn-dark text-bold pl-3 pr-3 elevation-2 hidden" name="btn_complete" id="btn_complete" >Complete</button>
                    <button type="submit" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="action" id="action" >Save</button>
                    <button type="button" class="btn btn-default text-bold pl-3 pr-3 elevation-2" data-dismiss="modal" >Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

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
                        <textarea class="form-control" placeholder="Reason for declining" name="reason" id="reason" ></textarea>
                    </div>
                </div>
                <div class="row quotation">
                    <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
                        <div class="form-group quo_view col-12 col-md-8">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Item Name" />
                        </div>
                        <div class="form-group quo_view col-12 col-md-4">
                            <button type="button" name="btn_add_quo" id="btn_add_quo" class="btn btn-dark text-bold pl-3 pr-3 elevation-2 btn-block" >Add</button>
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <table id="datatables_quotation" class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Item Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group col-12">
                            <hr class="m-0 p-0"/>
                        </div>
                        
                    <?php }?>
                    <div class="form-group bartender bartender_view col-12">
                        <label class="bartender_text text-lg"></label>
                    </div>
                    <div class="form-group bartender bartender_view col-12 col-md-8">
                        <input type="text" class="form-control" name="name" id="name_bartender" placeholder="Bartender Name" />
                    </div>
                    <div class="form-group bartender bartender_view col-12 col-md-4">
                        <button type="button" name="btn_add_bartender" id="btn_add_bartender" class="btn btn-dark text-bold pl-3 pr-3 elevation-2 btn-block" >Add</button>
                    </div>
                    <div class="form-group bartender col-12 col-md-12">
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
                    <div class="form-group bartender col-12">
                        <hr class="m-0 p-0"/>
                    </div>
                    <div class="form-group col-12 col-md-12">
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
                    </div>
                    <div class="form-group col-12 col-md-6 ">
                        <label>Services</label>
                        <input type="number" min="0" class="form-control" name="services_amount" id="services_amount" placeholder="Amount"  />
                    </div>
                    <div class="form-group col-12 col-md-6 ">
                        <label>Fees</label>
                        <input type="number" min="0" class="form-control" name="fees_amount" id="fees_amount" placeholder="Amount"  />
                    </div>
                    <div class="form-group col-12 col-md-12 total_amount hidden">
                        <label>Total Amount</label>
                        <input type="text" class="form-control total_amounts" disabled/>
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

<div id="paymentModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog ">
        <form method="post" id="forms_payment">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-12">
                            <select class="form-control" name="payment_method" id="payment_method" required >
                                <option value="Installment">Installment</option>
                                <option value="Full Payment">Full Payment</option>
                            </select>
                        </div>
                        <div class="form-group col-12 ">
                            <label class="details"></label>
                            <div class="text-center">
                                <div class="image-upload " >
                                    <label for="files">
                                        <img class="img-thumbnail files" src="assets/image-placeholder.png" 
                                            style="cursor:pointer; width: 200px; height: 200px;"/>
                                    </label>
                                    <input type="file" accept=".png, .jpg, .jpeg" onchange="readURL(this);" name="files" id="files" />
                                </div>
                                <i>Click image to upload</i>
                            </div> 
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <input type="text" class="form-control" name="sender_name" id="sender_name" placeholder="Sender Name" required />
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <select class="form-control" name="payment_server" id="payment_server" required >
                                <option value="">-Select Type-</option>
                                <option value="Gcash">Gcash</option>
                                <option value="Paymaya">Paymaya</option>
                                <option value="PBI">PBI</option>
                                <option value="CHINABANK">CHINABANK</option>
                                <option value="BDO">BDO</option>
                                <option value="LandBank">LandBank</option>
                                <option value="PNB">PNB</option>
                                <option value="Security Bank">Security Bank</option>
                                <option value="Union Bank">Union Bank</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <input type="text" class="form-control" name="payment_no" id="payment_no" placeholder="Transaction/Reference No." required />
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <input type="text" class="form-control payment_amount"  disabled />
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <input type="hidden" name="payment_amount" id="payment_amount"/>
                    <input type="hidden" name="id" id="id_payment"/>
                    <input type="hidden" name="reservation_id" id="reservation_id_payment"/>
                    <input type="hidden" name="btn_action" id="btn_action_payment"/>
                    <button type="submit" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="action" id="action_payment" >Save</button>
                    <button type="button" class="btn btn-default text-bold pl-3 pr-3 elevation-2" data-dismiss="modal" >Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="rateModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog ">
        <form method="post" id="forms_rate">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 ">
                            <div class="rate">
                                <input type="radio" id="star5" name="rate" value="5" />
                                <label for="star5" title="text">5 stars</label>
                                <input type="radio" id="star4" name="rate" value="4" />
                                <label for="star4" title="text">4 stars</label>
                                <input type="radio" id="star3" name="rate" value="3" />
                                <label for="star3" title="text">3 stars</label>
                                <input type="radio" id="star2" name="rate" value="2" />
                                <label for="star2" title="text">2 stars</label>
                                <input type="radio" id="star1" name="rate" value="1" />
                                <label for="star1" title="text">1 star</label>
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <textarea name="feedback" id="feedback" class="form-control" placeholder="Feedback" required ></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <input type="hidden" name="id" id="id_rate"/>
                    <input type="hidden" name="ratings" id="ratings"/>
                    <input type="hidden" name="btn_action" id="btn_action_rate"/>
                    <button type="submit" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="action" id="action_rate" >Save</button>
                    <button type="button" class="btn btn-default text-bold pl-3 pr-3 elevation-2" data-dismiss="modal" >Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="acceptModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog ">
        <form method="post" id="forms_accept">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label class="text-lg">Are you sure to accept this reservation ?</label>
                    <div class="row">
                        <div class="form-group col-12">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file" id="file" accept=".jpg, .png, .jpeg" required>
                                    <label style="text-align: left" class="custom-file-label" for="file">Upload image(jpg, .png, .jpeg)</label>
                                </div>
                            </div>
                            <!-- <textarea name="feedback" id="feedback" class="form-control" placeholder="Feedback" required ></textarea> -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <input type="hidden" name="id" id="id_accept"/>
                    <input type="hidden" name="status" id="status_accept"/>
                    <input type="hidden" name="btn_action" id="btn_action_accept"/>
                    <button type="submit" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="action" id="action_accept" >Save</button>
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

        const socket = io('<?php echo $server_link; ?>');

        function sendData(data) 
        {
            socket.emit('message', JSON.stringify(data))
        }
        // sendData( { title: 'chatroom', body: '<?php echo $_SESSION["user_id"]; ?>' } );

        $('.select2').select2();
    
        $(document).on('submit','#forms_accept', function(event){
            event.preventDefault();
            $('#action_accept').attr('disabled','disabled');
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
                    $('#action_accept').attr('disabled', false);
                    $('#forms_accept')[0].reset();
                    $('#acceptModal').modal('hide');
                    dataTable.ajax.reload();
                    if (data.status == true)
                    {
                        toastr.success(data.message);
                    }
                    else 
                    {
                        toastr.error(data.message);
                    }
                },error:function()
                {
                    $('#action_accept').attr('disabled', false);
                    toastr.error('Please try again.');
                }
            })
        });

        var star = 0;
        
        $('#star5').click(function(){
            star = 5;
            $('#ratings').val(5);
        }); 
        
        $('#star4').click(function(){
            star = 4;
            $('#ratings').val(4);
        }); 
        
        $('#star3').click(function(){
            star = 3;
            $('#ratings').val(3);
        }); 
        
        $('#star2').click(function(){
            star = 2;
            $('#ratings').val(2);
        }); 
        
        $('#star1').click(function(){
            star = 1;
            $('#ratings').val(1);
        }); 
    
        $(document).on('click', '.rates', function(){
            var id = $(this).attr("id");
            $('#id_rate').val(id);
            $('#rateModal').modal('show');
            reservation_id = $(this).data("reservation_id");
            $('.modal-title').html('#'+reservation_id);
            $('#action_rate').html("Rate");
            $('#action_rate').val('Reservation_rate');
            $('#btn_action_rate').val('Reservation_rate');
        });
    
        $(document).on('submit','#forms_rate', function(event){
            event.preventDefault();
            if (star == 0)
            {
                toastr.error("Please rate from 1 to 5.");
            }
            else
            {
                $('#action_rate').attr('disabled','disabled');
                var form_data = $(this).serialize();
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data:form_data,
                    dataType:"json",
                    success:function(data)
                    {
                        $('#action_rate').attr('disabled', false);
                        if (data.status == true)
                        {
                            $('#forms_rate')[0].reset();
                            $('#rateModal').modal('hide');
                            dataTable.ajax.reload();
                            toastr.success(data.message);
                        }
                        else 
                        {
                            toastr.error(data.message);
                        }
                    },error:function()
                    {
                        $('#action_rate').attr('disabled', false);
                        toastr.error('Please try again.');
                    }
                })
            }
        });
        
        $('#bartender').change(function(){
            $('#bartender_no').val(this.value);
        });
        
        $('#category_id').change(function(){
            loadProduct(this.value);
        });
        
        $('#payment_method').change(function(){
            if (this.value == 'Full Payment')
            {
                $('.details').html(total);
                $('#payment_amount').val(total_amount);
                $('.payment_amount').val(total_amount);
            }
            else
            {
                $('.details').html(details);
                $('#payment_amount').val(amount);
                $('.payment_amount').val(amount);
            }
        });
    
        var total = '';
        var details = '';
        var total_amount = '';
        var amount = '';
        $(document).on('click', '.payment', function(){
            var id = $(this).attr("id");
            reservation_id = $(this).data("reservation_id");
            details = $(this).data("details");
            total = $(this).data("total");
            var method = $(this).data("method");
            $('#paymentModal').modal('show');
            $('.modal-title').html('#'+reservation_id);
            $('.details').html(details);
            $('#id_payment').val(id);
            $('#reservation_id_payment').val(reservation_id);
            $('#date_paid').val('');
            $('#action_payment').html("Pay");
            $('#action_payment').val('Reservation_payment');
            $('#btn_action_payment').val('Reservation_payment');
            $('.files').attr('src', 'assets/image-placeholder.png');
            if (method == 'Installment')
            {
                $('#payment_method').attr('disabled', true).attr('required', false);
            }
            else
            {
                $('#payment_method').attr('disabled', false).attr('required', true);
            }
            amount = $(this).data("amount");
            total_amount = $(this).data("total_amount");
            $('#payment_amount').val(amount);
            $('.payment_amount').val(amount);
        });
    
        $(document).on('submit','#forms_payment', function(event){
            event.preventDefault();
            Swal.fire({
                icon: 'question',
                title: 'Are you sure that your payment is correct?',
                showCancelButton: true,
                showDenyButton: false,
                confirmButtonText: 'Yes',
                cancelButtonText: `No`,
            }).then((result) => {
                if (result.isConfirmed) 
                {
                    $('#action_payment').attr('disabled','disabled');
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
                            $('#action_payment').attr('disabled', false);
                            if (data.status == true)
                            {
                                $('#forms_payment')[0].reset();
                                $('#paymentModal').modal('hide');
                                dataTable.ajax.reload();
                                toastr.success(data.message);

                                sendData( { title: data.title, body: data.body, action: 'send', id: data.id } );
                            }
                            else 
                            {
                                toastr.error(data.message);
                            }
                        },error:function()
                        {
                            $('#action_payment').attr('disabled', false);
                            toastr.error('Please try again.');
                        }
                    })
                } 
                else if (result.isDenied) { }
            })
        });

        function loadProduct(category_id)
        {
            $.ajax({
                url:"action.php",
                method:"POST",
                data: { category_id:category_id, btn_action: 'product_list' },
                dataType:"json",
                success:function(data)
                {
                    $('#product_id').val('');
                    $('#product_id').html(data.products);
                },error:function()
                {
                    toastr.error('Please try again.');
                }
            })
        }

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
        
        $('#btn_add').click(function(){
            if ($('#category_id').val() == "")
            {
                toastr.error('Please select category.');
            }
            else
            {
                if ($('#product_id').val() == "" || $('#quantity').val() == "")
                {
                    toastr.error('Please select product and enter quantity.');
                }
                else
                {
                    var category_id = $('#category_id').val();
                    var product_id = $('#product_id').val();
                    var quantity = $('#quantity').val();
                    $.ajax({
                        url:"action.php",
                        method:"POST",
                        data: { category_id:category_id, product_id:product_id, quantity:quantity, btn_action: 'items_add' },
                        dataType:"json",
                        success:function(data)
                        {
                            if (data.status)
                            {
                                loadItems('add', '');
                                loadProduct('');
                                $('#category_id').val('');
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
            else
            {
                var btn_action = 'quotation_delete';
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
                        loadItems('add', '');
                    }
                    else if ( title == "bartender")
                    {
                        loadBartender(reservation_id, 'add');
                    }
                    else
                    {
                        loadQuotation(reservation_id, 'add');
                    }
                },error:function()
                {
                    toastr.error('Please try again.');
                }
            })
        });
        
        $('#add_button').click(function(){
            $('#forms')[0].reset();
            $('.modal-title').html("Reservation Form");
            $('#action').html("Book");
            $('#action').val('Reservation_add');
            $('#btn_action').val('Reservation_add');
            loadProduct('');
            loadItems('add', '');
            $('#action').removeClass("hidden");
            $('.category_id').removeClass("hidden");
            $('.product_id').removeClass("hidden");
            $('.btn_add').removeClass("hidden");
            
            $('#product').val('').trigger('change'); 
            
            $('#events_id').removeAttr("disabled","disabled");
            $('#event_date').removeAttr("disabled","disabled");
            $('#event_time').removeAttr("disabled","disabled");
            
            $('#total_days').removeAttr("disabled","disabled");
            $('#total_hours').removeAttr("disabled","disabled");

            $('#guests').removeAttr("disabled","disabled");
            $('#bartender').removeAttr("disabled","disabled");
            $('#address').removeAttr("disabled","disabled");
            $('#event_place').removeAttr("disabled","disabled");
            $('#notes').removeAttr("disabled","disabled");
            $('.datatables_quotation').addClass('hidden');
            deleteItem();

        });

        function deleteItem()
        {
            $.ajax({
                url:"action.php",
                method:"POST",
                data: {  btn_action: 'item_delete' },
                dataType:"json",
                success:function(data)
                {
                    
                },error:function()
                {
                    toastr.error('Please try again.');
                }
            })
        }
    
        $(document).on('submit','#forms', function(event){
            event.preventDefault();
            if (count > 0)
            {
                Swal.fire({
                    icon: 'question',
                    title: 'Are you sure that your reservation is correct?',
                    showCancelButton: true,
                    showDenyButton: false,
                    confirmButtonText: 'Yes',
                    cancelButtonText: `No`,
                }).then((result) => {
                    if (result.isConfirmed) 
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
                                    $('#forms')[0].reset();
                                    $('#addModal').modal('hide');
                                    dataTable.ajax.reload();
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
                    else if (result.isDenied) { }
                })
            }
            else
            {
                toastr.error('Please add items in the table.');
            }
        });

        var revs_id = 0;
        $(document).on('click', '.view', function(){
            var id = $(this).attr("id");
            $.ajax({
                url:"action.php",
                method:"POST",
                data:{id:id, btn_action:'Reservation_view'},
                dataType:"json",
                success:function(data)
                {
                    revs_id = data.rev_id; 
                    reservation_id = data.reservation_id; 
                    $('#addModal').modal('show');
                    $('.modal-title').html("Reservation");
                    $('#action').addClass("hidden");
                    $('.category_id').addClass("hidden");
                    $('.product_id').addClass("hidden");
                    $('.btn_add').addClass("hidden");

                    $('#events_id').attr("disabled","disabled").val(data.events_id);
                    $('#event_date').attr("disabled","disabled").val(data.event_date);
                    $('#event_time').attr("disabled","disabled").val(data.event_time);
                    
                    $('#total_days').attr("disabled","disabled").val(data.total_days);
                    $('#total_hours').attr("disabled","disabled").val(data.total_hours);

                    $('#guests').attr("disabled","disabled").val(data.guests);
                    $('#bartender').attr("disabled","disabled").val(data.bartender);
                    $('#address').attr("disabled","disabled").val(data.address);
                    $('#event_place').attr("disabled","disabled").val(data.event_place);
                    $('#notes').attr("disabled","disabled").val(data.notes);
                    
                    $('.payment_view').addClass('hidden');
                    $('.datatables_quotation').addClass('hidden');
                    loadItems('view', data.reservation_id);
                    if (data.status == 'Accepted' || data.status == 'Reserved' || data.status == 'Completed' || data.status == 'Processing')
                    {
                        // $('.datatables_quotation').removeClass('hidden');
                        // $.ajax({
                        //     url:"action.php",
                        //     method:"POST",
                        //     data: { reservation_id:data.reservation_id, title: 'view', btn_action: 'quotation_list' },
                        //     dataType:"json",
                        //     success:function(data)
                        //     {
                        //         $('#datatables_quotation1').DataTable().destroy();
                        //         $('#datatables_quotation1').html(data.table);  
                        //         $("#datatables_quotation1").DataTable({
                        //             "paging": false,
                        //             "lengthChange": false,
                        //             "searching": false,
                        //             "ordering": false,
                        //             "info": false,
                        //             "autoWidth": false,
                        //             "responsive": true,
                        //             "pageLength": -1, 
                        //         });
                        //         $('#datatables_quotation1').DataTable().draw();
                        //     },error:function()
                        //     {
                        //         toastr.error('Please try again.');
                        //     }
                        // })
                        if (data.status == 'Reserved' || data.status == 'Completed' || data.status == 'Processing')
                        {
                            $('.payment_view').removeClass('hidden');
                            $.ajax({
                                url:"action.php",
                                method:"POST",
                                data: { reservation_id:data.reservation_id, btn_action: 'reservation_view_payment' },
                                dataType:"json",
                                success:function(data)
                                {
                                    $('.payment_view').html(data.html);
                                },error:function()
                                {
                                    toastr.error('Please try again.');
                                }
                            })
                        }

                        if ((data.status == 'Reserved' || data.status == 'Processing') && '<?php echo $_SESSION["user_type"]; ?>' == 'Superadmin')
                        {
                            $('#btn_complete').removeClass('hidden');
                        }
                        else
                        {
                            $('#btn_complete').addClass('hidden');
                        }
                    }
                },error:function()
                {
                    toastr.error('Please try again.');
                }
            })
        });

        var bartender = 0;
        var reservation_id = '';
        $(document).on('click', '.status', function(){
            var id = $(this).attr("id");
            var status = $(this).data("status");
            var services_amount = $(this).data("services_amount");
            var fees_amount = $(this).data("fees_amount");
            reservation_id = $(this).data("reservation_id");
            $('#id').val(id);
            <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
                var customer = $(this).data("customer");
                $('.modal-title').html(customer);
            <?php } else {?>
                $('.modal-title').html('#'+reservation_id);
            <?php }?>
            modal = 'close';
            $('#btn_save').removeClass('hidden');
            $('.quo_view').removeClass('hidden');
            $('.bartender_view').removeClass('hidden');
            $('#services_amount').attr('disabled', true).val(services_amount);
            $('#fees_amount').attr('disabled', true).val(fees_amount);
            
            var total_amount = $(this).data("total_amount");
            $('.total_amount').addClass('hidden');
            $('.total_amounts').val(total_amount);
            if (status == 'Cancel')
            {
                $('#questionModal').modal('show');
                $('.reason').addClass('hidden');
                $('.quotation').addClass('hidden');
                $('.question').html('Are you sure to cancel this reservation ?').removeClass('hidden');
                $('#btn_save').html("Yes, Cancel");
                $('#status').val("Cancelled");
            }
            else if (status == 'Decline')
            {
                $('#questionModal').modal('show');
                $('.reason').removeClass('hidden');
                $('.quotation').addClass('hidden');
                $('.question').html('Are you sure to decline this reservation ?').removeClass('hidden');
                $('#btn_save').html("Yes, Decline");
                $('#status').val("Declined");
            }
            else if (status == 'Quotation')
            {
                $('#services_amount').attr('disabled', false);
                $('#fees_amount').attr('disabled', false);
                $('#questionModal').modal('show');
                $('.reason').addClass('hidden');
                $('.quotation').removeClass('hidden');
                $('.question').html('Please add event item.').removeClass('hidden');
                $('#btn_save').html("Send Quotation");
                $('#status').val("Quoted");
                bartender = $(this).data("bartender");
                if (bartender !== 0)
                {
                    $('.bartender_text').html('Please add '+bartender+' bartender.');
                    $('.bartender').removeClass('hidden');
                    loadBartender(reservation_id, 'add');
                }
                else
                {
                    $('.bartender').addClass('hidden');
                }
                loadQuotation(reservation_id, 'add');
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data: { reservation_id:reservation_id, title: 'quotation', btn_action: 'items_list' },
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
                    },error:function()
                    {
                        toastr.error('Please try again.');
                    }
                })
            }
            else if (status == 'Quoted')
            {
                $('.total_amount').removeClass('hidden');

                $('#questionModal').modal('show');
                $('.reason').addClass('hidden');
                $('.quotation').removeClass('hidden');
                $('.bartender_view').addClass('hidden');

                <?php if ($_SESSION["user_type"] == 'Client') {?>
                    $('.quo_view').addClass('hidden');
                    $('.question').html('You want to approve this quotation?').removeClass('hidden');
                    $('#btn_save').html("Yes, Approve it").removeClass('hidden');
                    $('#status').val("Approved");
                    //  show total amount
                <?php } else {?>
                    $('.quo_view').addClass('hidden');
                    $('.question').addClass('hidden');
                    $('#btn_save').addClass('hidden');
                <?php }?>

                bartender = $(this).data("bartender");
                if (bartender !== 0)
                {
                    $('.bartender').removeClass('hidden');
                    $('.bartender_text').addClass('hidden');
                    $('.bartender_view').addClass('hidden');
                    $('.quo_view').addClass('hidden');
                    loadBartender(reservation_id, 'view');
                }
                else
                {
                    $('.bartender').addClass('hidden');
                }
                loadQuotation(reservation_id, 'view');
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data: { reservation_id:reservation_id, title: 'quotation', btn_action: 'items_list' },
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
                    },error:function()
                    {
                        toastr.error('Please try again.');
                    }
                })
            }
            else if (status == 'Approved')
            {
                $('.total_amount').removeClass('hidden');
                $('#questionModal').modal('show');
                $('.reason').addClass('hidden');
                $('.quotation').removeClass('hidden');
                $('.bartender_view').addClass('hidden');
                $('.question').addClass('hidden');
                $('#btn_save').addClass('hidden');
                $('.quo_view').addClass('hidden');

                bartender = $(this).data("bartender");
                if (bartender !== 0)
                {
                    $('.bartender').removeClass('hidden');
                    $('.bartender_text').addClass('hidden');
                    $('.bartender_view').addClass('hidden');
                    // $('.quo_view').addClass('hidden');
                    loadBartender(reservation_id, 'view');
                }
                else
                {
                    $('.bartender').addClass('hidden');
                }
                loadQuotation(reservation_id, 'view');
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data: { reservation_id:reservation_id, title: 'quotation', btn_action: 'items_list' },
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
                    },error:function()
                    {
                        toastr.error('Please try again.');
                    }
                })
            }
            else if (status == 'Accept')
            {
                $('.total_amount').removeClass('hidden');
                // id, status : Accepted, btn_action : Reservation_status
                $('#acceptModal').modal('show');
                $('#status_accept').val("Accepted");
                $('#btn_action_accept').val("Reservation_status");
                $('#id_accept').val(id);

                // $('.reason').addClass('hidden');
                // $('.quotation').addClass('hidden');
                // $('.question').html('Are you sure to accept this reservation ?').removeClass('hidden');
                // $('#btn_save').html("Yes, Accept");
            }
            else
            {
                $('.total_amount').removeClass('hidden');
            }
        });

        function loadQuotation(reservation_id, title)
        {
            $.ajax({
                url:"action.php",
                method:"POST",
                data: { reservation_id:reservation_id, title: title, btn_action: 'quotation_list' },
                dataType:"json",
                success:function(data)
                {
                    $('#datatables_quotation').DataTable().destroy();
                    $('#datatables_quotation').html(data.table);  
                    $("#datatables_quotation").DataTable({
                        "paging": false,
                        "lengthChange": false,
                        "searching": false,
                        "ordering": false,
                        "info": false,
                        "autoWidth": false,
                        "responsive": true,
                        "pageLength": -1, 
                    });
                    $('#datatables_quotation').DataTable().draw();
                },error:function()
                {
                    toastr.error('Please try again.');
                }
            })
        }
        
        $('#btn_add_quo').click(function(){
            if ($('#name').val() == "" || $('#price').val() == "")
            {
                toastr.error('Please enter name and price.');
            }
            else
            {
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data: { reservation_id:reservation_id, name:$('#name').val(), price:$('#price').val(), btn_action: 'quotation_add' },
                    dataType:"json",
                    success:function(data)
                    {
                        $('#name').val('');
                        $('#price').val('');
                        loadQuotation(reservation_id, 'add');
                    },error:function()
                    {
                        toastr.error('Please try again.');
                    }
                })
            }
        });
        
        var total_bartender = 0;
        function loadBartender(reservation_id, title)
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
                            loadBartender(reservation_id, 'add');
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
        
        $('#btn_save').click(function(){
            if ($('#status').val() == 'Declined')
            {
                if ($('#reason').val() == '')
                {
                    toastr.error('Please enter the reason for declining.');
                }
                else
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
            }
            else
            {
                if ($('#status').val() == 'Quoted')
                {
                    if ($('#services_amount').val() == '' || $('#fees_amount').val() == '')
                    {
                        toastr.error('Please enter amount in services and fees.');
                    }
                    // if (total_count == 0)
                    // {
                    //     toastr.error('Please add quotation.');
                    // }
                    else
                    {
                        Swal.fire({
                            icon: 'question',
                            title: 'Are you sure that this quotation is correct?',
                            showCancelButton: true,
                            showDenyButton: false,
                            confirmButtonText: 'Yes',
                            cancelButtonText: `No`,
                        }).then((result) => {
                            if (result.isConfirmed) 
                            {
                                var status = false;
                                if (bartender !== 0)
                                {
                                    if (bartender !== total_bartender)
                                    {
                                        toastr.error('Please add '+bartender+' bartender.');
                                    }
                                    else
                                    {
                                        $.ajax({
                                            url:"action.php",
                                            method:"POST",
                                            data:{id:$('#id').val(), services_amount:$('#services_amount').val(), fees_amount:$('#fees_amount').val(), status:$('#status').val(), btn_action:'Reservation_status'},
                                            dataType:"json",
                                            success:function(data)
                                            {
                                                if (data.status == true)
                                                {
                                                    toastr.success(data.message);
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
                                }
                                else
                                {
                                    $.ajax({
                                        url:"action.php",
                                        method:"POST",
                                        data:{id:$('#id').val(), services_amount:$('#services_amount').val(), fees_amount:$('#fees_amount').val(), status:$('#status').val(), btn_action:'Reservation_status'},
                                        dataType:"json",
                                        success:function(data)
                                        {
                                            if (data.status == true)
                                            {
                                                toastr.success(data.message);
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
                            } 
                            else if (result.isDenied) { }
                        })
                    }
                }
                else if ($('#status').val() == 'Approved')
                {
                    Swal.fire({
                        icon: 'question',
                        title: 'Are you sure to approve this?',
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
                                        if ($('#status').val() == 'Completed')
                                        {
                                            modal = 'close';
                                        }
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
                else
                {
                    Swal.fire({
                        icon: 'question',
                        title: 'Are you sure to complete this?',
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
                                        if ($('#status').val() == 'Completed')
                                        {
                                            modal = 'close';
                                        }
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

        });
        
        var modal = 'open';
        $('#btn_complete').click(function(){
            // console.log(reservation_id, revs_id);

            $('#id').val(revs_id);
            $('#questionModal').modal('show');
            $('#questionModal .modal-title').html('#'+reservation_id);
            $('#addModal').modal('hide');

            $('.reason').addClass('hidden');
            $('.quotation').addClass('hidden');
            $('.question').html('Are you sure to complete this reservation ?').removeClass('hidden');
            $('#btn_save').html("Yes, Complete").removeClass('hidden');
            $('#status').val("Completed");
            modal = 'open';
            
        });

        $('#questionModal').on('hidden.bs.modal', function () {
            if (modal == 'open')
            {
                $('#addModal').modal('show');
            }
            else
            {
                $('#addModal').modal('hide');
            }
        })

        var dataTable = $("#datatables").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "processing":true,
            "serverSide":true,
            "ordering": false,
            "order":[],
            "ajax":{
                url:"fetch/cancelled.php",
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
            // icons: {
            //     time: "fa fa-clock",
            //     date: "fa fa-calendar",
            //     up: "fa fa-arrow-up",
            //     down: "fa fa-arrow-down"
            // },
            format: 'MM-DD-YYYY',
            // minDate: moment(new Date()).add(45, 'days').startOf('day'),
            minDate: moment(new Date()).add(0, 'days').startOf('day'),
        });

        $('#event_times').datetimepicker({
            icons: {
                time: "fa fa-clock",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'hh:mm A',
            // minDate: moment(new Date()).add(45, 'days').startOf('day'),
            // minDate: moment(new Date()).add(0, 'days').startOf('day'),
        });
        
        bsCustomFileInput.init();

    });
</script>
  
</body>
</html>