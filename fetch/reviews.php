<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

$query .= "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Completed' AND feedback IS NOT NULL AND ";
if ($_SESSION["user_type"] == 'Client')
{
	$query .= " client_id = '".$_SESSION["user_id"]."' AND ";
}

if(isset($_POST["search"]["value"]))
{
	$query .= '(reservation_id LIKE "%'.$_POST["search"]["value"].'%" ';

	if ($_SESSION["user_type"] !== 'Client')
	{
		$query .= 'OR (SELECT last_name FROM '.$USER_TABLE.' WHERE id = '.$RESERVATION_TABLE.'.client_id) LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR (SELECT first_name FROM '.$USER_TABLE.' WHERE id = '.$RESERVATION_TABLE.'.client_id) LIKE "%'.$_POST["search"]["value"].'%" ';
		$query .= 'OR (SELECT middle_name FROM '.$USER_TABLE.' WHERE id = '.$RESERVATION_TABLE.'.client_id) LIKE "%'.$_POST["search"]["value"].'%" ';
	}

	$query .= 'OR event_date LIKE "%'.$_POST["search"]["value"].'%" ';
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
	
	$client_name = "";
	if ($_SESSION["user_type"] == 'Superadmin')
	{
		$clients = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '".$row["client_id"]."' ");
		$client_name = "<b>Client:</b> ".$clients['last_name'].", ".$clients['first_name']." ".$clients['middle_name']."<br>";
	}
	
	$star = '';
	if(!empty($row['feedback']))
	{
		for ($x = 1; $x <= $row["rating"]; $x++) {
			$star .= '<i class="fa fa-star text-dark"></i>';
		}
	}
	$sub_array[] = $row['reservation_id'];
	$sub_array[] = $row['feedback'];
	$sub_array[] = $star;
	$sub_array[] = $client_name."<b>Events:</b> ".$row['events_id']
		."<br><b>Date:</b> ".$row['event_date']
		."<br><b># of Guests:</b> ".$row['guests']
		."<br><b>Location:</b> ".$row['address']
		."<br><b>Notes:</b> ".$row['notes']
		."<br><b>Add Bartender:</b> ".$row['bartender'];
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
	$query = "";
	if ($_SESSION["user_type"] == 'Client')
	{
		$query .= " AND client_id = '".$_SESSION["user_id"]."' ";
	}
	$statement = $connect->prepare("SELECT * FROM $TABLE WHERE status = 'Completed' AND feedback IS NOT NULL $query");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>