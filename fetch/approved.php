<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

$query .= "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Approved' AND ";

if(isset($_POST["search"]["value"]))
{
	$query .= '(reservation_id LIKE "%'.$_POST["search"]["value"].'%" ';

	$query .= 'OR (SELECT last_name FROM '.$USER_TABLE.' WHERE id = '.$RESERVATION_TABLE.'.client_id) LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR (SELECT first_name FROM '.$USER_TABLE.' WHERE id = '.$RESERVATION_TABLE.'.client_id) LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR (SELECT middle_name FROM '.$USER_TABLE.' WHERE id = '.$RESERVATION_TABLE.'.client_id) LIKE "%'.$_POST["search"]["value"].'%" ';

	$query .= 'OR event_date LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR total_days LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR total_hours LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR guests LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR address LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR notes LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR bartender LIKE "%'.$_POST["search"]["value"].'%" ';
	
	$query .= 'OR feedback LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR rating LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR reason LIKE "%'.$_POST["search"]["value"].'%" ';

	$query .= 'OR status LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR date_created LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY id DESC ';
}

if($_POST['length'] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$data = array();

$filtered_rows = $statement->rowCount();

foreach($result as $row)
{
	$sub_array = array();
	
	$user = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '".$row["client_id"]."' ");
	$sub_array[] = '
		<div class="btn-group btn-group-md elevation-2">
			<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'"  data-customer="'.$user["last_name"].", ".$user["first_name"]." ".$user["middle_name"].'" 
				data-reservation_id="'.$row["reservation_id"].'" data-bartender="'.$row["bartender_no"].'" data-status="Quoted" 
				data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
				data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
			<a href="#" class="btn btn-default status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" data-status="Accept" data-toggle="tooltip" data-placement="top" title="Accept">Accept</a>
			<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" data-status="Decline" data-toggle="tooltip" data-placement="top" title="Decline">Decline</a>
			<a href="#" class="btn btn-default view text-bold" name="view" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
		</div>
	';
	
	$client_name = "";
	if ($_SESSION["user_type"] == 'Superadmin')
	{
		$clients = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '".$row["client_id"]."' ");
		$client_name = "<b>Client:</b> ".$clients['last_name'].", ".$clients['first_name']." ".$clients['middle_name']."<br>";
	}

	$sub_array[] = $row['reservation_id'];
	
	$bartender_no = " (".$row['bartender_no'].")";

	$sub_array[] = $client_name."<b>Events:</b> ".$row['events_id']
		."<br><b>Event Date:</b> ".$row['event_date']
		."<br><b>Event Time:</b> ".$row['event_time']
		."<br><b>Total Days:</b> ".$row['total_days']
		."<br><b>Total Hours:</b> ".$row['total_hours']
		."<br><b># of Guests:</b> ".$row['guests']
		."<br><b>Event Place:</b> ".$row['event_place']
		."<br><b>Event Address:</b> ".$row['address']
		."<br><b>Notes:</b> ".$row['notes']
		."<br><b>Bartender:</b> ".$row['bartender'].$bartender_no ;
	$sub_array[] = $row['date_created'];

	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, $RESERVATION_TABLE),
	"data"				=>	$data
);

function get_total_all_records($connect, $TABLE)
{
	$statement = $connect->prepare("SELECT * FROM $TABLE WHERE status = 'Approved' ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>