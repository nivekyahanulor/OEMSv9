<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Notifications';
include('header.php');

include('sidebar.php');

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0"><?php echo $title; ?></h1>
            <?php 
            
                // $date_now = new DateTime();
                // $result = fetch_all($connect," SELECT * FROM $RESERVATION_TABLE WHERE status IN ('Accepted','Reserved','Processing') " );
                // foreach($result as $row)
                // {
                //     if ($row["status"] == 'Processing' && $row["payment_method"] == 'Installment')
                //     {
                //         // echo str_replace("-", "/", $row["payment_three_due"]);

                //         if (empty($row["payment_three"]))
                //         {
                //             $date_payment    = new DateTime(str_replace("-", "/", $row["payment_three_due"]));
                //             if ($date_now > $date_payment) 
                //             {
                //                 $fifty = (50 / 100) * floatval($row['total_amount']);
                //                 $title = 'Due Date of Final Payment';
                //                 $message = 'Due date : '.$row["payment_three_due"].' with an amount of '.$fifty.'.';
                //                 notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $row["reservation_id"], $title, $message);
                                
                //                 query($connect, "UPDATE $RESERVATION_TABLE SET payment_three = '".date("m-d-Y h:i A")."' WHERE id = '".$row['id']."' ");
                //             }
                //         }
                //     }
                //     else if ($row["status"] == 'Reserved')
                //     {
                //         // echo str_replace("-", "/", $row["payment_two_due"]);

                //         if (empty($row["payment_two"]))
                //         {
                //             $date_payment    = new DateTime(str_replace("-", "/", $row["payment_two_due"]));
                //             if ($date_now > $date_payment) 
                //             {
                //                 $forty = (40 / 100) * floatval($row['total_amount']);
                //                 $title = 'Due Date of Second Payment';
                //                 $message = 'Due date : '.$row["payment_two_due"].' with an amount of '.$forty.'.';
                //                 notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $row["reservation_id"], $title, $message);
                                
                //                 query($connect, "UPDATE $RESERVATION_TABLE SET payment_two = '".date("m-d-Y h:i A")."' WHERE id = '".$row['id']."' ");
                //             }
                //         }
                //     }
                //     else
                //     {
                //         // echo str_replace("-", "/", $row["payment_one_due"]);
                        
                //         if (empty($row["payment_one"]))
                //         {
                //             $date_payment    = new DateTime(str_replace("-", "/", $row["payment_one_due"]));
                //             if ($date_now > $date_payment) 
                //             {
                //                 $ten = (10 / 100) * floatval($row['total_amount']);
                //                 $title = 'Due Date of First Payment';
                //                 $message = 'Due date : '.$row["payment_one_due"].' with an amount of '.$ten.'.';
                //                 notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $row["reservation_id"], $title, $message);
                                
                //                 query($connect, "UPDATE $RESERVATION_TABLE SET payment_one = '".date("m-d-Y h:i A")."' WHERE id = '".$row['id']."' ");
                //             }
                //         }
                //     }
                // }

                // $date_now = new DateTime();
                // $date_payment    = new DateTime("10/13/2023");
               
                // if ($date_now > $date_payment) 
                // {
                //     echo 'send notification';
                // }
            ?>
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
                                        <th>#</th>
                                        <th>Reservation ID</th>
                                        <th>Title</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Date Notified</th>
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

        const socket = io('<?php echo $server_link; ?>');
        socket.on('message', text => 
        {
            var json = JSON.parse(text);
            
            if (json.action == 'send')
            {
                loadData();
            }
        });

        function sendData(data) 
        {
            socket.emit('message', JSON.stringify(data))
        }

        loadData();
        function loadData()
        {
            $.ajax({
                url:"action.php",
                method:"POST",
                data: { btn_action: 'notification_load' },
                dataType:"json",
                success:function(data)
                {
                    $('#datatables').DataTable().destroy();
                    $('#datatables').html(data.table);  
                    $("#datatables").DataTable({ 
                        // "scrollX": true, 
                        "lengthChange": true, 
                        "autoWidth": false,
                        "ordering": false,
                    });
                    $('#datatables').DataTable().draw();
                    

                    $("#datatables tbody tr").css('cursor', 'pointer');
                    $("#datatables tbody tr").on('click',function(){
                        var currentRow=$(this).closest("tr"); 
                        var col0 = currentRow.find("td:eq(0)").text(); 
                        var col4 = currentRow.find("td:eq(4)").text(); 
                        if (col4 !== 'Seen') // 
                        {
                            $.ajax({
                                url:"action.php",
                                method:"POST",
                                data: { btn_action: 'notification_seen', id: col0 },
                                dataType:"json",
                                success:function(data)
                                {
                                    loadData();
                                    sendData( { action : 'refresh' } );
                                },error:function()
                                {
                                    // toastr.error('Please try again.');
                                }
                            })
                        }
                    });
                },error:function()
                {
                    // toastr.error('Please try again.');
                }
            })
        }

    });
</script>

</body>
</html>