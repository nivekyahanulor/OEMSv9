<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mobile Cart Reservation System</title>
        <link rel="icon" href="assets/logo.jpg" type="image/ico">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
    
    <style type="text/css">
        .hidden
        {
            display:none;
        }
        #home, #about, #gallery
        {
            height: 100vh;
        }
        @media (max-width: 1024px)
        {
            .home_text
            {
                margin-top: 0px!important;
            }
        }
        @media (max-width: 858px)
        {
            #about, #gallery
            {
                /* height: auto!important; */
                height: 100%!important;
                /* margin-bottom: 4rem; */
            }
        }
        @media (max-width: 510px)
        {
            .home_text
            {
                font-size: 2.5rem!important;
            }
        }
        @media (max-width: 400px)
        {
            .home_text
            {
                font-size: 2rem!important;
            }
        }
        @media (max-width: 350px)
        {
            .home_text
            {
                font-size: 1.5rem!important;
            }
        }
        body 
        {
            /* display: flex; */
            background: url("assets/images/background.jpg") no-repeat fixed center;
            background-size: cover;
        }
        #myBtn 
        {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 30px;
            z-index: 99;
            /* font-size: 18px; */
            border: none;
            outline: none;
            /* background-color: red; */
            /* color: white; */
            cursor: pointer;
            /* padding: 20px; */
            border-radius: 100px;
            width: 50px;
            height: 50px;
        }
        .home_text
        {
            margin-top: -16rem;
        }
    </style>
</head>
<body class="hold-transition bg-gray layout-top-nav">
    
<div class="wrapper">
  <nav class="main-header navbar navbar-expand-md border-0 navbar-light navbar-white text-bold text-lg w-100" style="background-color: transparent !important;">
    <div class="container mr-0 ml-0 " style="max-width: 100% !important">

      <button class="navbar-toggler order-1 border-light " type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <!-- <span class="navbar-toggler-icon"></span> -->
         <i class="fa fa-bars text-white p-2"></i>
      </button>

      <div class="collapse navbar-collapse order-3 text-center" id="navbarCollapse">
        <ul class="navbar-nav ml-auto" style="letter-spacing: 2px;">
          <!-- <li class="nav-item ">
            <a href="#home" class="nav-link text-white btn_nav">Home</a>
          </li> -->
          <li class="nav-item">
            <a href="#gallery" class="nav-link text-white btn_nav">Gallery</a>
          </li>
          <li class="nav-item">
            <a href="#services" class="nav-link text-white btn_nav">Services</a>
          </li>
          <li class="nav-item">
            <a href="#about" class="nav-link text-white btn_nav">About</a>
          </li>
          <li class="nav-item">
            <a href="#contact" class="nav-link text-white btn_nav">Contact</a>
          </li>
        </ul>
        
      </div>

    </div>
  </nav>
</div>

