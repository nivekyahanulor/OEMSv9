<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

$query .= "SELECT * FROM $SETTINGS_TABLE WHERE ";

if(isset($_POST["search"]["value"]))
{
	$query .= '(image LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR title LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR description LIKE "%'.$_POST["search"]["value"].'%" ';
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

	$sub_array[] = '
		<div class="btn-group btn-group-md elevation-2">
			<a href="#" class="btn btn-dark delete text-bold" name="delete" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="Delete">Delete</a>
		</div>
	';
	// $sub_array[] = $row['image'];
	$sub_array[] = '<a data-magnify="gallery" class="card-img-top " data-caption="'.$row["image"].'" data-group="" href="'.$row["image"].'">
		<img class="img-fluid " style="height: 50px; width: 50px; cursor: pointer;" src="'.$row["image"].'" alt="Valid Photo">
	</a>';
	$sub_array[] = $row['title'];
	$sub_array[] = $row['description'];
	$sub_array[] = $row['date_created'];

	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, $SETTINGS_TABLE),
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