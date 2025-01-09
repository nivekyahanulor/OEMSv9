<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Service Offer';
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
                <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
                    <div class="col-12 col-md-4">
                        <div class="small-box bg-success elevation-2">
                            <div class="inner text-white">
                                <h3 id="booking"><?php echo get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE "); ?></h3>
                                <p class="text-bold">Total</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar-alt text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="small-box bg-secondary elevation-2 click_status" data-status="pending.php" style="cursor: pointer;">
                            <div class="inner text-white">
                                <h3 id="booking"><?php echo get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Pending' "); ?></h3>
                                <p class="text-bold">Pending</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar-plus text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="small-box bg-primary elevation-2 click_status" data-status="reserved.php" style="cursor: pointer;">
                            <div class="inner text-white">
                                <h3 id="booking"><?php echo get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Reserved' "); ?></h3>
                                <p class="text-bold">Reserved</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar-day text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="small-box bg-primary elevation-2 click_status" data-status="quoted.php" style="cursor: pointer;">
                            <div class="inner text-white">
                                <h3 id="booking"><?php echo get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Quoted' "); ?></h3>
                                <p class="text-bold">Quoted</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-clipboard-list text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="small-box bg-primary elevation-2 click_status" data-status="payment.php" style="cursor: pointer;">
                            <div class="inner text-white">
                                <h3 id="booking"><?php echo get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Payment' "); ?></h3>
                                <p class="text-bold">Payment</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-recycle text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="small-box bg-success elevation-2 click_status" data-status="completed.php" style="cursor: pointer;">
                            <div class="inner text-white">
                                <h3 id="booking"><?php echo get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Completed' "); ?></h3>
                                <p class="text-bold">Completed</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar-check text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="small-box bg-danger elevation-2 click_status" data-status="declined.php" style="cursor: pointer;">
                            <div class="inner text-white">
                                <h3 id="booking"><?php echo get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Declined' "); ?></h3>
                                <p class="text-bold">Declined</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar-times text-white"></i>
                            </div>
                        </div>
                    </div>
					
					  <div class="col-12 col-md-4">
                        <div class="small-box  elevation-2 click_status" style="background-color:gray;" data-status="meeting.php" style="cursor: pointer;">
                            <div class="inner text-white">
                                <h3 id="booking"><?php echo get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Meeting' "); ?></h3>
                                <p class="text-bold">Meeting</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-calendar-minus text-white"></i>
                            </div>
                        </div>
                    </div>


                <?php } else { ?>
                    <!-- <div class="col-12">
                        <div class="row">
                            <div class="col-12 text-center">
                                <img src="assets/logo.jpg" alt="Logo" class="img-circle elevation-3 " style="opacity: .8; height: 250px; ">
                            </div>
                            <div class="col-12 text-center mt-2">
                                <h2 >Tiyo Pau's</h2>
                            </div>
                            <div class="col-12 text-center mt-2">
                                <p> Address: Biñan, Philippines</p>
                            </div>
                            <div class="col-12 text-center mt-2">
                                <p> Contact: 0915 063 6457</p>
                            </div>
                            <div class="col-12 text-center mt-2">
                                <p> Email: tiyopaus@gmail.com</p>
                            </div>
                            <div class="col-12 text-center mt-2">
                                <p> Facebook: <a href="https://www.facebook.com/tiyopaus?mibextid=ZbWKwL" target="_blank">Tiyo Pau's</a></p>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-12 ">
                        <div class="row">
                            <?php
                                $output = '';
                                $result = fetch_all($connect,"SELECT * FROM $SETTINGS_TABLE  " );
                                foreach($result as $row)
                                {
                                    $output .= '
                                    <div class="col-md-12 col-lg-6 col-xl-4 ">
                                        <div class="card mb-2 bg-gradient-dark">
                                            <a data-magnify="gallery" class="" data-caption="'.$row["image"].'" data-group="" href="'.$row["image"].'">
                                                <img class="card-img-top " style="height: 300px; cursor: pointer;" src="'.$row["image"].'" alt="Valid Photo">
                                            </a>';
                                    if (!empty($row["title"]) || !empty($row["description"]))
                                    {
                                        $output .= '
                                                <div class="card-footer bg-white text-dark">';
                                        if (!empty($row["title"]))
                                        {
                                            $output .= '<h5 class="card-title" >'.$row["title"].'</h5>';
                                        }
                                        if (!empty($row["description"]))
                                        {
                                            $output .= '<p class="card-text pt-1">'.$row["description"].'</p>';
                                        }
                                        $output .= '
                                                </div>';
                                    }
                                    $output .= '
                                        </div>
                                    </div>';
                                }
                                echo $output;
                            ?>
                        </div>
                    </div>
                    
                <?php } ?>
            </div>
        </div>
    </div>

</div>

<?php

include('footer.php');

?>
  
<script>
    $(function () {

        <?php if ($_SESSION["user_type"] == 'Superadmin') {?>
            $('.click_status').click(function(){
                var status = $(this).data("status");
                window.location.href = status;
            }); 
        <?php } ?>
    });
</script>

</body>
</html>