<section id="home" class="d-flex align-items-center justify-content-between">
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-lg-6 ">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center ">
                        <div class="display-4 text-bold text-wrap text-center home_text" style="width: 80%; line-height: 1.5; letter-spacing: 6px; ">MAKE YOUR EVENT EXTRA SPECIAL</div>
                    </div>
                    <div class="col-12 d-flex justify-content-center mt-4">
                        <div class="col-12 col-lg-8">
                            <!-- <div class="row">
                                <div class="col-6 text-center ">
                                    <button type="button" class="btn btn-default text-bold text-lg pl-3 pr-3 elevation-2 mb-2" 
                                    name="btn_login" id="btn_login" data-toggle="modal" data-target="#loginModal" >SIGNIN</button>
                                </div>
                                <div class="col-6 text-center">
                                    <button type="button" class="btn btn-default text-bold text-lg pl-3 pr-3 elevation-2 mb-2" 
                                        name="btn_register" id="btn_register" data-toggle="modal" data-target="#registerModal" >SIGNUP</button>
                                </div>
                            </div> -->
                            <div class="card card-dark elevation-2">
                                <div class="card-body">
                                    <form method="post" id="forms">
                                        <div class="row">
                                            <div class="form-group col-12 col-md-12">
                                                <label class="text-dark">Email</label>
                                                <input type="email" name="email" id="email" class="form-control " placeholder="Email" required />
                                            </div>
                                            <div class="form-group col-12 col-md-12">
                                                <label class="text-dark">Password</label>
                                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required />
                                            </div>
                                            <div class="col-12">
                                                <input type="hidden" name="btn_action" id="btn_action" value="login" />
                                                <button type="submit" class="btn btn-dark btn-block text-bold pl-3 pr-3 elevation-2" name="action" id="action" >Login</button>
                                            </div>
                                        </div>
                                  
									  <div class="mt-2 col-12">
										 <input type="checkbox" required>  <a href="#"  data-toggle="modal" data-target="#termscondition">By logging in, you confirm that you have read and understood our Terms and Conditions.</a>
									  </div>
                                    <div class="mt-2 col-12">
                                        <a href="#" name="btn_register" id="btn_register" data-toggle="modal" data-target="#registerModal">No account? Sign up here...</a>
                                    </div>
                                    <div class="mt-2 col-12">
                                        <a href="#" name="btn_forgot" id="btn_forgot" data-toggle="modal" data-target="#forgotModal">Forgot Password?</a>
                                    </div>
									</form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="gallery" class="d-flex align-items-center justify-content-center" >
    <div class="col-12 col-sm-8 col-lg-6 d-flex align-items-center justify-content-center mt-5 mb-5">
        <div class="row ">
            <div class="col-6">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="col-12 col-md-8 m-2">
                        <img class="card-img-top elevation-2" src="assets/images/1.jpg" alt="Picture" style="height: 100%; width: 100%!important; border-radius: 12px;">
                    </div>
                    <div class="col-12 col-md-8 m-2">
                        <img class="card-img-top elevation-2" src="assets/images/2.jpg" alt="Picture" style="height: 100%; width: 100%!important; border-radius: 12px;">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="col-12 col-md-8 m-2">
                        <img class="card-img-top elevation-2" src="assets/images/3.jpg" alt="Picture" style="height: 100%; width: 100%!important; border-radius: 12px;">
                    </div>
                    <div class="col-12 col-md-8 m-2">
                        <img class="card-img-top elevation-2" src="assets/images/4.jpg" alt="Picture" style="height: 100%; width: 100%!important; border-radius: 12px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="services" class="bg-white d-flex align-items-center justify-content-center" >
    <div class="col-12 col-sm-8 col-lg-6 ">
        <div class="h2 text-bold text-center pt-5">Our Services</div>
        <div class="text-lg mt-sm-5 text-justify p-3 pb-5" style="line-height: 2; letter-spacing: 1px;">
            At TIYO PAU'S, we offer a full range of beverage services tailored to your event needs:

            Coffee Bar: Freshly brewed coffee to keep your guests energized.

            Milk Tea & Fruit Tea Station: A variety of flavors to satisfy tea lovers.

            Cocktail Bar: Handcrafted cocktails to elevate your celebrations.

            From small parties to large events, we provide professional setup and friendly service to ensure your guests enjoy every sip. 
            Let us take care of the drinks while you enjoy the occasion!
        </div>
    </div>
</div>

<section id="about" class="d-flex align-items-center justify-content-center" >
    <div class=" col-12 col-sm-8 col-lg-5 mt-5 mb-5">
        <div class="card elevation-2 " style="background-color: #3B270C;" > 
            <div class="card-header text-center p-4"  >
                <a class="h2 text-bold text-white ">About Us</a>
            </div>
            <div class="card-body text-lg text-white p-5 text-dark text-justify" style="line-height: 2; letter-spacing: 1px;">
                <div class="">
                    TIYO PAU'S offers a diverse selection of drinks for every event, including coffee, milk tea, fruit tea, and cocktails. 
                    We pride ourselves on delivering high-quality beverages that add flavor and fun to any occasion. 
                    Whether it's a small gathering or a big celebration, we’re here to make your event memorable.
                </div>
            </div>
        </div>
    </div>
