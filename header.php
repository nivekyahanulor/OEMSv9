<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mobile Cart Reservation System</title>
    <link rel="icon" href="assets/logo.jpg" type="image/ico">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    
    <link rel="stylesheet" href="assets/plugins/fullcalendar/main.css">

    <link rel="stylesheet" href="assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">

    <!--jQuery Magnify-->
    <link rel="stylesheet" href="assets/css/jquery.magnify.css">
    
    <style>
        .hidden 
        {
            display: none;
        }
        .image-upload > input 
        {
            visibility:hidden;
            width: 0;
            height: 0;
        }
        .magnify-button-close, 
        .magnify-button-maximize, 
        .magnify-button-rotateRight, 
        .magnify-button-actualSize, 
        .magnify-button-next, 
        .magnify-button-fullscreen, 
        .magnify-button-prev, 
        .magnify-button-zoomIn, 
        .magnify-button-zoomOut
        {
            color: white;
        }
        
        .rate 
        {
            float: left;
            height: 46px;
            padding: 0 10px;
        }
        .rate:not(:checked) > input 
        {
            position:absolute;
            top:-9999px;
        }
        .rate:not(:checked) > label 
        {
            float:right;
            width:1em;
            overflow:hidden;
            white-space:nowrap;
            cursor:pointer;
            font-size:40px;
            color:#ccc;
        }
        .rate:not(:checked) > label:before 
        {
            content: '★ ';
        }
        .rate > input:checked ~ label 
        {
            color: #ffc700;    
        }
        .rate:not(:checked) > label:hover,
        .rate:not(:checked) > label:hover ~ label 
        {
            color: #deb217;  
        }
        .rate > input:checked + label:hover,
        .rate > input:checked + label:hover ~ label,
        .rate > input:checked ~ label:hover,
        .rate > input:checked ~ label:hover ~ label,
        .rate > label:hover ~ input:checked ~ label 
        {
            color: #c59b08;
        }
        
    </style>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item align-items-center d-none d-sm-flex">
                <div class="text-lg font-weight-bold">Mobile Cart Reservation</div>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">

            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link " data-toggle="dropdown" href="#" style="font-size:20px;">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-danger navbar-badge notifications" style="font-size:15px;">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header "><span class="notifications">0</span> Notifications</span>
                    <div class="dropdown-divider"></div>
                    <div class="list_notifications"></div>
                </div>
            </li>

            <?php if ($_SESSION["user_type"] == "Superadmin") { ?>
                <li class="nav-item dropdown">

                    <a class="nav-link text-bold" data-toggle="dropdown" href="#">
                        <?php echo $_SESSION["user_name"]; ?>
                    </a>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer btn_profile" data-toggle="modal" data-target="#profileModal" >Profile</a>

                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="dropdown-item dropdown-footer text-danger" name="btn_profile" id="btn_profile">Logout</a>

                    </div>
                </li>
            <?php } ?>
        </ul>

  </nav>