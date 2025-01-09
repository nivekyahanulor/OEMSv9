<?php

//fetch_data.php

include('../config.php');

$query = '';

$output = array();

$query .= "SELECT * FROM $RESERVATION_TABLE WHERE ";


if ($_SESSION["user_type"] == 'Client')
{
	$query .= " client_id = '".$_SESSION["user_id"]."' AND ";
	$query .= " status = '".$_GET["data"]."' AND ";
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
	$sub_array[] = $row['reservation_id'];

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
		if($row['status'] == 'Pending' || $row['status'] == 'Reserved')
		{
			$sub_array[] = "<b>Type:</b> ".$row['types']
				."<br><b>Date:</b> ".$row['event_date']
				."<br><b>Time:</b> ".$row['event_time']
				."<br><b>Notes:</b> ".$row['notes'] ;
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
	}
	$sub_array[] = $row['date_created'];

	$status = '';
	$button = '';
	if($row['status'] == 'Pending' || $row['status'] == 'Reserved')
	{
		if ($row['status'] == 'Pending')
		{
			$status = '<span class="badge badge-secondary pl-3 pr-3 p-2">Pending</span>';
			if ($row['types'] == 'Beverage')
			{
				$button = '
					<div class="btn-group btn-group-md elevation-2">
						<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" 
							data-reservation_id="'.$row["reservation_id"].'" 
							data-status="Cancel" data-toggle="tooltip" data-placement="top" title="Cancel">
								Cancel
						</a>
						<a href="#" class="btn btn-default view text-bold" name="view" id="'.$row["id"].'" 
							data-reservation_id="'.$row["reservation_id"].'" 
							data-types="'.$row["types"].'" 
							data-status="'.$row["status"].'" 
							data-toggle="tooltip" data-placement="top" title="View">
								View
						</a>
					</div>
				';
			}
			else
			{
				$button = '
					<div class="btn-group btn-group-md elevation-2">
						<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" 
							data-reservation_id="'.$row["reservation_id"].'" 
							data-status="Cancel" data-toggle="tooltip" data-placement="top" title="Cancel">
								Cancel
						</a>
					</div>
				';
			}
		}
		else
		{
			$status = '<span class="badge badge-primary pl-3 pr-3 p-2">Reserved</span>';
			if ($row['types'] == 'Beverage')
			{
				$button = '
					<div class="btn-group btn-group-md elevation-2">
						<a href="#" class="btn btn-dark view text-bold" name="view" id="'.$row["id"].'" 
							data-reservation_id="'.$row["reservation_id"].'" 
							data-types="'.$row["types"].'" 
							data-status="'.$row["status"].'" 
							data-toggle="tooltip" data-placement="top" title="View">
								View
						</a>
					</div>
				';
			}
		}
	}
	else if($row['status'] == 'Declined' || $row['status'] == 'Cancelled')
	{
		$reason = $row["status"] !== "Declined" ? "" :  "<br><b>Reason:</b> ".$row['reason'];
		$status = '<span class="badge badge-danger pl-3 pr-3 p-2">'.$row['status'].'</span>'.$reason;
		if ($row['types'] == 'Beverage')
		{
			$button = '
				<div class="btn-group btn-group-md elevation-2">
					<a href="#" class="btn btn-dark view text-bold" name="view" id="'.$row["id"].'" 
						data-reservation_id="'.$row["reservation_id"].'" 
						data-types="'.$row["types"].'" 
						data-status="'.$row["status"].'" 
						data-toggle="tooltip" data-placement="top" title="View">
							View
					</a>
				</div>
			';
		}
	}
	else if($row['status'] == 'Quoted' || $row['status'] == 'Payment')
	{
		if ($row['status'] == 'Quoted')
		{
			$details = "<br><b>Service:</b> ".number_format($row['services_amount'], 2, '.', ',')
			."<br><b>".$row['types'].":</b> ".number_format($row['fees_amount'], 2, '.', ',')
			."<br><b>Total:</b> ".number_format($row['total_amount'], 2, '.', ',')
			."<br><b>Method:</b> ".$row['payment_method'] // full payment or installment show other payment
			."<br><b>Due Date:</b> ".$row['payment_one_due']
			.'<br><a href="'.$row["contract_image"].'" download="'.$row["contract_image"].'">Contract</a>';
		}
		else
		{
			$details = "<br><b>Total:</b> ".number_format($row['total_amount'], 2, '.', ',')
			."<br><b>Method:</b> ".$row['payment_method'] // full payment or installment show other payment
			."<br><b>Sender:</b> ".$row['payment_one_sender']
			."<br><b>Reference #:</b> ".$row['payment_one_no']
			."<br><b>Server:</b> ".$row['payment_one_server']
			."<br><b>Date:</b> ".$row['payment_one_date']
			.'<br><a href="'.$row["contract_image"].'" download="'.$row["contract_image"].'">Contract</a>';
		}

		$status = '<span class="badge badge-primary pl-3 pr-3 p-2">'.$row['status'].'</span>'.$details;
		
		if ($row["payment_method"] == 'Full Payment')
		{
			$amount = $row['total_amount'];
		}
		else
		{
			$ten = (10 / 100) * floatval($row['total_amount']);
			$forty = (40 / 100) * floatval($row['total_amount']);
			$fifty = (50 / 100) * floatval($row['total_amount']);
			
			if(empty($row["payment_one"]))
			{
				$amount = $ten;
			}
			else if(empty($row["payment_two"]))
			{
				$amount = $forty;
			}
			else if(empty($row["payment_three"]))
			{
				$amount = $fifty;
			}
		}
		if ($row['status'] == 'Quoted')
		{
			$button = '
				<div class="btn-group btn-group-md elevation-2">
					<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" 
						data-reservation_id="'.$row["reservation_id"].'" 
						data-amount="'.$amount.'" 
						data-status="Payment" data-toggle="tooltip" data-placement="top" title="Payment">
							Payment
					</a>
					
					<a href="#" class="btn btn-default view text-bold" name="view" id="'.$row["id"].'" 
						data-reservation_id="'.$row["reservation_id"].'" 
						data-types="'.$row["types"].'" 
						data-status="'.$row["status"].'" 
						data-bartender="'.$row["bartender_no"].'"
						data-services_amount="'.$row["services_amount"].'"
						data-total_amount="'.$row["total_amount"].'"
						data-toggle="tooltip" data-placement="top" title="View">
							View
					</a>
				</div>
			';
		}
		else
		{
			if ($row["payment_method"] == 'Installment')
			{
				$button = '
					<div class="btn-group btn-group-md elevation-2">
						<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" 
							data-reservation_id="'.$row["reservation_id"].'" 
							data-amount="'.$amount.'" 
							data-status="Payment" data-toggle="tooltip" data-placement="top" title="Payment">
								Payment
						</a>
						
						<a href="#" class="btn btn-default view text-bold" name="view" id="'.$row["id"].'" 
							data-reservation_id="'.$row["reservation_id"].'" 
							data-types="'.$row["types"].'" 
							data-status="'.$row["status"].'" 
							data-bartender="'.$row["bartender_no"].'"
							data-services_amount="'.$row["services_amount"].'"
							data-total_amount="'.$row["total_amount"].'"
							data-toggle="tooltip" data-placement="top" title="View">
								View
						</a>
					</div>
				';
			}
			else
			{
				$button = '
					<div class="btn-group btn-group-md elevation-2">
						<a href="#" class="btn btn-dark view text-bold" name="view" id="'.$row["id"].'" 
							data-reservation_id="'.$row["reservation_id"].'" 
							data-types="'.$row["types"].'" 
							data-status="'.$row["status"].'" 
							data-bartender="'.$row["bartender_no"].'"
							data-services_amount="'.$row["services_amount"].'"
							data-total_amount="'.$row["total_amount"].'"
							data-toggle="tooltip" data-placement="top" title="View">
								View
						</a>
					</div>
				';
			}
		}
	}
	else
	{
		$details = "<br><b>Total:</b> ".number_format($row['total_amount'], 2, '.', ',')
		."<br><b>Method:</b> ".$row['payment_method'] // full payment or installment show other payment
		."<br><b>Sender:</b> ".$row['payment_one_sender']
		."<br><b>Reference #:</b> ".$row['payment_one_no']
		."<br><b>Server:</b> ".$row['payment_one_server']
		."<br><b>Date:</b> ".$row['payment_one_date']
		.'<br><a href="'.$row["contract_image"].'" download="'.$row["contract_image"].'">Contract</a>';
		$status = '<span class="badge badge-success pl-3 pr-3 p-2">'.$row['status'].'</span>'.$details;
		if (!empty($row['feedback']))
		{
			$star = '';
			if(!empty($row['feedback']))
			{
				for ($x = 1; $x <= $row["rating"]; $x++) {
					$star .= '<i class="fa fa-star text-dark"></i>';
				}
			}
			$button = '
				<div class="btn-group btn-group-md elevation-2">
					<a href="#" class="btn btn-dark view text-bold" name="view" id="'.$row["id"].'" 
						data-reservation_id="'.$row["reservation_id"].'" 
						data-types="'.$row["types"].'" 
						data-status="'.$row["status"].'" 
						data-bartender="'.$row["bartender_no"].'"
						data-services_amount="'.$row["services_amount"].'"
						data-total_amount="'.$row["total_amount"].'"
						data-toggle="tooltip" data-placement="top" title="View">
							View
					</a>
				</div>
			';
			// ."<br><b>Feedback:</b> ".$row['feedback']
			// .'<br>'.$star
		}
		else
		{
			$button = '
				<div class="btn-group btn-group-md elevation-2">
					<a href="#" class="btn btn-dark feedback text-bold" name="feedback" id="'.$row["id"].'" 
						data-reservation_id="'.$row["reservation_id"].'" 
						data-toggle="tooltip" data-placement="top" title="Review">
							Review
					</a>
					<a href="#" class="btn btn-default view text-bold" name="view" id="'.$row["id"].'" 
						data-reservation_id="'.$row["reservation_id"].'" 
						data-types="'.$row["types"].'" 
						data-status="'.$row["status"].'" 
						data-bartender="'.$row["bartender_no"].'"
						data-services_amount="'.$row["services_amount"].'"
						data-total_amount="'.$row["total_amount"].'"
						data-toggle="tooltip" data-placement="top" title="View">
							View
					</a>
				</div>
			';
		}
	}
	$sub_array[] = $status;
	$sub_array[] = $button;
	
	// $client_name = "";
	if ($_SESSION["user_type"] == 'Superadmin')
	{
		// $clients = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '".$row["client_id"]."' ");
		// // $client_name = "<b>Client:</b> ".$clients['last_name'].", ".$clients['first_name']." ".$clients['middle_name']."<br>";
		
		// $sub_array[] = "<b>Fullname:</b> ".$clients['last_name'].", ".$clients['first_name']." ".$clients['middle_name']
		// 	."<br><b>Contact:</b> ".$clients['contact']
		// 	."<br><b>Address:</b> ".$clients['address'] ;
	}

	// if ($row["types"] == 'Beverage')
	// {
	// 	$bartender_no = $row['bartender'] == 'Yes' ? " (".$row['bartender_no'].")" : '';
	
	// 	$sub_array[] = "<b>Type:</b> ".$row['types']
	// 		."<br><b>Events:</b> ".$row['events_id']
	// 		."<br><b>Date:</b> ".$row['event_date']
	// 		."<br><b>Time:</b> ".$row['event_time']
	// 		."<br><b>Total Days:</b> ".$row['total_days']
	// 		."<br><b>Total Hours:</b> ".$row['total_hours']
	// 		."<br><b>Guests:</b> ".$row['guests']
	// 		."<br><b>Place:</b> ".$row['event_place']
	// 		."<br><b>Address:</b> ".$row['address']
	// 		."<br><b>Notes:</b> ".$row['notes']
	// 		."<br><b>Bartender:</b> ".$row['bartender'].$bartender_no ;
	// }
	// else
	// {
	// 	if ($row["status"] == 'Pending')
	// 	{
	// 		$sub_array[] = "<b>Type:</b> ".$row['types']
	// 			."<br><b>Date:</b> ".$row['event_date']
	// 			."<br><b>Time:</b> ".$row['event_time']
	// 			."<br><b>Notes:</b> ".$row['notes'] ;
	// 	}
	// 	else
	// 	{
	// 		$sub_array[] = "<b>Type:</b> ".$row['types']
	// 			."<br><b>Events:</b> ".$row['events_id']
	// 			."<br><b>Date:</b> ".$row['event_date']
	// 			."<br><b>Time:</b> ".$row['event_time']
	// 			."<br><b>Address:</b> ".$row['address']
	// 			."<br><b>Notes:</b> ".$row['notes'] ;
	// 	}
	// }
	// if ($_SESSION["user_type"] == 'Client') 
	// {
	// 	if($row['status'] == 'Pending')
	// 	{
	// 		$sub_array[] = '
	// 			<div class="btn-group btn-group-md elevation-2">
	// 				<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" data-status="Cancel" data-toggle="tooltip" data-placement="top" title="Cancel">Cancel</a>
	// 				<a href="#" class="btn btn-default view text-bold" name="view" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 			</div>
	// 		';
	// 	}
	// 	else if($row['status'] == 'Cancelled')
	// 	{
	// 		$sub_array[] = '
	// 			<div class="btn-group btn-group-md elevation-2">
	// 				<a href="#" class="btn btn-dark view text-bold" name="view"  id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 			</div>
	// 		';
	// 	}
	// 	else if($row['status'] == 'Quoted')
	// 	{
	// 		$sub_array[] = '
	// 			<div class="btn-group btn-group-md elevation-2">
	// 				<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" 
	// 					data-reservation_id="'.$row["reservation_id"].'" data-bartender="'.$row["bartender_no"].'" data-status="Quoted" 
	// 					data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 					data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 				<a href="#" class="btn btn-default status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" data-status="Cancel" data-toggle="tooltip" data-placement="top" title="Cancel">Cancel</a>
	// 				<a href="#" class="btn btn-dark view text-bold" name="view" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 			</div>
	// 		';
	// 	}
	// 	else if($row['status'] == 'Approved')
	// 	{
	// 		$sub_array[] = '
	// 			<div class="btn-group btn-group-md elevation-2">
	// 				<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" 
	// 					data-reservation_id="'.$row["reservation_id"].'" data-bartender="'.$row["bartender_no"].'" data-status="Approved" 
	// 					data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 					data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 				<a href="#" class="btn btn-default view text-bold" name="view" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 			</div>
	// 		';
	// 	}
	// 	else if($row['status'] == 'Accepted' || $row['status'] == 'Reserved' || $row['status'] == 'Processing')
	// 	{
	// 		$date = substr($row["date_accepted"], 6,4)."-".substr($row["date_accepted"], 0, 2)."-".substr($row["date_accepted"], 3, 2);
	// 		if ($row['status'] == 'Accepted')
	// 		{
	// 			if ($row["payment_method"] == 'Full Payment')
	// 			{
	// 				$ten = floatval($row['total_amount']);
	// 			}
	// 			else
	// 			{
	// 				$ten = (10 / 100) * floatval($row['total_amount']);
	// 			}
	// 			$sub_array[] = '
	// 				<div class="btn-group btn-group-md elevation-2">
	// 					<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" 
	// 						data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 						data-bartender="'.$row["bartender_no"].'" data-status="Approved" data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 					<a href="#" class="btn btn-default payment text-bold" name="payment"  id="'.$row["id"].'" 
	// 						data-total="₱'.number_format($row["total_amount"], 2, '.', ',').'" data-method="'.$row["payment_method"].'" 
	// 						data-amount="'.number_format($ten, 2, '.', '').'" 
	// 						data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'" 
	// 						data-reservation_id="'.$row["reservation_id"].'" data-toggle="tooltip" data-placement="top" title="Payment">Payment</a>
	// 					<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" data-status="Cancel" data-toggle="tooltip" data-placement="top" title="Cancel">Cancel</a>
	// 					<a href="#" class="btn btn-default view text-bold" name="view"  id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 				</div>
	// 			';
	// 		}
	// 		else
	// 		{
	// 			if ($row["payment_method"] == 'Full Payment')
	// 			{
	// 				$sub_array[] = '
	// 					<div class="btn-group btn-group-md elevation-2">
	// 						<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" 
	// 							data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 							data-bartender="'.$row["bartender_no"].'" data-status="Approved" data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 						<a href="#" class="btn btn-default view text-bold" name="view"  id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 					</div>
	// 				';
	// 			}
	// 			else
	// 			{
	// 				if (empty($row["payment_two"]))
	// 				{
	// 					$amount = (40 / 100) * floatval($row['total_amount']);
	// 					$details = "<b>40%:</b> ₱".number_format($amount, 2, '.', ',')." <b>Due Date:</b> ".$row['payment_two_due'];//date('M d, Y', strtotime($date. ' - 15 days'));
	// 				}
	// 				else
	// 				{
	// 					$amount = (50 / 100) * floatval($row['total_amount']);
	// 					$details = "<b>50%:</b> ₱".number_format($amount, 2, '.', ',')." <b>Due Date:</b> ".$row['payment_three_due'];//date('M d, Y', strtotime($date. ' - 7 days'));
	// 				}
	// 				if (empty($row["payment_three"]))
	// 				{
	// 					if (!empty($row["payment_two"]))
	// 					{
	// 						$sub_array[] = '
	// 							<div class="btn-group btn-group-md elevation-2">
	// 								<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" 
	// 									data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 									data-bartender="'.$row["bartender_no"].'" data-status="Approved" data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 								<a href="#" class="btn btn-default payment text-bold" name="payment"  id="'.$row["id"].'" 
	// 									data-details="'.$details.'" data-reservation_id="'.$row["reservation_id"].'" data-method="'.$row["payment_method"].'" 
	// 									data-amount="'.number_format($amount, 2, '.', '').'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 									data-toggle="tooltip" data-placement="top" title="Payment">Payment</a>
	// 								<a href="#" class="btn btn-dark view text-bold" name="view"  id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 							</div>
	// 						';
	// 					}
	// 					else
	// 					{
	// 						$sub_array[] = '
	// 							<div class="btn-group btn-group-md elevation-2">
	// 								<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" 
	// 									data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 									data-bartender="'.$row["bartender_no"].'" data-status="Approved" data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 								<a href="#" class="btn btn-default payment text-bold" name="payment"  id="'.$row["id"].'" 
	// 									data-details="'.$details.'" data-reservation_id="'.$row["reservation_id"].'" data-method="'.$row["payment_method"].'" 
	// 									data-amount="'.number_format($amount, 2, '.', '').'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 									data-toggle="tooltip" data-placement="top" title="Payment">Payment</a>
	// 								<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" data-status="Cancel" data-toggle="tooltip" data-placement="top" title="Cancel">Cancel</a>
	// 								<a href="#" class="btn btn-default view text-bold" name="view"  id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 							</div>
	// 						';
	// 					}
	// 				}
	// 				else
	// 				{
	// 					$sub_array[] = '
	// 						<div class="btn-group btn-group-md elevation-2">
	// 							<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" 
	// 								data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 								data-bartender="'.$row["bartender_no"].'" data-status="Approved" data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 							<a href="#" class="btn btn-default view text-bold" name="view"  id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 						</div>
	// 					';
	// 				}
	// 			}
	// 		}
	// 	}
	// 	else
	// 	{
	// 		if( empty($row['feedback']) )
	// 		{
	// 			$sub_array[] = '
	// 				<div class="btn-group btn-group-md elevation-2">
	// 					<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" 
	// 					data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 					data-bartender="'.$row["bartender_no"].'" data-status="Approved" data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 					<a href="#" class="btn btn-default view text-bold" name="view"  id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 					<a href="#" class="btn btn-dark rates text-bold" name="rates"  id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" data-toggle="tooltip" data-placement="top" title="Rate">Rate</a>
	// 				</div>
	// 			';
	// 		}
	// 		else
	// 		{
	// 			$sub_array[] = '
	// 				<div class="btn-group btn-group-md elevation-2">
	// 					<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" 
	// 					data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 					data-bartender="'.$row["bartender_no"].'" data-status="Approved" data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 					<a href="#" class="btn btn-default view text-bold" name="view"  id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 				</div>
	// 			';
	// 		}
	// 	}
	// }
	// else
	// {
    //     $user = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '".$row["client_id"]."' ");
	// 	if($row['status'] == 'Pending')
	// 	{
	// 		$sub_array[] = '
	// 			<div class="btn-group btn-group-md elevation-2">
	// 				<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" data-customer="'.$user["last_name"].", ".$user["first_name"]." ".$user["middle_name"].'" 
	// 				data-reservation_id="'.$row["reservation_id"].'" data-bartender="'.$row["bartender_no"].'" data-status="Quotation" 
	// 				data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" 
	// 				data-types="'.$row["types"].'" 
	// 				data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 				<a href="#" class="btn btn-default status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" data-status="Decline" data-toggle="tooltip" data-placement="top" title="Decline">Decline</a>
	// 				<a href="#" class="btn btn-dark view text-bold" name="view" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 			</div>
	// 		';
	// 	}
	// 	else if($row['status'] == 'Quoted')
	// 	{
	// 		$sub_array[] = '
	// 			<div class="btn-group btn-group-md elevation-2">
	// 				<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'"  data-customer="'.$user["last_name"].", ".$user["first_name"]." ".$user["middle_name"].'" 
	// 				data-reservation_id="'.$row["reservation_id"].'" data-bartender="'.$row["bartender_no"].'" data-status="Quoted" 
	// 				data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 				data-types="'.$row["types"].'" 
	// 				data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 				<a href="#" class="btn btn-default view text-bold" name="view" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 			</div>
	// 		';
	// 	}
	// 	else if($row['status'] == 'Approved')
	// 	{
	// 		$sub_array[] = '
	// 			<div class="btn-group btn-group-md elevation-2">
	// 				<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'"  data-customer="'.$user["last_name"].", ".$user["first_name"]." ".$user["middle_name"].'" 
	// 				data-reservation_id="'.$row["reservation_id"].'" data-bartender="'.$row["bartender_no"].'" data-status="Quoted" 
	// 				data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 				data-types="'.$row["types"].'" 
	// 				data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 				<a href="#" class="btn btn-default status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" data-status="Accept" data-toggle="tooltip" data-placement="top" title="Accept">Accept</a>
	// 				<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" data-status="Decline" data-toggle="tooltip" data-placement="top" title="Decline">Decline</a>
	// 				<a href="#" class="btn btn-default view text-bold" name="view" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 			</div>
	// 		';
	// 	}
	// 	else if($row['status'] == 'Accepted')
	// 	{
	// 		$sub_array[] = '
	// 			<div class="btn-group btn-group-md elevation-2">
	// 			<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'"  data-customer="'.$user["last_name"].", ".$user["first_name"]." ".$user["middle_name"].'" 
	// 			data-reservation_id="'.$row["reservation_id"].'" data-bartender="'.$row["bartender_no"].'" data-status="Quoted" 
	// 			data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 			data-types="'.$row["types"].'" 
	// 			data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 				<a href="#" class="btn btn-default status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" data-status="Decline" data-toggle="tooltip" data-placement="top" title="Decline">Decline</a>
	// 				<a href="#" class="btn btn-dark view text-bold" name="view" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 			</div>
	// 		';
	// 	}
	// 	else if($row['status'] == 'Reserved' || $row['status'] == 'Processing')
	// 	{
	// 		if (!empty($row["payment_two"]))
	// 		{
	// 			$sub_array[] = '
	// 				<div class="btn-group btn-group-md elevation-2">
	// 					<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'"  data-customer="'.$user["last_name"].", ".$user["first_name"]." ".$user["middle_name"].'" 
	// 					data-reservation_id="'.$row["reservation_id"].'" data-bartender="'.$row["bartender_no"].'" data-status="Quoted" 
	// 					data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 					data-types="'.$row["types"].'" 
	// 					data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 					<a href="#" class="btn btn-default view text-bold" name="view" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 				</div>
	// 			';
	// 		}
	// 		else
	// 		{
	// 			$sub_array[] = '
	// 				<div class="btn-group btn-group-md elevation-2">
	// 				<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'"  data-customer="'.$user["last_name"].", ".$user["first_name"]." ".$user["middle_name"].'" 
	// 				data-reservation_id="'.$row["reservation_id"].'" data-bartender="'.$row["bartender_no"].'" data-status="Quoted" 
	// 				data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 				data-types="'.$row["types"].'" 
	// 				data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 					<a href="#" class="btn btn-default status text-bold" name="status" id="'.$row["id"].'" data-reservation_id="'.$row["reservation_id"].'" data-status="Decline" data-toggle="tooltip" data-placement="top" title="Decline">Decline</a>
	// 					<a href="#" class="btn btn-dark view text-bold" name="view" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 				</div>
	// 			';
	// 		}
	// 	}
	// 	else
	// 	{
	// 		$sub_array[] = '
	// 			<div class="btn-group btn-group-md elevation-2">
	// 				<a href="#" class="btn btn-dark status text-bold" name="status" id="'.$row["id"].'"  data-customer="'.$user["last_name"].", ".$user["first_name"]." ".$user["middle_name"].'" 
	// 				data-reservation_id="'.$row["reservation_id"].'" data-bartender="'.$row["bartender_no"].'" data-status="Quoted" 
	// 				data-fees_amount="'.$row["fees_amount"].'" data-services_amount="'.$row["services_amount"].'" data-total_amount="'.number_format($row["total_amount"], 2, '.', '').'"
	// 				data-types="'.$row["types"].'" 
	// 				data-toggle="tooltip" data-placement="top" title="Quotation">Quotation</a>
	// 				<a href="#" class="btn btn-default view text-bold" name="view" id="'.$row["id"].'" data-toggle="tooltip" data-placement="top" title="View">View</a>
	// 			</div>
	// 		';
	// 	}
	// }
	
	// if($row['status'] == 'Pending')
	// {
	// 	$sub_array[] = '<span class="badge badge-secondary pl-3 pr-3 p-2">Pending</span>';
	// }
	// else if($row['status'] == 'Quoted' || $row['status'] == 'Approved')
	// {
	// 	$sub_array[] = '<span class="badge badge-primary pl-3 pr-3 p-2">'.$row["status"].'</span>';
	// }
	// else if($row['status'] == 'Accepted' || $row['status'] == 'Reserved' || $row['status'] == 'Processing')
	// {
	// 	if (!empty($row["payment_method"]))
	// 	{
	// 		if ($row["payment_method"] == 'Full Payment')
	// 		{
	// 			if (!empty($row["payment_one_date"]))
	// 			{
	// 				$sub_array[] = '<span class="badge badge-primary pl-3 pr-3 p-2">'.$row['status'].'</span>'
	// 				."<br><b>Payment Method:</b> ".$row["payment_method"]
	// 				."<br><b>Total Amount:</b> ₱".number_format($row['total_amount'], 2, '.', ',')
	// 				."<br><b class='text-success'>Date Paid:</b> ".$row["payment_one_date"]
	// 				."<br><b class='text-success'>Sender:</b> ".$row["payment_one_sender"]
	// 				."<br><b class='text-success'>Trans./Ref. No.:</b> ".$row["payment_one_no"]
	// 				.'<br><a href="'.$row["contract_image"].'" download="'.$row["contract_image"].'">Contract</a>';
	// 			}
	// 			else
	// 			{
	// 				$sub_array[] = '<span class="badge badge-primary pl-3 pr-3 p-2">'.$row['status'].'</span>'
	// 				."<br><b>Payment Method:</b> ".$row["payment_method"]
	// 				."<br><b>Total Amount:</b> ₱".number_format($row['total_amount'], 2, '.', ',')
	// 				."<br><b >Due Date:</b> ".$row["payment_one_due"]
	// 				.'<br><a href="'.$row["contract_image"].'" download="'.$row["contract_image"].'">Contract</a>';
	// 			}
	// 		}
	// 		else
	// 		{
	// 			$date = substr($row["date_accepted"], 6,4)."-".substr($row["date_accepted"], 0, 2)."-".substr($row["date_accepted"], 3, 2);
	// 			$event_date = substr($row["event_date"], 6,4)."-".substr($row["event_date"], 0, 2)."-".substr($row["event_date"], 3, 2);
		
	// 			$one_paid = !empty($row["payment_one_date"]) ? "<br><b class='text-success'>Date Paid:</b> ".$row["payment_one_date"]
	// 			."<br><b class='text-success'>Sender:</b> ".$row["payment_one_sender"]
	// 			."<br><b class='text-success'>Trans./Ref. No.:</b> ".$row["payment_one_no"] :  "<br><b>Due Date:</b> ".$row["payment_one_due"];

	// 			$two_paid = !empty($row["payment_two_date"]) ? "<br><b class='text-success'>Date Paid:</b> ".$row["payment_two_date"]
	// 			."<br><b class='text-success'>Sender:</b> ".$row["payment_two_sender"]
	// 			."<br><b class='text-success'>Trans./Ref. No.:</b> ".$row["payment_two_no"] : "<br><b>Due Date:</b> ".$row["payment_two_due"];//date('M d, Y', strtotime($event_date. ' - 15 days'));

	// 			$three_paid = !empty($row["payment_three_date"]) ? "<br><b class='text-success'>Date Paid:</b> ".$row["payment_three_date"]
	// 			."<br><b class='text-success'>Sender:</b> ".$row["payment_three_sender"]
	// 			."<br><b class='text-success'>Trans./Ref. No.:</b> ".$row["payment_three_no"] : "<br><b>Due Date:</b> ".$row["payment_three_due"];//date('M d, Y', strtotime($event_date. ' - 7 days'));
		
	// 			$ten = (10 / 100) * floatval($row['total_amount']);
	// 			$forty = (40 / 100) * floatval($row['total_amount']);
	// 			$fifty = (50 / 100) * floatval($row['total_amount']);
	// 			$sub_array[] = '<span class="badge badge-primary pl-3 pr-3 p-2">'.$row['status'].'</span>'
	// 			."<br><b>Total Amount:</b> ₱".number_format($row['total_amount'], 2, '.', ',')
	// 			."<br><b>10%:</b> ₱".number_format($ten, 2, '.', ',').$one_paid
	// 			."<br><b>40%:</b> ₱".number_format($forty, 2, '.', ',').$two_paid
	// 			."<br><b>50%:</b> ₱".number_format($fifty, 2, '.', ',').$three_paid
	// 			.'<br><a href="'.$row["contract_image"].'" download="'.$row["contract_image"].'">Contract</a>';
	// 		}
	// 	}
	// 	else
	// 	{
	// 		$sub_array[] = '<span class="badge badge-primary pl-3 pr-3 p-2">'.$row['status'].'</span>'
	// 		.'<br><a href="'.$row["contract_image"].'" download="'.$row["contract_image"].'">Contract</a>';
	// 	}
	// }
	// else if($row['status'] == 'Completed')
	// {
	// 	$star = '';
	// 	if(!empty($row['feedback']))
	// 	{
	// 		for ($x = 1; $x <= $row["rating"]; $x++) {
	// 			$star .= '<i class="fa fa-star text-dark"></i>';
	// 		}
	// 	}
	// 	$feedback = !empty($row["feedback"]) ? "<br><b>Feedback:</b> ".$row["feedback"]." ".$star : "";

	// 	if ($row["payment_method"] == 'Full Payment')
	// 	{
	// 		$sub_array[] = '<span class="badge badge-success pl-3 pr-3 p-2">Fully Paid</span>'
	// 		."<br><b>Payment Method:</b> ".$row["payment_method"]
	// 		."<br><b>Total Amount:</b> ₱".number_format($row['total_amount'], 2, '.', ',')
	// 		."<br><b class='text-success'>Date Paid:</b> ".$row["payment_one_date"]
	// 		."<br><b class='text-success'>Sender:</b> ".$row["payment_one_sender"]
	// 		."<br><b class='text-success'>Trans./Ref. No.:</b> ".$row["payment_one_no"]
	// 		.$feedback
	// 		.'<br><a href="'.$row["contract_image"].'" download="'.$row["contract_image"].'">Contract</a>';
	// 	}
	// 	else
	// 	{
	// 		$date = substr($row["date_accepted"], 6,4)."-".substr($row["date_accepted"], 0, 2)."-".substr($row["date_accepted"], 3, 2);
	// 		$event_date = substr($row["event_date"], 6,4)."-".substr($row["event_date"], 0, 2)."-".substr($row["event_date"], 3, 2);
	
	// 		$one_paid = !empty($row["payment_one"]) ? "<br><b class='text-success'>Date Paid:</b> ".$row["payment_one_date"]
	// 		."<br><b class='text-success'>Sender:</b> ".$row["payment_one_sender"]
	// 		."<br><b class='text-success'>Trans./Ref. No.:</b> ".$row["payment_one_no"] : "";
	// 		$two_paid = !empty($row["payment_two"]) ? "<br><b class='text-success'>Date Paid:</b> ".$row["payment_two_date"]
	// 		."<br><b class='text-success'>Sender:</b> ".$row["payment_two_sender"]
	// 		."<br><b class='text-success'>Trans./Ref. No.:</b> ".$row["payment_two_no"] : "";
	// 		$three_paid = !empty($row["payment_three"]) ? "<br><b class='text-success'>Date Paid:</b> ".$row["payment_three_date"]
	// 		."<br><b class='text-success'>Sender:</b> ".$row["payment_three_sender"]
	// 		."<br><b class='text-success'>Trans./Ref. No.:</b> ".$row["payment_three_no"] : "";
			
	// 		$ten = (10 / 100) * floatval($row['total_amount']);
	// 		$forty = (40 / 100) * floatval($row['total_amount']);
	// 		$fifty = (50 / 100) * floatval($row['total_amount']);
	// 		$sub_array[] = '<span class="badge badge-success pl-3 pr-3 p-2">Fully Paid</span>'
	// 		."<br><b>Total Amount:</b> ₱".number_format($row['total_amount'], 2, '.', ',')
	// 		."<br><b>10%:</b> ₱".number_format($ten, 2, '.', ',').$one_paid
	// 		."<br><b>40%:</b> ₱".number_format($forty, 2, '.', ',').$two_paid
	// 		."<br><b>50%:</b> ₱".number_format($fifty, 2, '.', ',').$three_paid
	// 		.$feedback
	// 		.'<br><a href="'.$row["contract_image"].'" download="'.$row["contract_image"].'">Contract</a>';
	// 	}
	// }
	// else
	// {
	// 	$reason = $row["status"] !== "Declined" ? "" :  "<br><b>Reason:</b> ".$row['reason'];
	// 	$sub_array[] = '<span class="badge badge-danger pl-3 pr-3 p-2">'.$row['status'].'</span>'.$reason;
	// }

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
		$query .= " WHERE client_id = '".$_SESSION["user_id"]."' ";
	}
	$statement = $connect->prepare("SELECT * FROM $TABLE $query");
	$statement->execute();
	return $statement->rowCount();
}

echo json_encode($output);

?>