</section>

<div id="contact" class="bg-white ">
    <div class="pl-sm-5 pt-4 pb-3 mr-4 ml-4 ">
        <div class="h2 text-bold">Contact Us</div>
        <div class="row m-0 mt-4">
            <div class="col-12 col-lg-6">
                <div class="text-lg">
                    <i class="fa fa-map-marker-alt mr-2"></i>
                    <span class="text-bold">Address:</span> 033A Old National Hi-Way, Sto. Niño, Biñan, 4024 Laguna
                </div>
                <div class="text-lg">
                    <i class="fa fa-mobile-alt mr-2"></i>
                    <span class="text-bold">Mobile #:</span> 09150636457
                </div>
                <div class="text-lg">
                    <i class="far fa-envelope mr-2"></i>
                    <span class="text-bold">Email:</span> tiyopaus@gmail.com
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="text-lg">
                    <i class="fab fa-facebook-square mr-2"></i>
                    <span class="text-bold">Facebook:</span> 
                    <a href="https://www.facebook.com/tiyopaus?mibextid=ZbWKwL">Tiyo Pau's</a>
                </div>
                <div class="text-lg">
                    <i class="fab fa-instagram mr-2"></i>
                    <span class="text-bold">Instagram:</span> 
                    <a href="https://www.instagram.com/tiyopaus?igsh=MW0zdjVja2wwZ2xvcg==">@tiyopaus</a>
                </div>
            </div>
        </div>
    </div>
</div>

<button onclick="topFunction()" id="myBtn" title="Go to top" class="btn btn-light text-center elevation-3">
    <i class="fa fa-arrow-up"></i>
</button>

<footer style="padding: 20px 0 20px 0;  width: 100%; background-color: #3B270C;" class="text-center text-md"> 
    Tiyo Pau's | &copy; Copyright 2023 
    <!-- <a href="https://www.facebook.com/tiyopaus" class="text-white" target="_blank"><i class="fab fa-facebook fa-1x mr-1 text-white"></i></a> -->
</footer>
<!-- position: fixed; bottom: 0; -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="assets/plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>

<!-- <div id="loginModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog">
        <form method="post" id="forms">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-12">
                            <input type="email" name="email" id="email" class="form-control " placeholder="Email" required />
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between ">
                    <a href="#" name="btn_forgot" id="btn_forgot" data-toggle="modal" data-target="#forgotModal">Forgot Password</a>
                    <button type="button" class="btn btn-dark text-bold pl-3 pr-3 elevation-2 float-left" name="btn_forgot" id="btn_forgot" data-toggle="modal" data-target="#forgotModal" >Forgot Password</button>
                    <div class="">
                        <input type="hidden" name="btn_action" id="btn_action"/>
                        <button type="submit" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="action" id="action" >Login</button>
                        <button type="button" class="btn btn-default text-bold pl-3 pr-3 elevation-2 ml-1" data-dismiss="modal" >Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div> -->

