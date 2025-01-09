<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

$query .= "SELECT * FROM $EVENTS_TABLE WHERE ";

if(isset($_POST["search"]["value"]))
{
	$query .= '(event LIKE "%'.$_POST["search"]["value"].'%" ';
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
	
	if($row['status'] == 'Active')
	{
		$status = '<a href="#" class="btn btn-primary status text-bold" data-status="Inactive" name="status" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="Active">Active</a>';
	}
	else
	{
		$status = '<a href="#" class="btn btn-secondary status text-bold" data-status="Active" name="status" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="Active">Inactive</a>';
	}

    $sub_array[] = '
        <div class="btn-group btn-group-md elevation-2"> '.$status .'
            <a href="#" class="btn btn-dark update text-bold" name="update" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="Update">Update</a>
        </div>
    ';
	
	$sub_array[] = $row['event'];
	// $sub_array[] = $row['date_created'];

	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, $EVENTS_TABLE),
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