<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Reservation';
include('header.php');

include('sidebar.php');

?>

  <div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0"><?php echo $title." ".($_SESSION["user_type"] == 'Client' ? 'Status' : 'List'); ?> </h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
				    <ul class="nav nav-tabs" role="tablist">
						<li class="nav-item">
						  <a href="?data=Pending" class="nav-link <?php if($_GET['data'] == 'Pending'){ echo 'active';} else {} ?>" >Pending</a>
						</li>
						<li class="nav-item">
						  <a href="?data=Reserved" class="nav-link <?php if($_GET['data'] == 'Reserved'){ echo 'active';} else {} ?>">Reserved</a>
						</li>
						<li class="nav-item">
						  <a  href="?data=Quoted" class="nav-link <?php if($_GET['data'] == 'Quoted'){ echo 'active';} else {} ?>">Quoted</a>
						</li>
						<li class="nav-item">
						  <a  href="?data=Completed" class="nav-link <?php if($_GET['data'] == 'Completed'){ echo 'active';} else {} ?>">Completed</a>
						</li>
						<li class="nav-item">
						  <a  href="?data=Declined" class="nav-link <?php if($_GET['data'] == 'Declined'){ echo 'active';} else {} ?>">Declined</a>
						</li>
						<li class="nav-item">
						  <a  href="?data=Meeting" class="nav-link <?php if($_GET['data'] == 'Meeting'){ echo 'active';} else {} ?>">Meeting</a>
						</li>
				`    </ul>
                    <div class="card card-dark elevation-2">
                        <div class="card-header text-right">
						<?php
						 $limit = fetch_all($connect,"SELECT * FROM reservation WHERE client_id = '3' order by id desc limit 1 " );
						 foreach($limit as $c)
                         {
							if($c['status'] == 'Completed'){
						
						?>
                            <button type="button" name="add" id="add_button" data-toggle="modal" data-target="#addModal" class="btn btn-default elevation-2 text-bold pl-3 pr-3" >
                                Reserve Now
                            </button>
						 <?php } else { ?>
						  <button disabled type="button" name="add" id="add_button" class="btn btn-default elevation-2 text-bold pl-3 pr-3" >
                                Reserve Now
                            </button>
						 <?php } } ?>
                        </div>
                        <div class="card-body">
                            <table id="datatables" class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th>Reservation ID</th>
                                        <th>Details</th>
                                        <th>Date Reserved</th>
                                        <th>Status</th>
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
                                <div class="form-group col-12 col-md-12">
                                    <div><h6><strong>REMINDERS:</strong></h6></div>
                                    <div class="text-justify">If you choose the Reservation for Cocktails, a meeting will be set for cocktail tasting. 
                                        If it's for Beverage, you can choose which beverages will be served at the event.</div>
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <label>Type</label>
                                    <select class="form-control" name="types" id="types" required >
                                        <option value="">Select</option>
                                        <option value="Cocktails">Cocktails</option>
                                        <option value="Beverage">Beverage</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <label>Event</label>
                                    <select class="form-control" name="events_id" id="events_id" disabled >
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
                                <div class="form-group col-12 col-md-4">
                                    <label>Other</label>
                                    <input type="text"class="form-control" name="other" id="other" disabled />
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Date</label>
                                    <div class="input-group date" id="event_dates" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input " data-toggle="datetimepicker" data-target="#event_dates" 
                                            name="event_date" id="event_date" disabled/>
                                        <div class="input-group-append" data-target="#event_dates" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Time</label>
                                    <div class="input-group date" id="event_times" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input " data-toggle="datetimepicker" data-target="#event_times" 
                                            name="event_time" id="event_time" disabled/>
                                        <div class="input-group-append" data-target="#event_times" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <label>Total Days</label>
                                    <input type="number" min="1" max="15" class="form-control" name="total_days" id="total_days" disabled />
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <label>Total Hours</label>
                                    <input type="number" min="1" max="23" class="form-control" name="total_hours" id="total_hours" disabled />
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <label># of Guests</label>
                                    <input type="number" min="1" class="form-control" name="guests" id="guests" disabled />
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Bartender?</label>
                                    <select class="form-control" name="bartender" id="bartender" disabled >
                                        <option value="">Select</option>
                                        <!-- <option value="Yes">Yes</option> -->
                                        <option value="No">No</option>
                                        <option value="1">1 bartender for 50 pax</option>
                                        <option value="2">2 bartender for 100 pax</option>
                                        <option value="3">3 bartender for 110-150 pax</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Event Place</label>
                                    <input type="text" class="form-control" name="event_place" id="event_place" disabled />
                                </div>
                                <div class="form-group col-12 col-md-12">
                                    <label>Event Address</label>
                                    <textarea class="form-control" name="address" id="address" disabled></textarea>
                                </div>
                                <div class="form-group col-12 col-md-12">
                                    <label>Notes</label>
                                    <textarea class="form-control" name="notes" id="notes" disabled></textarea>
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
                                        <li>Require to settle full payment if the event is <b>2days</b> before the event.</li>
                                        <li>10% of total package must be settle to <b>guarantee</b> booking/block the date 1-5 days settlement when the status will be ACCEPTED or CANCELLED.</li>
                                        <li>40% must be settled  atleast <b>15 days</b> before the event.</li>
                                        <li>50% remaining will be settle atleast <b>1 week</b> before the event.</li>
                                    </ol>
                                </div>
                                <div class="form-group col-12 col-md-12">
                                    <hr class="p-0 m-0">
                                </div>
                                <div class="form-group col-12 col-md-6 category_id">
                                    <label>Category</label>
                                    <select class="form-control" name="category_id" id="category_id"  >
                                        <option value="">Select</option>
                                        <?php
                                            $output = '';
                                            $rslt = fetch_all($connect,"SELECT * FROM $CATEGORY_TABLE WHERE status = 'Active' AND category != 'Cocktails' " ); //  
                                            foreach($rslt as $row)
                                            {
                                                $output .= '<option value="'.$row["id"].'">'.$row['category'].'</option>';
                                            }
                                            echo $output;
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-6 btn_add">
                                    <label class="d-none d-md-block">&nbsp;</label>
                                    <button type="button" name="btn_add" id="btn_add" class="btn btn-dark text-bold pl-3 pr-3 elevation-2 btn-block" >Add</button>
                                </div>
                                <div class="form-group col-12 col-md-6 product_id">
                                    <label>Product</label>
                                    <select class="form-control " name="product_id" id="product_id"  >
                                        <option value="">Select</option>
                                    </select>
									<div id="display-img"></div>

                                </div>
                                <div class="form-group col-12 col-md-6 product_id">
                                    <label>Quantity</label>
                                    <input type="number" min="1" class="form-control" name="quantity" id="quantity"  />
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
        <form method="post" id="forms_question">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label class="question text-lg"></label>
                    <div class="row">
                        <div class="form-group col-12 col-md-12 datatables_items1">
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
                        <div class="form-group col-12 col-md-6 details">
                            <label>Services Amount</label>
                            <input type="number" min="0" class="form-control services_amount" disabled />
                        </div>
                        <div class="form-group col-12 col-md-6 details">
                            <label>Total Amount</label>
                            <input type="text" class="form-control total_amounts" disabled/>
                        </div>
                        <div class="form-group col-12 payment_details">
                            <div class="row">
                                <div class="form-group col-12 ">
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
                                    <label>Sender Name</label>
                                    <input type="text" class="form-control" name="sender_name" id="sender_name" />
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Type</label>
                                    <select class="form-control" name="payment_server" id="payment_server"  >
                                        <option value="">Select</option>
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
                                    <label>Transaction/Reference No.</label>
                                    <input type="text" class="form-control" name="payment_no" id="payment_no" />
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Payment Amount</label>
                                    <input type="text" class="form-control payment_amount"  disabled />
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-12 payment_view">
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <input type="hidden" name="id" id="id"/>
                    <input type="hidden" name="status" id="status"/>
                    <input type="hidden" name="btn_action" id="btn_action1"/>
                    <input type="hidden" name="reservation_id" id="reservation_id"/>
                    <input type="hidden" name="payment_amount" id="payment_amount"/>
                    <button type="submit" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="btn_save" id="btn_save" ></button>
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
                            <label>Feedback</label>
                            <textarea name="feedback" id="feedback" class="form-control" required ></textarea>
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
<script>
$('#product_id').on('change', function() {
  $.ajax({
        url:"action.php",
        data: { "value":   this.value , "btn_action" : "fetch_image"},
        type: "post",
        success: function(data){
          $.each(JSON.parse(data), function(i, item) {
			$("#display-img").html("<br><img src='assets/products/" + item + "' width=200px>");
		});
        }
    });
});
</script>
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
        
        $('#events_id').change(function(){
            $('#other').attr('disabled','disabled').removeAttr('required','required');
            if ($(this).val() == 'Other')
            {
                $('#other').removeAttr('disabled','disabled').attr('required','required');
            }
        });
        
        $('#add_button').click(function(){
            $('#forms')[0].reset();
            $('.modal-title').html("<?php echo $title; ?> Form");
            $('#action').html("Book").removeClass('hidden');
            $('#action').val('<?php echo $title; ?>_add');
            $('#btn_action').val('<?php echo $title; ?>_add');

            $('#types').removeAttr("disabled","disabled").attr("required","required").val('');
            disables()
            $('.category_id').addClass("hidden");
            $('.product_id').addClass("hidden");
            $('.btn_add').addClass("hidden");
            $('.datatables_items').addClass("hidden");
            
            $('.datatables_quotation').addClass('hidden');
            $('.payment_view').addClass('hidden');

        });

        function disables()
        {
            $('#events_id').attr("disabled","disabled").removeAttr("required","required");
            $('#event_date').attr("disabled","disabled").removeAttr("required","required");
            $('#event_time').attr("disabled","disabled").removeAttr("required","required");
            $('#total_days').attr("disabled","disabled").removeAttr("required","required");
            $('#total_hours').attr("disabled","disabled").removeAttr("required","required");
            $('#guests').attr("disabled","disabled").removeAttr("required","required");
            $('#bartender').attr("disabled","disabled");
            $('#event_place').attr("disabled","disabled").removeAttr("required","required");
            $('#address').attr("disabled","disabled").removeAttr("required","required");
            $('#notes').attr("disabled","disabled");
            $('#other').attr('disabled','disabled').removeAttr('required','required');
        }

        function enables()
        {
            $('#events_id').removeAttr("disabled","disabled").attr("required","required");
            $('#event_date').removeAttr("disabled","disabled").attr("required","required");
            $('#event_time').removeAttr("disabled","disabled").attr("required","required");
            $('#total_days').removeAttr("disabled","disabled").attr("required","required");
            $('#total_hours').removeAttr("disabled","disabled").attr("required","required");
            $('#guests').removeAttr("disabled","disabled").attr("required","required");
            $('#bartender').removeAttr("disabled","disabled");
            $('#event_place').removeAttr("disabled","disabled").attr("required","required");
            $('#address').removeAttr("disabled","disabled").attr("required","required");
            $('#notes').removeAttr("disabled","disabled");
        }

        $('#types').change(function(){
            if ($(this).val() == '')
            {
                disables();
            }
            else
            {
                if ($(this).val() == 'Cocktails')
                {
                    disables();
                    $('#event_date').removeAttr("disabled","disabled").attr("required","required");
                    $('#event_time').removeAttr("disabled","disabled").attr("required","required");
                    $('#notes').removeAttr("disabled","disabled").attr("required","required");
                    $('.category_id').addClass("hidden");
                    $('.product_id').addClass("hidden");
                    $('.btn_add').addClass("hidden");
                    $('.datatables_items').addClass("hidden");
                    count = 1;
                }
                else
                {
                    enables();
                    $('.category_id').removeClass("hidden");
                    $('.product_id').removeClass("hidden");
                    $('.btn_add').removeClass("hidden");
                    $('.datatables_items').removeClass("hidden");
                    loadProduct('');
                    loadItems('add', '');
                    
                    $('#product').val('').trigger('change'); 
                    
                    deleteItem();
                }
            }
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
        
        $('#bartender').change(function(){
            $('#bartender_no').val(this.value);
        });
        
        $('#category_id').change(function(){
            loadProduct(this.value);
        });
        
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
                        // cocktailList(); // (?)
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

        function cocktailList() // (?)
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

        $(document).on('click', '.status', function(){
            var id = $(this).attr("id");
            var reservation_id = $(this).data("reservation_id");
            var status = $(this).data("status");
            $('#forms_question')[0].reset();
            $('#questionModal').modal('show');
            $('.modal-title').html('#'+reservation_id);
            $('#id').val(id);
            $('#reservation_id').val(reservation_id);
            $('.question').addClass('hidden');
            $('.datatables_items1').addClass("hidden");
            $('.bartender').addClass("hidden");
            $('.details').addClass("hidden");
            $('.payment_details').addClass("hidden");
            $('.payment_view').addClass("hidden");
            if (status == 'Pending')
            {
                $('#btn_action1').val("Reservation_status");
                $('#status').val("Cancelled");
                $('.question').html('Are you sure to cancel this reservation ?').removeClass('hidden');
                $('#btn_save').html("Yes, Cancel").removeClass("hidden"); 
            }
            else
            {
                $('.files').attr('src', 'assets/image-placeholder.png');
                $('#btn_action1').val("Reservation_payment");
                var amount = $(this).data("amount");
                $('.payment_amount').val(amount);
                $('#payment_amount').val(amount);
                $('.payment_details').removeClass("hidden");
                $('#status').val("Payment");
                $('#btn_save').html("Send Payment").removeClass("hidden"); 
            }
        });
        
        $(document).on('click', '.view', function(){
            var id = $(this).attr("id");
            var reservation_id = $(this).data("reservation_id");
            var types = $(this).data("types");
            var status = $(this).data("status");
            $('#questionModal').modal('show');
            $('.modal-title').html('#'+reservation_id);
            $('.bartender').addClass("hidden");
            $('.details').addClass("hidden");
            $('.payment_details').addClass("hidden");
            $('.payment_view').addClass("hidden");
            if (status == 'Pending' || status == 'Declined' || status == 'Cancelled' || status == 'Reserved')
            {
                $('.question').addClass('hidden');
                $('.datatables_items1').removeClass("hidden");
                $('#btn_save').addClass("hidden");
                
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data: { reservation_id:reservation_id, title: 'view', btn_action: 'items_list' },
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
            else if (status == 'Quoted' || status == 'Payment' || status == 'Completed')
            {
                var bartender = $(this).data("bartender");
                var services_amount = $(this).data("services_amount");
                var total_amount = $(this).data("total_amount");
                $('.question').addClass('hidden');
                $('#btn_save').addClass("hidden");
                $('.details').removeClass("hidden");
                $('.services_amount').val(services_amount);
                $('.total_amounts').val(total_amount);
                if (types == 'Cocktails')
                {
                    $('.datatables_items1').addClass("hidden");
                }
                else
                {
                    $('.datatables_items1').removeClass("hidden");
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
                
                if (bartender !== 0)
                {
                    $('.bartender').removeClass("hidden");
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
                if (status == 'Payment' || status == 'Completed')
                {
                    $('.payment_view').removeClass("hidden");
                    $.ajax({
                        url:"action.php",
                        method:"POST",
                        data: { reservation_id:reservation_id, btn_action: 'reservation_view_payment' },
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
            }
        });
    
        $(document).on('submit','#forms_question', function(event){
            event.preventDefault();
            if ($('#status').val() == 'Payment') 
            {
                if ($('#files').val() == '') 
                {
                    toastr.error('Please upload an image file.');
                    return;
                }
                if ($('#sender_name').val() == '' || $('#payment_server').val() == '' || $('#payment_no').val() == '') 
                {
                    toastr.error('Please enter payment details.');
                    return;
                }
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
                    $.ajax({
                        url:"action.php",
                        method:"POST",
                        // data:{id:$('#id').val(), status:$('#status').val(), btn_action:'<?php echo $title; ?>_status'},
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
                                sendData( { title: data.title, body: data.body, action: 'send', id: data.id } );
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
    
        $(document).on('click', '.feedback', function(){
            var id = $(this).attr("id");
            $('#id_rate').val(id);
            $('#rateModal').modal('show');
            reservation_id = $(this).data("reservation_id");
            $('.modal-title').html('#'+reservation_id);
            $('#action_rate').html("Rate");
            $('#action_rate').val('<?php echo $title; ?>_rate');
            $('#btn_action_rate').val('<?php echo $title; ?>_rate');
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

        var dataTable = $("#datatables").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "processing":true,
            "serverSide":true,
            "ordering": false,
            "order":[],
            "ajax":{
                url:"fetch/reservation.php?data=<?php echo $_GET['data'];?>",
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
            format: 'hh:00 A', // 6pm to 7am
            enabledHours: [18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7],
        });

    });
</script>
  
</body>
</html>