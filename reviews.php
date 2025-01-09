<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Reviews';
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
                <?php //if ($_SESSION["user_type"] !== 'Superadmin') {?>
                    <div class="col-12 ">
                        <div class="row justify-content-center ">
                            <div class="col-12 col-md-6">
                                <div class="card bg-dark">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-3 text-center">
                                                <div class="text-bold display-4 review_ratings">0.0</div>
                                                <div class="review_star mb-2">
                                                    <!-- <span class="text-white">&starf;</span>
                                                    <span class="text-white">&starf;</span>
                                                    <span class="text-white">&starf;</span>
                                                    <span class="text-white">&starf;</span>
                                                    <span class="text-white">&star;</span> -->
                                                </div>
                                                <div>Total Ratings</div>
                                            </div>
                                            <div class="col-12 col-md-9">
                                                <div class="row ">
                                                    <div class="col-1 text-center">
                                                        <span class="text-md">5</span>
                                                    </div>
                                                    <div class="col-11 pb-2">
                                                        <div class="m-0 p-0 mt-2">
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-primary review_five" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                                                    aria-valuemax="100" style="width: 0%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-1 text-center">
                                                        <span class="text-md">4</span>
                                                    </div>
                                                    <div class="col-11 pb-2">
                                                        <div class="m-0 p-0 mt-2">
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-primary review_four" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                                                    aria-valuemax="100" style="width: 0%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-1 text-center">
                                                        <span class="text-md">3</span>
                                                    </div>
                                                    <div class="col-11 pb-2">
                                                        <div class="m-0 p-0 mt-2">
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-primary review_three" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                                                    aria-valuemax="100" style="width: 0%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-1 text-center">
                                                        <span class="text-md">2</span>
                                                    </div>
                                                    <div class="col-11 pb-2">
                                                        <div class="m-0 p-0 mt-2">
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-primary review_two" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                                                    aria-valuemax="100" style="width: 0%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-1 text-center">
                                                        <span class="text-md">1</span>
                                                    </div>
                                                    <div class="col-11 pb-2">
                                                        <div class="m-0 p-0 mt-2">
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar bg-primary review_one" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                                                    aria-valuemax="100" style="width: 0%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php //} ?>
                <div class="col-12">
                    <div class="card card-dark elevation-2">
                        <div class="card-body">
                            <table id="datatables" class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th>Reservation ID</th>
                                        <th>Feedback</th>
                                        <th>Ratings</th>
                                        <th>Details</th>
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
  
<script>
    $(function () {

        loadData();
        function loadData()
        {
            var btn_action = 'load_ratings';
            $.ajax({
                url:"action.php",
                method:"POST",
                data:{ btn_action:btn_action },
                dataType:"json",
                success:function(data)
                {
                    $('.review_ratings').html(data.rate);
                    $('.review_star').html(data.star);
                    $('.review_five').attr('aria-valuenow', data.percent_5).css('width', data.percent_5+'%');
                    $('.review_four').attr('aria-valuenow', data.percent_4).css('width', data.percent_4+'%');
                    $('.review_three').attr('aria-valuenow', data.percent_3).css('width', data.percent_3+'%');
                    $('.review_two').attr('aria-valuenow', data.percent_2).css('width', data.percent_2+'%');
                    $('.review_one').attr('aria-valuenow', data.percent_1).css('width', data.percent_1+'%');
                },error:function()
                {
                    toastr.error('Please try again.');
                }
            })
        }

        var dataTable = $("#datatables").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "processing":true,
            "serverSide":true,
            "ordering": false,
            "order":[],
            "ajax":{
                url:"fetch/reviews.php",
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