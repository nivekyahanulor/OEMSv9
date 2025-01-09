
    <!-- Main Footer -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">
            Tiyo Pau's | &copy; Copyright 2023
        </div>
        &nbsp;
    </footer>

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="assets/plugins/toastr/toastr.min.js"></script>

<!-- Select2 -->
<script src="assets/plugins/select2/js/select2.full.min.js"></script>

<!-- bs-custom-file-input -->
<script src="assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="assets/plugins/jszip/jszip.min.js"></script>
<script src="assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<script src="assets/plugins/fullcalendar/main.js"></script>

<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>

<!--jQuery Magnify-->
<script src="assets/js/jquery.magnify.js"></script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<!--SOCKET IO-->
<script src="https://cdn.socket.io/socket.io-3.0.0.js"></script>

<?php if ($_SESSION["user_type"] == 'Superadmin') { ?>
<div id="profileModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" id="forms_profile">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title">Profile</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-12">
                            <label>Email</label>
                            <input type="email" name="email_profile" id="email_profile" class="form-control " value="<?php echo $_SESSION['user_name']; ?>" disabled />
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Password</label>
                            <input type="password" name="password_profile" id="password_profile" class="form-control " placeholder="Password" required />
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Retype Password</label>
                            <input type="password" name="retype_profile" id="retype_profile" class="form-control " placeholder="Retype Password" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="btn_action" id="btn_action_profile" value="profile"/>
                    <button type="submit" class="btn btn-dark pl-3 pr-3 text-bold pl-3 elevation-2" name="action_profile" id="action_profile" >Save</button>
                    <button type="button" class="btn btn-default pl-3 pr-3 text-bold pl-3 elevation-2" data-dismiss="modal" >Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(function () {

        // add notify about due date

        toastr.options.onclick = function(e) 
        { 
            // window.location.href = "notifications.php";
            // alert(this.data.Message); 
            if (this.data)
            {
                seen(this.data.id);
            }
        }

        function seen(id)
        {
            $.ajax({
                url:"action.php",
                method:"POST",
                data: { btn_action: 'notification_seen', id: id },
                dataType:"json",
                success:function(data)
                {
                    // window.location.href = "notifications.php";
                    loadCount();
                },error:function()
                {
                    // toastr.error('Please try again.');
                }
            })
        }

        loadCount();
        const socket = io('<?php echo $server_link; ?>');
        socket.on('message', text => 
        {
            var json = JSON.parse(text);
            // console.log(json);
            loadCount();
            
            if (json.action == 'send')
            {
                // $(document).Toasts('create', {
                //     autohide: true,
                //     delay: 1500,
                //     class: 'bg-info',
                //     title: json.title,
                //     // title: 'Toast Title',
                //     // subtitle: 'Subtitle',
                //     // body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
                //     body: json.body
                // })
                
                toastr.info(json.body, json.title, 
                {
                    "data": { "id": json.id}
                });
            }
        });

        function loadCount()
        {
            $.ajax({
                url:"action.php",
                method:"POST",
                data: { btn_action: 'notification_count' },
                dataType:"json",
                success:function(data)
                {
                    $('.notifications').html(data.count);
                    $('.list_notifications').html(data.list);
                },error:function()
                {
                    // toastr.error('Please try again.');
                }
            })
        }
        
        $('.btn_profile').click(function(){
            $('.modal-title').html("Profile");
        });
    
        $(document).on('submit','#forms_profile', function(event){
            event.preventDefault();
            if ($('#password_profile').val() == $('#retype_profile').val())
            {
                $('#action_profile').attr('disabled','disabled');
                var form_data = $(this).serialize();
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data:form_data,
                    dataType:"json",
                    success:function(data)
                    {
                        $('#action_profile').attr('disabled', false);
                        if (data.status == true)
                        {
                            $('#forms_profile')[0].reset();
                            $('#profileModal').modal('hide');
                            toastr.success(data.message);
                        }
                        else 
                        {
                            toastr.error(data.message);
                        }
                    },error:function()
                    {
                        $('#action_profile').attr('disabled', false);
                        toastr.error('Please try again.');
                    }
                })
            }
            else
            {
                toastr.error('Password does not match.');
            }
        });
        
        $(document).on('click', '.click_notif', function(){
            var id = $(this).attr("id");
            seen(id);
        });

    });
</script>
<?php } else { ?>

<div id="profileModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" id="forms_profile">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title">Profile</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-4 ">
                            <label>Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control " placeholder="Last Name" disabled />
                        </div>
                        <div class="form-group col-12 col-md-4 ">
                            <label>First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control " placeholder="First Name" disabled />
                        </div>
                        <div class="form-group col-12 col-md-4 ">
                            <label>Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" class="form-control " placeholder="Middle Name" disabled />
                        </div>
                        <div class="form-group col-12 col-md-8">
                            <label>Email</label>
                            <input type="email" name="email" id="email" class="form-control " required />
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <label>Contact</label>
                            <input type="number" min="0" name="contact" id="contact" class="form-control " placeholder="Contact" required/>
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <label>Address</label>
                            <textarea name="address" id="address_profile" class="form-control " placeholder="Address" required ></textarea>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Password</label>
                            <input type="password" name="password_profile" id="password_profile" class="form-control " placeholder="Password" required />
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label>Retype Password</label>
                            <input type="password" name="retype_profile" id="retype_profile" class="form-control " placeholder="Retype Password" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="btn_action" id="btn_action_profile" value="profile_edit"/>
                    <button type="submit" class="btn btn-dark pl-3 pr-3 text-bold pl-3 elevation-2" name="action" id="action_profile" >Save</button>
                    <button type="button" class="btn btn-default pl-3 pr-3 text-bold pl-3 elevation-2" data-dismiss="modal" >Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(function () {

        // add notify about due date

        toastr.options.onclick = function(e) 
        { 
            // window.location.href = "notifications.php";
            // alert(this.data.Message); 
            if (this.data)
            {
                seen(this.data.id);
            }
        }

        function seen(id)
        {
            $.ajax({
                url:"action.php",
                method:"POST",
                data: { btn_action: 'notification_seen', id: id },
                dataType:"json",
                success:function(data)
                {
                    // window.location.href = "notifications.php";
                    loadCount();
                },error:function()
                {
                    // toastr.error('Please try again.');
                }
            })
        }

        loadCount();
        const socket = io('<?php echo $server_link; ?>');
        socket.on('message', text => 
        {
            var json = JSON.parse(text);
            // console.log(json);
            loadCount();
            
            if (json.action == 'customer')
            {
                // $(document).Toasts('create', {
                //     autohide: true,
                //     delay: 1500,
                //     class: 'bg-info',
                //     title: json.title,
                //     // title: 'Toast Title',
                //     // subtitle: 'Subtitle',
                //     // body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
                //     body: json.body
                // })
                
                toastr.info(json.body, json.title, 
                {
                    "data": { "id": json.id}
                });
            }
        });

        function loadCount()
        {
            $.ajax({
                url:"action.php",
                method:"POST",
                data: { btn_action: 'notification_count' },
                dataType:"json",
                success:function(data)
                {
                    $('.notifications').html(data.count);
                    $('.list_notifications').html(data.list);
                },error:function()
                {
                    // toastr.error('Please try again.');
                }
            })
        }
        
        $(document).on('click', '.click_notif', function(){
            var id = $(this).attr("id");
            seen(id);
        });
        
        $('.btn_profile').click(function(){
            $('.modal-title').html("Profile");
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
        });
        
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
                            $('#profileModal').modal('hide');
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
        
    });
</script>

<?php } ?>