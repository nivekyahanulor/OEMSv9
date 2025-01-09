<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

$query .= "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Quoted' AND ";

if(isset($_POST["search"]["value"]))
{
	$query .= '(reservation_id LIKE "%'.$_POST["search"]["value"].'%" ';
	
	$query .= 'OR (SELECT last_name FROM '.$USER_TABLE.' WHERE id = '.$RESERVATION_TABLE.'.client_id) LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR (SELECT first_name FROM '.$USER_TABLE.' WHERE id = '.$RESERVATION_TABLE.'.client_id) LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR (SELECT middle_name FROM '.$USER_TABLE.' WHERE id = '.$RESERVATION_TABLE.'.client_id) LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR (SELECT contact FROM '.$USER_TABLE.' WHERE id = '.$RESERVATION_TABLE.'.client_id) LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR (SELECT address FROM '.$USER_TABLE.' WHERE id = '.$RESERVATION_TABLE.'.client_id) LIKE "%'.$_POST["search"]["value"].'%" ';

	$query .= 'OR types LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR events_id LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR event_date LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR event_time LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR total_days LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR total_hours LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR guests LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR event_place LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR address LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR notes LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR bartender LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR bartender_no LIKE "%'.$_POST["search"]["value"].'%" ';

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

	$details = "<br><b>Services:</b> ".number_format($row['services_amount'], 2, '.', ',')
	."<br><b>".$row['types'].":</b> ".number_format($row['fees_amount'], 2, '.', ',')
	."<br><b>Total:</b> ".number_format($row['total_amount'], 2, '.', ',')
	."<br><b>Method:</b> ".$row['payment_method'] // full payment or installment show other payment
	."<br><b>Due Date:</b> ".$row['payment_one_due']
	.'<br><a href="'.$row["contract_image"].'" download="'.$row["contract_image"].'">Contract</a>';
	$sub_array[] = $row['reservation_id'].$details;
	
	$clients = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '".$row["client_id"]."' ");
	
	$sub_array[] = "<b>Fullname:</b> ".$clients['last_name'].", ".$clients['first_name']." ".$clients['middle_name']
		."<br><b>Contact:</b> ".$clients['contact']
		."<br><b>Address:</b> ".$clients['address'] ;
	
	if ($row['types'] == 'Beverage')
	{
		$bartender_no = " (".$row['bartender_no'].")";
	
		$sub_array[] = "<b>Type:</b> ".$row['types']
			."<br><b>Events:</b> ".$row['events_id']
			."<br><b>Date:</b> ".$row['event_date']
			."<br><b>Time:</b> ".$row['event_time']
			."<br><b>Total Days:</b> ".$row['total_days']
			."<br><b>Total Hours:</b> ".$row['total_hours']
			."<br><b>Guests:</b> ".$row['guests']
			."<br><b>Place:</b> ".$row['event_place']
			."<br><b>Address:</b> ".$row['address']
			."<br><b>Notes:</b> ".$row['notes']
			."<br><b>Bartender:</b> ".$row['bartender'].$bartender_no ;
	}
	else
	{
		$sub_array[] = "<b>Type:</b> ".$row['types']
			."<br><b>Events:</b> ".$row['events_id']
			."<br><b>Date:</b> ".$row['event_date']
			."<br><b>Time:</b> ".$row['event_time']
			."<br><b>Address:</b> ".$row['address']
			."<br><b>Notes:</b> ".$row['notes'] ;
	}
	$sub_array[] = $row['date_created'];

	$sub_array[] = '
		<div class="btn-group btn-group-md elevation-2">
			<a href="#" class="btn btn-dark view text-bold" name="view" id="'.$row["id"].'" 
				data-types="'.$row["types"].'" 
				data-reservation_id="'.$row["reservation_id"].'" 
				data-bartender="'.$row["bartender_no"].'"
				data-services_amount="'.$row["services_amount"].'"
				data-total_amount="'.$row["total_amount"].'"
				data-toggle="tooltip" data-placement="top" title="View">
					View
			</a>
		</div>
	';

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
	$statement = $connect->prepare("SELECT * FROM $TABLE WHERE status = 'Quoted' ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>