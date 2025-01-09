<?php

include('config.php');

if(!isset($_SESSION["user_type"]))
{
    header("location:login.php");
}

$title = 'Events';
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
                <div class="col-12">
				<button class="btn" style="background-color:purple;color:#fff;"> TOTAL </button>
				<button class="btn" style="background-color:yellow;color:#000;"> PENDING </button>
				<button class="btn" style="background-color:blue;color:#fff;"> RESERVED </button>
				<button class="btn" style="background-color:orange;color:#fff;"> QUOTED </button>
				<button class="btn" style="background-color:green;color:#fff;"> PAYMENT </button>
				<button class="btn" style="background-color:teal;color:#fff;"> COMPLETED </button>
				<button class="btn" style="background-color:red;color:#fff;"> DECLINED </button>
				<button class="btn" style="background-color:gray;color:#fff;"> MEETING </button>
				<hr>
                    <div class="card ">
                        <div class="card-header ">
                            <div class="card-title">Scheduled</div>
                        </div>
                        <div class="card-body p-0">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div id="calendarModal" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 id="modalTitle" class="modal-title">Event</h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
        </div>
        <div id="modalBody" class="modal-body">
			
			<div id="event-client"></div>
			<div id="event-category"></div>
			<div id="event-date"></div>
			<div id="event-time"></div>

		</div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
</div>

<?php

include('footer.php');

?>
  
<script>
    $(function () {
        loadCalendar();
        function loadCalendar()
        {
            var btn_action = 'calendar';
            $.ajax({
                url:"action.php",
                method:"POST",
                data:{btn_action:btn_action},
                success:function(data)
                {
                    var arr = JSON.parse(data);
                    newArr = [];
                    for (var i = 0; i < arr.length; i++){
                        newArr.push({
                            id             : arr[i].id,
                            title          : arr[i].title,
                            topic          : arr[i].topic,
                            types          : arr[i].types,
                            client         : arr[i].client,
                            date_time      : arr[i].date_time,
                            event_time     : arr[i].event_time,
                            details        : arr[i].details,
                            start          : new Date(arr[i].start_y, arr[i].start_m, arr[i].start_d),
                            end            : new Date(arr[i].end_y, arr[i].end_m, arr[i].end_d),
                            backgroundColor: arr[i].color,
                            borderColor    : arr[i].color,
                            allDay         : true,
                            status         : arr[i].status,
                        });
                    }
                    renderCalendar(newArr);
                },error:function()
                {
                    Swal.fire({
                        icon: 'error',
                        // title: 'Error',
                        text: 'Something went wrong.',
                    })
                }
            })
        }
            
        function renderCalendar(newArrs)
        {
            /* initialize the calendar
            -----------------------------------------------------------------*/

            var Calendar = FullCalendar.Calendar;
            var Draggable = FullCalendar.Draggable;

            var calendarEl = document.getElementById('calendar');

            var calendar = new Calendar(calendarEl, {
                // initialDate: y+'-'+month,
                headerToolbar: {
                    left  : 'today',
                    center: 'title',
                    right : 'prev,next'
                },
                themeSystem: 'bootstrap',
                events: newArrs,
                editable  : false,
                droppable : false,
                eventClick : function( info ) {
					    $('#event-title').html(newArrs[0].title);
					    $('#event-client').html("Client Name :" + newArrs[0].client);
					    $('#event-category').html("Category :" + newArrs[0].types);
					    $('#event-date').html("Event Date :" + newArrs[0].date_time);
					    $('#event-time').html("Event Time :" + newArrs[0].event_time);
						$('#calendarModal').modal();
                },
            });

            calendar.render();
        }
    });
</script>

</body>
</html>