<div id="registerModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog">
        <form method="post" id="forms_register">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-4 register">
                            <label class="text-dark">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control " placeholder="Last Name" />
                        </div>
                        <div class="form-group col-12 col-md-4 register">
                            <label class="text-dark">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control " placeholder="First Name" />
                        </div>
                        <div class="form-group col-12 col-md-4 register">
                            <label class="text-dark">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" class="form-control " placeholder="Middle Name" />
                        </div>
                        <div class="form-group col-12 col-md-6 register">
                            <label class="text-dark">Email</label>
                            <input type="email" name="email" id="email_register" class="form-control " placeholder="Email" />
                        </div>
                        <div class="form-group col-12 col-md-6 register">
                            <label class="text-dark">Contact</label>
                            <input type="number" name="contact" id="contact" class="form-control " placeholder="Contact" maxlength = "11" 
                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                        </div>
                        <div class="form-group col-12 col-md-12 register">
                            <label class="text-dark">Address</label>
                            <textarea name="address" id="address" class="form-control " placeholder="Address" ></textarea>
							<a href="#" data-toggle="modal" data-target="#termscondition"><input type="checkbox" required> You confirm that you have read, understood, and accepted these terms by signing up or creating an account.</a>

                        </div>
                        <div class="form-group col-12 col-md-12  email_code">
                            <i class="text-dark">NOTE: Please check your email, we sent an email code.</i>
                            <label class="text-dark">Email Code</label>
                            <input type="number" min="1" name="email_code" id="email_code" maxlength = "4" 
                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
                            class="form-control mt-2" placeholder="Email Code" />
                        </div>
                        <div class="form-group col-12 col-md-6 password_register">
                            <label class="text-dark">Password</label>
                            <input type="password" name="password" id="password_register" class="form-control " placeholder="Password" />
                        </div>
                        <div class="form-group col-12 col-md-6 password_register">
                            <label class="text-dark">Retype Password</label>
                            <input type="password" name="retype" id="retype" class="form-control " placeholder="Retype Password" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <input type="hidden" name="steps" id="steps_register"/>
                    <input type="hidden" name="btn_action" id="btn_action_register"/>
                    <button type="submit" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="action" id="action_register" >Register</button>
                    <button type="button" class="btn btn-default text-bold pl-3 pr-3 elevation-2" data-dismiss="modal" >Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="forgotModal" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog">
        <form method="post" id="forms_forgot">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-12 col-md-12 forgot">
                            <label class="text-dark">Email</label>
                            <input type="email" name="email" id="email_forgot" class="form-control " placeholder="Email" />
							<br>
							<a href="#"  data-toggle="modal" data-target="#termscondition"><input type="checkbox" required> I agree to Terms and Conditions </a>
                        </div>

                        <div class="form-group col-12 col-md-12 email_code_forgot">
                            <label class="text-dark">Email Code</label>
                            <input type="number" min="1" name="email_code" id="email_code_forgot" 
                                maxlength = "4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
                                class="form-control " placeholder="Email Code" />
                        </div>
                        <div class="form-group col-12 col-md-6 password_forgot">
                            <label class="text-dark">Password</label>
                            <input type="password" name="password" id="password_forgot" class="form-control " placeholder="Password" />
                        </div>
                        <div class="form-group col-12 col-md-6 password_forgot">
                            <label class="text-dark">Retype Password</label>
                            <input type="password" name="retype" id="retype_forgot" class="form-control " placeholder="Retype Password" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <input type="hidden" name="steps" id="steps_forgot"/>
                    <input type="hidden" name="btn_action" id="btn_action_forgot"/>
                    <button type="submit" class="btn btn-dark text-bold pl-3 pr-3 elevation-2" name="action" id="action_forgot" >Forgot</button>
                    <button type="button" class="btn btn-default text-bold pl-3 pr-3 elevation-2" data-dismiss="modal" >Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="termscondition" class="modal fade" data-backdrop="static" data-keyword="false" role="dialog" aria-modal="true">
    <div class="modal-dialog">
        <form method="post" id="forms_forgot">
            <div class="modal-content" >
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"><b>Terms and Conditions</b></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div style="color:#000000;">
						
						Tiyo Pau's Mobile Cart provides memorable events that start with our qualitydrinks, made with passion and love, and we are committed to offering great
						drinks that make every occasion unforgettable. Our delicious drinks are
						perfect for small gatherings as well as large celebrations. 
						
						<br><br>1. Product
						The provided picture of the product on this site is owned by Tiyo Pau'sowner, and its only purpose is to represent the product so that the customer
						can have a glimpse of the idea and description about the product that theywill
						receive. 
						
						<br><br>2. Date of Reservation
						The date for reservation has a minimum of 2 days allotted for theTiyoPau's to have preparation before the event. The customer can only set adatein the days that have passed 2 days (ex., Aug. 1 is the date now, Aug. 2-3is
						not an approved date for reservation, Aug. 4, and so on is the approveddatefor reservation). 
						
						<br><br>3. Payment
						The Tiyo Pau's accept online payment, the rules for the payment varydepending on the date reserved. 1. Require to settle full payment if the event atleast 2 days before the event. 2. 10% of total package must be settle to guarantee booking/block the date1- 5 days settlement when the status will be ACCEPTED or CANCELLED. 3. 40% must be settled atleast 15 days before the event. 4. 50% remaining will be settle atleast 1 
						</div>				
					</div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-default text-bold pl-3 pr-3 elevation-2" data-dismiss="modal" >Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Get the button
    let mybutton = document.getElementById("myBtn");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() 
    {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) 
        {
            mybutton.style.display = "block";
        } else 
        {
            mybutton.style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() 
    {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
    $(function () { 
        
        $('.btn_nav').click(function(){
            $('#navbarCollapse').removeClass('show');
        });

        $('#btn_login').click(function(){
            $('#forms')[0].reset();
            $('.modal-title').html("Signin");
            $('#action').html("Signin");
            $('#action').val('login');
            $('#btn_action').val('login');
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
                        window.location.href = "dashboard.php";
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

        $('#btn_register').click(function(){
            $('#forms_register')[0].reset();
            $('.modal-title').html("Signup");
            $('#action_register').html("Signup");
            $('#action_register').val('register');
            $('#btn_action_register').val('register');
            
            $('#steps_register').val('1');
            
            $('.register').removeClass('hidden');
            $('.password_register').addClass('hidden');
            $('.email_code').addClass('hidden');
            
            $('#last_name').attr('required','required');
            $('#first_name').attr('required','required');
            $('#email_register').attr('required','required');
            $('#contact').attr('required','required');
            $('#address').attr('required','required');
            
            $('#email_code').removeAttr('required','required');

            $('#password_register').removeAttr('required','required');
            $('#retype').removeAttr('required','required');
        });

        $(document).on('submit','#forms_register', function(event){
            event.preventDefault();
            if ($('#steps_register').val() == '3')
            {
                if ($('#password_register').val() == $('#retype').val())
                {
                $('#action_register').attr('disabled','disabled');
                var form_data = $(this).serialize();
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data:form_data,
                    dataType:"json",
                    success:function(data)
                    {
                        $('#action_register').attr('disabled', false);
                        if (data.status == true)
                        {
                            $('#registerModal').modal('hide');
                            toastr.success(data.message);
                        }
                        else 
                        {
                            toastr.error(data.message);
                        }
                    },error:function()
                    {
                        $('#action_register').attr('disabled', false);
                        toastr.error('Please try again.');
                    }
                })
                }
                else
                {
                    toastr.error('Password does not match.');
                }
            }
            else
            {
                $('#action_register').attr('disabled','disabled');
                var form_data = $(this).serialize();
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data:form_data,
                    dataType:"json",
                    success:function(data)
                    {
                        $('#action_register').attr('disabled', false);
                        if (data.status == true)
                        {
                            if ($('#steps_register').val() == '1')
                            {
                                $('.register').addClass('hidden');
                                $('.email_code').removeClass('hidden');
                                $('#last_name').removeAttr('required','required');
                                $('#first_name').removeAttr('required','required');
                                $('#email_register').removeAttr('required','required');
                                $('#contact').removeAttr('required','required');
                                $('#address').removeAttr('required','required');
                                $('#email_code').attr('required','required');
                                $('#steps_register').val('2');
                                $('#action_register').html("Verify");
                            }
                            // else if ($('#steps_register').val() == '2')
                            // {
                            //     $('.email_code').addClass('hidden');
                            //     $('.password_register').removeClass('hidden');
                            //     $('#email_code').removeAttr('required','required');
                            //     $('#password_register').attr('required','required');
                            //     $('#retype').attr('required','required');
                            //     $('#steps_register').val('3');
                            //     $('#action_register').html("New Password");
                            // }
                            else
                            {
                                // $('#registerModal').modal('hide');
                                // toastr.success(data.message);
                                $('.email_code').addClass('hidden');
                                $('.password_register').removeClass('hidden');
                                $('#email_code').removeAttr('required','required');
                                $('#password_register').attr('required','required');
                                $('#retype').attr('required','required');
                                $('#steps_register').val('3');
                                $('#action_register').html("New Password");
                            }
                        }
                        else 
                        {
                            toastr.error(data.message);
                        }
                    },error:function()
                    {
                        $('#action_register').attr('disabled', false);
                        toastr.error('Please try again.');
                    }
                })
            }
        });

        $('#btn_forgot').click(function(){
            // $('#loginModal').modal('hide');
            $('#forms_forgot')[0].reset();
            $('.modal-title').html("Forgot Password");
            $('#action_forgot').html("Forgot");
            $('#action_forgot').val('forgot');
            $('#btn_action_forgot').val('forgot');
            
            $('#steps_forgot').val('1');
            
            $('.forgot').removeClass('hidden');
            $('.email_code_forgot').addClass('hidden');
            $('.password_forgot').addClass('hidden');

            $('#email_forgot').attr('required','required');
            
            $('#email_code_forgot').removeAttr('required','required');

            $('#password_forgot').removeAttr('required','required');
            $('#retype_forgot').removeAttr('required','required');
        });
    
        $(document).on('submit','#forms_forgot', function(event){
            event.preventDefault();
            if ($('#steps_forgot').val() == '3')
            {
                if ($('#password_forgot').val() == $('#retype_forgot').val())
                {
                    $('#action_forgot').attr('disabled','disabled');
                    var form_data = $(this).serialize();
                    $.ajax({
                        url:"action.php",
                        method:"POST",
                        data:form_data,
                        dataType:"json",
                        success:function(data)
                        {
                            $('#action_forgot').attr('disabled', false);
                            if (data.status == true)
                            {
                                $('#forgotModal').modal('hide');
                                toastr.success(data.message);
                            }
                            else 
                            {
                                toastr.error(data.message);
                            }
                        },error:function()
                        {
                            $('#action_forgot').attr('disabled', false);
                            toastr.error('Please try again.');
                        }
                    })
                }
                else
                {
                    toastr.error('Password does not match.');
                }
            }
            else
            {
                $('#action_forgot').attr('disabled','disabled');
                var form_data = $(this).serialize();
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data:form_data,
                    dataType:"json",
                    success:function(data)
                    {
                        $('#action_forgot').attr('disabled', false);
                        if (data.status == true)
                        {
                            if ($('#steps_forgot').val() == '1')
                            {
                                $('.forgot').addClass('hidden');
                                $('.email_code_forgot').removeClass('hidden');
                                $('#email_forgot').removeAttr('required','required');
                                $('#email_code_forgot').attr('required','required');

                                $('#action_forgot').html("Verify");
                                $('#steps_forgot').val('2');
                            }
                            else
                            {
                                $('.email_code_forgot').addClass('hidden');
                                $('#email_code_forgot').removeAttr('required','required');

                                $('.password_forgot').removeClass('hidden');
                                $('#password_forgot').attr('required','required');
                                $('#retype_forgot').attr('required','required');

                                $('#action_forgot').html("New Password");
                                $('#steps_forgot').val('3');
                            }
                        }
                        else 
                        {
                            toastr.error(data.message);
                        }
                    },error:function()
                    {
                        $('#action_forgot').attr('disabled', false);
                        toastr.error('Please try again.');
                    }
                })
            }
        });

    });
</script>
</body>
</html>
