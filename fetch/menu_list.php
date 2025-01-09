<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

$query .= "SELECT *, (SELECT category FROM $CATEGORY_TABLE WHERE id = $PRODUCT_TABLE.category_id) AS category FROM $PRODUCT_TABLE 
WHERE status = 'Active' AND ";

if(isset($_POST["search"]["value"]))
{
	$query .= '(product LIKE "%'.$_POST["search"]["value"].'%" ';

	$query .= 'OR (SELECT category FROM '.$CATEGORY_TABLE.' WHERE id = '.$PRODUCT_TABLE.'.category_id) LIKE "%'.$_POST["search"]["value"].'%" ';

	$query .= 'OR description LIKE "%'.$_POST["search"]["value"].'%"  )';
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
	
	$sub_array[] = $row['category'];
	$sub_array[] = $row['product'];
	$sub_array[] = $row['description'];

	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect, $PRODUCT_TABLE),
	"data"				=>	$data
);

function get_total_all_records($connect, $TABLE)
{
	$statement = $connect->prepare("SELECT * FROM $TABLE WHERE status = 'Active' ");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>