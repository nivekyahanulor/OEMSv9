<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

$query .= "SELECT a.* ,b.reservation_id,c.first_name,c.last_name,c.contact FROM $REPORT_TABLE  a  ";
$query .= "LEFT JOIN $RESERVATION_TABLE b on  a.reservation = b.id ";
$query .= "LEFT JOIN $USER_TABLE c on b.client_id = c.id ";
$query .= "WHERE";

if(isset($_POST["search"]["value"]))
{
	$query .= '(a.item_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR a.quantity LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR a.date_created LIKE "%'.$_POST["search"]["value"].'%" )';
}

if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY a.id DESC ';
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

    $sub_array[] = '
        <div class="btn-group btn-group-md elevation-2"> 
            <a href="#" class="btn btn-dark update text-bold" name="update" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="Update">Update</a>
        </div>
    ';
	$sub_array[] = $row['reservation_id'];
	$sub_array[] = $row['first_name'].' '.$row['last_name'];
	$sub_array[] = $row['contact'];
	$sub_array[] = $row['item_name'];
	$sub_array[] = $row['date_created'];

	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, $REPORT_TABLE),
	"data"				=>	$data
);

function get_total_all_records($connect, $TABLE)
{
	$statement = $connect->prepare("SELECT * FROM $TABLE ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>