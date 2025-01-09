<?php
    
include('config.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'login' )
	{
        $result = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE email = '".trim($_POST["email"])."' ");
        if($result)
        {
            if($result['status'] == 'Active')
            {
                if(password_verify(trim($_POST["password"]), $result["password"]))
                {
                    $_SESSION['user_name']  = $result['email'];
                    $_SESSION['user_type']  = $result['user_type'];
                    $_SESSION['user_id']    = $result['id'];
                    $output['status'] = true;
                }
                else
                {
                    $output['status'] = false;
                    $output['message'] = 'Wrong password!';
                }
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'Invalid Account!';
            }
        }
        else
        {
            $output['status'] = false;
            $output['message'] = 'Account does not exist';
        }
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'profile' )
	{
        $password = password_hash(trim($_POST["password_profile"]), PASSWORD_DEFAULT);
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $USER_TABLE SET password = '".$password."' WHERE id = '".$_SESSION['user_id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully saved.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully saved.';
        }
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'register' )
	{
        if ($_POST["steps"] == '1')
        {
            $count = get_total_count($connect, "SELECT * FROM $USER_TABLE WHERE last_name = '".trim($_POST["last_name"])."' AND first_name = '".trim($_POST["first_name"])."' AND middle_name = '".trim($_POST["middle_name"])."' ");
            if ($count > 0)
            {
                $output['status'] = false;
                $output['message'] = 'Fullname already exist.';
            }
            else 
            {
                $count = get_total_count($connect, "SELECT * FROM $USER_TABLE WHERE email = '".trim($_POST["email"])."' ");
                if ($count > 0)
                {
                    $output['status'] = false;
                    $output['message'] = 'Email already exist.';
                }
                else
                {
                    $email_code = rand(999,9999);
                    $connect->beginTransaction();
                    $create = query($connect, "INSERT INTO $USER_TABLE (last_name, first_name, middle_name, email, email_code, contact, address, user_type, status, date_created) VALUES 
                    ('".trim($_POST["last_name"])."', '".trim($_POST["first_name"])."', '".trim($_POST["middle_name"])."', '".trim($_POST["email"])."', '".$email_code."', 
                     '".trim($_POST["contact"])."', '".trim($_POST["address"])."', 
                    'Client', 'Activation', '".date("m-d-Y h:i A")."') ");
                    if ($create == true)
                    {
                        //send email
                        $mail = send_mail(trim($_POST["email"]), 
                        trim($_POST['last_name']).", ".trim($_POST["first_name"])." ".trim($_POST["middle_name"]), 
                        'ACCOUNT ACTIVATION', 
                        'Greetings!, 
                        <br> <br>Thank you for creating an account, use this activation code : <b>'.$email_code.'</b> to complete your registration. <br> <br> 
                        Online Mobile Cart Reservation System <br> <br> <i>This is a system generated email. Do not reply.<i>');
                        if ($mail)
                        {
                            $connect->commit();
                            $output['status'] = true;
                            $output['message'] = 'Successfully registered.';
                        }
                        else 
                        {
                            $connect->rollBack();
                            $output['status'] = false;
                            $output['message'] = 'Unsuccessfully registered.';
                        }
                    }
                    else 
                    {
                        $connect->rollBack();
                        $output['status'] = false;
                        $output['message'] = 'Unsuccessfully registered.';
                    }
                }
            }
        }
        else if ($_POST["steps"] == '2')
        {
            $count = get_total_count($connect, "SELECT * FROM $USER_TABLE WHERE email = '".trim($_POST["email"])."' AND email_code = '".trim($_POST["email_code"])."' ");
            if ($count > 0)
            {
                $output['status'] = true;
                $output['message'] = 'Successfully verified.';
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'Email code does not exist.';
            }
        }
        else
        {
            $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
            $connect->beginTransaction();
            $update = query($connect, "UPDATE $USER_TABLE SET password = '".$password."', status = 'Active' WHERE email = '".trim($_POST["email"])."' ");
            if ($update == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully registered.';
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully registered.';
            }
        }
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'forgot' )
	{
        if ($_POST["steps"] == '1')
        {
            $result = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE email = '".trim($_POST["email"])."' ");
            if($result)
            {
                if($result['status'] == 'Active')
                {
                    $email_code = rand(999,9999);
                    $connect->beginTransaction();
                    $update = query($connect, "UPDATE $USER_TABLE SET email_code = '".$email_code."' WHERE email = '".trim($_POST["email"])."' ");
                    if ($update == true)
                    {
                        //send email
                        $mail = send_mail(trim($_POST["email"]), 
                        trim($result['last_name']).", ".trim($result["first_name"])." ".trim($result["middle_name"]), 
                        'FORGOT PASSWORD', 
                        'Greetings!, 
                        <br> <br>You request for forgot password email code: '.$email_code.' <br> <br> Disregard this email if you didn\'t request. <br> <br> 
                        Online Mobile Cart Reservation System <br> <br> <i>This is a system generated email. Do not reply.<i>');
                        if ($mail)
                        {
                            $connect->commit();
                            $output['status'] = true;
                            $output['message'] = 'Successfully requested.';
                        }
                        else
                        {
                            $connect->rollBack();
                            $output['status'] = false;
                            $output['message'] = 'Unsuccessfully requested.';
                        }
                    }
                    else 
                    {
                        $connect->rollBack();
                        $output['status'] = false;
                        $output['message'] = 'Unsuccessfully requested.';
                    }
                }
                else
                {
                    $output['status'] = false;
                    $output['message'] = 'Email does not exist.';
                }
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'Email does not exist.';
            }
        }
        else if ($_POST["steps"] == '2')
        {
            $count = get_total_count($connect, "SELECT * FROM $USER_TABLE WHERE email = '".trim($_POST["email"])."' AND email_code = '".trim($_POST["email_code"])."' ");
            if ($count > 0)
            {
                $output['status'] = true;
                $output['message'] = 'Successfully verified.';
            }
            else
            {
                $output['status'] = false;
                $output['message'] = 'Email code does not exist.';
            }
        }
        else
        {
            $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
            $connect->beginTransaction();
            $update = query($connect, "UPDATE $USER_TABLE SET password = '".$password."' WHERE email = '".trim($_POST["email"])."' ");
            if ($update == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully reset password.';
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully reset password.';
            }
        }
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Products_add' )
	{
        $count = get_total_count($connect, "SELECT * FROM $PRODUCT_TABLE WHERE product = '".trim($_POST["product"])."' AND category_id = '".trim($_POST["category_id"])."' ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Product already exist.';
        }
        else 
        {
            // description, '".trim($_POST["description"])."', 
            $connect->beginTransaction();
			
			$image = addslashes(file_get_contents($_FILES['files']['tmp_name']));
			$image_name = addslashes($_FILES['files']['name']);
			$image_size = getimagesize($_FILES['files']['tmp_name']);
			move_uploaded_file($_FILES["files"]["tmp_name"], "assets/products/" . $_FILES["files"]["name"]);
			$location   =  $_FILES["files"]["name"];

			
            $create = query($connect, "INSERT INTO $PRODUCT_TABLE (category_id, product, price, products,  status, date_created) VALUES 
            ('".trim($_POST["category_id"])."', '".trim($_POST["product"])."', '".trim($_POST["price"])."', '".trim($location)."', 'Active','".date("m-d-Y h:i A")."') ");
            if ($create == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully saved.';
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully saved.';
            }
        }
		header("location:products.php?added");
	}
	
	if($_POST['btn_action'] == 'Products_fetch' )
	{
        $result = fetch_row($connect, "SELECT * FROM $PRODUCT_TABLE WHERE id = '".$_POST["id"]."' ");
        $output['category_id'] = $result['category_id'];
        $output['product'] = $result['product'];
        $output['price'] = $result['price'];
        // $output['description'] = $result['description'];
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Products_update' )
	{
        $count = get_total_count($connect, "SELECT * FROM $PRODUCT_TABLE WHERE id != '".$_POST["id"]."' AND product = '".trim($_POST["product"])."' AND category_id = '".trim($_POST["category_id"])."'  ");
        if($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Product already exist.';
        }
        else
        {
			
			$image = addslashes(file_get_contents($_FILES['files']['tmp_name']));
			$image_name = addslashes($_FILES['files']['name']);
			$image_size = getimagesize($_FILES['files']['tmp_name']);
			move_uploaded_file($_FILES["files"]["tmp_name"], "assets/products/" . $_FILES["files"]["name"]);
			$location   =  $_FILES["files"]["name"];

            // , 
            // description = '".trim($_POST["description"])."' 
            $connect->beginTransaction();
            $update = query($connect, "UPDATE $PRODUCT_TABLE SET 
                category_id = '".trim($_POST["category_id"])."', 
                product = '".trim($_POST["product"])."', 
                products = '".trim($_POST["location"])."', 
                price = '".trim($_POST["price"])."'
            WHERE id = '".$_POST['id']."' ");
            if ($update == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully saved.';
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully saved.';
            }
        }
		header("location:products.php?updated");
	}

	if($_POST['btn_action'] == 'Products_status' )
	{
		if($_POST['status'] == 'Active')
		{
			$status = 'Inactive';	
		}
        else
        {
			$status = 'Active';	
        }
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $PRODUCT_TABLE SET status = '".$_POST['status']."' WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully saved.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully saved.';
        }
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Category_add' )
	{
        $count = get_total_count($connect, "SELECT * FROM $CATEGORY_TABLE WHERE category = '".trim($_POST["category"])."'");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Category already exist.';
        }
        else 
        {
            $connect->beginTransaction();
            $create = query($connect, "INSERT INTO $CATEGORY_TABLE (category, status, date_created) VALUES 
            ('".trim($_POST["category"])."', 'Active','".date("m-d-Y h:i A")."') ");
            if ($create == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully saved.';
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully saved.';
            }
        }
		echo json_encode($output);
	}
	
	if($_POST['btn_action'] == 'Category_fetch' )
	{
        $result = fetch_row($connect, "SELECT * FROM $CATEGORY_TABLE WHERE id = '".$_POST["id"]."' ");
        $output['category'] = $result['category'];
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Category_update' )
	{
        $count = get_total_count($connect, "SELECT * FROM $CATEGORY_TABLE WHERE id != '".$_POST["id"]."' AND category = '".trim($_POST["category"])."' ");
        if($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Category already exist.';
        }
        else
        {
            $connect->beginTransaction();
            $update = query($connect, "UPDATE $CATEGORY_TABLE SET 
                category = '".trim($_POST["category"])."'  
            WHERE id = '".$_POST['id']."' ");
            if ($update == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully saved.';
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully saved.';
            }
        }
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Category_status' )
	{
		if($_POST['status'] == 'Active')
		{
			$status = 'Inactive';	
		}
        else
        {
			$status = 'Active';	
        }
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $CATEGORY_TABLE SET status = '".$_POST['status']."' WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully saved.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully saved.';
        }
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'Event Types_add' )
	{
        $count = get_total_count($connect, "SELECT * FROM $EVENTS_TABLE WHERE event = '".trim($_POST["event"])."'");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Event already exist.';
        }
        else 
        {
            $connect->beginTransaction();
            $create = query($connect, "INSERT INTO $EVENTS_TABLE (event, status, date_created) VALUES 
            ('".trim($_POST["event"])."', 'Active','".date("m-d-Y h:i A")."') ");
            if ($create == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully saved.';
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully saved.';
            }
        }
		echo json_encode($output);
	}
	
	if($_POST['btn_action'] == 'Event Types_fetch' )
	{
        $result = fetch_row($connect, "SELECT * FROM $EVENTS_TABLE WHERE id = '".$_POST["id"]."' ");
        $output['event'] = $result['event'];
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Event Types_update' )
	{
        $count = get_total_count($connect, "SELECT * FROM $EVENTS_TABLE WHERE id != '".$_POST["id"]."' AND event = '".trim($_POST["event"])."' ");
        if($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'Event already exist.';
        }
        else
        {
            $connect->beginTransaction();
            $update = query($connect, "UPDATE $EVENTS_TABLE SET 
                event = '".trim($_POST["event"])."'
            WHERE id = '".$_POST['id']."' ");
            if ($update == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully saved.';
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully saved.';
            }
        }
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Event Types_status' )
	{
		if($_POST['status'] == 'Active')
		{
			$status = 'Inactive';	
		}
        else
        {
			$status = 'Active';	
        }
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $EVENTS_TABLE SET status = '".$_POST['status']."' WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully saved.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully saved.';
        }
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'Reservation_add' )
	{
        $count = get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE client_id = '".$_SESSION["user_id"]."' AND status = 'Pending' ");
        if ($count > 0)
        {
            $output['status'] = false;
            $output['message'] = 'You have pending reservation. Please wait for the owner to process your reservation.';
        }
        else 
        {
            $reservation_id = '';
            $result = fetch_row($connect,"SELECT * FROM $RESERVATION_TABLE ORDER BY id DESC LIMIT 1 ");
            if ($result)
            {
                if ( date('Ym') == substr($result['reservation_id'], 3, 6) )
                {
                    $add = intval(substr($result['reservation_id'], 9)) + 1;
                    if (strlen($add) == 1) { $add = "000".$add; }
                    if (strlen($add) == 2) { $add = "00".$add; }
                    if (strlen($add) == 3) { $add = "0".$add; }
                    $reservation_id = "ID-".date('Ym').$add;
                } 
                else { $reservation_id = "ID-".date('Ym').'0001'; }
            }
            else { $reservation_id = "ID-".date('Ym').'0001';  }

            if ($_POST['types'] == 'Beverage')
            {
                // validate with status
                $count = get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE event_date = '".trim($_POST["event_date"])."' AND event_time = '".trim($_POST["event_time"])."' ");
                if ($count > 0)
                {
                    $output['status'] = false;
                    $output['message'] = 'Please select another date or time.';
                }
                else
                {
                    // $events = fetch_row($connect, "SELECT * FROM $EVENTS_TABLE WHERE id = '".$_POST["events_id"]."' "); // remove
                    // $bartender_no = !empty(trim($_POST["bartender"])) ? trim($_POST["bartender_no"]) : 0;
                    $events = trim($_POST["events_id"]) != 'Other' ? trim($_POST["events_id"]) : trim($_POST["other"]);
                    $bartender_no = trim($_POST["bartender"]) != 'No' ? trim($_POST["bartender_no"]) : 0;
        
                    $date = trim($_POST["event_date"]);
                    $event_date = substr($date, 6,4)."-".substr($date, 0, 2)."-".substr($date, 3, 2);
        
                    $date1 = new DateTime();
                    $date2 = new DateTime($event_date);
                    $interval = $date1->diff($date2);
        
                    // payment date
                    if ($interval->days > 14)
                    {
                        $payment_method = 'Installment';
                        $payment_one_due = date('m-d-Y', strtotime($event_date. ' - 15 days'));
                        $payment_two_due = date('m-d-Y', strtotime($event_date. ' - 7 days'));
                        $payment_three_due = date('m-d-Y', strtotime($event_date. ' - 5 days'));
                    }
                    else
                    {
                        $payment_method = 'Full Payment';
                        $payment_two_due = '';
                        $payment_three_due = '';
        
                        if ($interval->days > 5)
                        {
                            $payment_one_due = date('m-d-Y', strtotime($event_date. ' - 5 days'));
                        }
                        else
                        {
                            $payment_one_due = $event_date;
                        }
                    }
        
                    $connect->beginTransaction();
					if($_POST["types"]=='Cocktails'){
						$stat = 'Meeting';
					} else {
						$stat = 'Pending';
					}
                    $create = query($connect, "INSERT INTO $RESERVATION_TABLE 
                        (types, reservation_id, client_id, events_id, event_date, event_time, total_days, total_hours, guests, event_place, address, notes, bartender, bartender_no, 
                        payment_method, payment_one_due, payment_two_due, payment_three_due, status, date_created) 
                    VALUES 
                        ('".trim($_POST["types"])."', '".$reservation_id."', '".$_SESSION["user_id"]."', 
                        '".$events."', '".trim($_POST["event_date"])."', '".trim($_POST["event_time"])."', 
                        '".trim($_POST["total_days"])."', '".trim($_POST["total_hours"])."', '".trim($_POST["guests"])."', 
                        '".trim($_POST["event_place"])."', '".trim($_POST["address"])."', 
                        '".trim($_POST["notes"])."', '".trim($_POST["bartender"])."', '".$bartender_no."', 
                        '".$payment_method."', '".$payment_one_due."', '".$payment_two_due."', '".$payment_three_due."', 
                        '".$stat."','".date("m-d-Y h:i A")."') ");
        
                    $update = query($connect, "UPDATE $ITEMS_TABLE SET reservation_id = '".$reservation_id."' WHERE reservation_id = '' ");
        
                    if ($create == true && $update == true)
                    {
                        $connect->commit();
                        $output['status'] = true;
                        $output['message'] = 'Successfully booked.';
                    }
                    else 
                    {
                        $connect->rollBack();
                        $output['status'] = false;
                        $output['message'] = 'Unsuccessfully booked.';
                    }
                }
            }
            else
            {
                $count = get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE event_date = '".trim($_POST["event_date"])."' AND event_time = '".trim($_POST["event_time"])."' ");
                if ($count > 0)
                {
                    $output['status'] = false;
                    $output['message'] = 'Please select another date or time.';
                }
                else
                {
                    $connect->beginTransaction();
					if($_POST["types"]=='Cocktails'){
						$stat = 'Meeting';
					} else {
						$stat = 'Pending';
					}
                    $create = query($connect, "INSERT INTO $RESERVATION_TABLE 
                        (types, reservation_id, client_id, events_id, event_date, event_time, total_days, total_hours, guests, event_place, address, notes, bartender, bartender_no, 
                        payment_method, payment_one_due, payment_two_due, payment_three_due, status, date_created) 
                    VALUES 
                        ('".trim($_POST["types"])."', '".$reservation_id."', '".$_SESSION["user_id"]."', '', '".trim($_POST["event_date"])."', '".trim($_POST["event_time"])."', 
                        '', '', '', '', '', 
                        '".trim($_POST["notes"])."', '', '0', 
                        '', '', '', '', 
                        '".$stat."','".date("m-d-Y h:i A")."') ");
        
                    if ($create == true)
                    {
                        $connect->commit();
                        $output['status'] = true;
                        $output['message'] = 'Successfully booked.';
                    }
                    else 
                    {
                        $connect->rollBack();
                        $output['status'] = false;
                        $output['message'] = 'Unsuccessfully booked.';
                    }
                }
            }
        }
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'Reservation_view' )
	{
        $result = fetch_row($connect, "SELECT * FROM $RESERVATION_TABLE WHERE id = '".$_POST["id"]."' ");
        $output['types'] = $result['types'];
        $output['events_id'] = $result['events_id'];
        $output['event_date'] = $result['event_date'];
        $output['event_time'] = $result['event_time'];
        $output['total_days'] = $result['total_days'];
        $output['total_hours'] = $result['total_hours'];
        $output['guests'] = $result['guests'];
        $output['address'] = $result['address'];
        $output['event_place'] = $result['event_place'];
        $output['notes'] = $result['notes'];
        $output['bartender'] = $result['bartender'];
        $output['reservation_id'] = $result['reservation_id'];
        $output['status'] = $result['status'];
        $output['rev_id'] = $result['id'];
        $output['feedback'] = $result['feedback'];
		echo json_encode($output);
    }
    
	if($_POST['btn_action'] == 'Reservation_status' )
    {
        if ($_POST["status"] == 'Declined' || $_POST["status"] == 'Cancelled' || $_POST["status"] == 'Reserved')
        {
            $result = fetch_row($connect, "SELECT * FROM $RESERVATION_TABLE WHERE id = '".$_POST["id"]."' AND status NOT IN ('Pending') ");
        }
        else if ($_POST["status"] == 'Quoted')
        {
            $result = fetch_row($connect, "SELECT * FROM $RESERVATION_TABLE WHERE id = '".$_POST["id"]."' AND status NOT IN ('Reserved') ");
        }
        else if ($_POST["status"] == 'Completed')
        {
            $result = fetch_row($connect, "SELECT * FROM $RESERVATION_TABLE WHERE id = '".$_POST["id"]."' AND status NOT IN ('Payment') ");
        }
        if ($result)
        {
            $output['status'] = false;
            $output['message'] = 'It\'s already been '.strtolower($result["status"]).'.';
        }
        else 
        {
            $reservation = fetch_row($connect, "SELECT * FROM $RESERVATION_TABLE WHERE id = '".$_POST["id"]."' ");
            $query = "";
            if ($_POST["status"] == 'Declined')
            {
                $query = ", reason = '".trim($_POST["reason"])."'";
            }
            else if ($_POST["status"] == 'Quoted')
            {
                $image = $_FILES["file"]["name"];
                $docs = strpos($image, 'docs');
                $pdf = strpos($image, 'pdf');
                $type = $docs !== false ? 'docs' : ($pdf !== false ? 'pdf' : 'false' );
                if ($type == 'false')
                {
                    $output['status'] = false;
                    $output['message'] = "Invalid file type, please upload a docs or pdf.";
                    echo json_encode($output);
                    return;
                }

                if ($_POST["types"] == 'Cocktails')
                {
                    // validate with status
                    $count = get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE event_date = '".trim($_POST["event_date"])."' AND event_time = '".trim($_POST["event_time"])."' AND id != '".$_POST["id"]."' ");
                    if ($count > 0)
                    {
                        $output['status'] = false;
                        $output['message'] = 'Please select another date or time.';
                        echo json_encode($output);
                        return;
                    }
                    
                    $date = trim($_POST["event_date"]);
                    $event_date = substr($date, 6,4)."-".substr($date, 0, 2)."-".substr($date, 3, 2);
        
                    $date1 = new DateTime();
                    $date2 = new DateTime($event_date);
                    $interval = $date1->diff($date2);
        
                    if ($interval->days > 14)
                    {
                        $payment_method = 'Installment';
                        $payment_one_due = date('m-d-Y', strtotime($event_date. ' - 15 days'));
                        $payment_two_due = date('m-d-Y', strtotime($event_date. ' - 7 days'));
                        $payment_three_due = date('m-d-Y', strtotime($event_date. ' - 5 days'));
                    }
                    else
                    {
                        $payment_method = 'Full Payment';
                        $payment_two_due = '';
                        $payment_three_due = '';
        
                        if ($interval->days > 5)
                        {
                            $payment_one_due = date('m-d-Y', strtotime($event_date. ' - 5 days'));
                        }
                        else
                        {
                            $payment_one_due = $event_date;
                        }
                    } // '".$payment_method."', '".$payment_one_due."', '".$payment_two_due."', '".$payment_three_due."', 

                    $events = trim($_POST["events_id"]) != 'Other' ? trim($_POST["events_id"]) : trim($_POST["other"]);
                    $query .= ", events_id = '".$events."', event_date = '".trim($_POST["event_date"])."', event_time = '".trim($_POST["event_time"])."', 
                    address = '".trim($_POST["address"])."', 
                    payment_method = '".$payment_method."', payment_one_due = '".$payment_one_due."', 
                    payment_two_due = '".$payment_two_due."', payment_three_due = '".$payment_three_due."' ";
                }
                
                $file_type = array("pdf", "docs");
                $upload = upload_image($_FILES["file"], $reservation["reservation_id"], 'assets/contract/', $file_type, $type);
                if ($upload["status"] == false)
                {
                    $output['status'] = false;
                    $output['message'] = $upload["message"];
                    echo json_encode($output);
                    return;
                }

                $total_sum = get_total_sum($connect, "SELECT SUM(total_price) AS total FROM $ITEMS_TABLE WHERE reservation_id = (SELECT reservation_id FROM $RESERVATION_TABLE WHERE id = '".$_POST["id"]."') ");
                $total_amount = floatval(trim($_POST["services_amount"]))  + floatval($total_sum); // + floatval(trim($_POST["fees_amount"])) // , fees_amount = '".trim($_POST["fees_amount"])."'
                $query .= ", contract_image = '".$upload["message"]."', date_accepted = '".date("m-d-Y h:i A")."', fees_amount = '".$total_sum."', total_amount = '".$total_amount."', services_amount = '".trim($_POST["services_amount"])."' "; // check total date from event date then check if installment o full payment 
            }
            $connect->beginTransaction();
            $update = query($connect, "UPDATE $RESERVATION_TABLE SET status = '".$_POST["status"]."' $query WHERE id = '".$_POST['id']."' ");
            if ($update == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully '.strtolower($_POST["status"]).'.';

                // reserved, qouted, complete
                if ($_POST["status"] == 'Reserved' || $_POST["status"] == 'Quoted' || $_POST["status"] == 'Completed' || $_POST["status"] == 'Declined')
                {
                    $message = 'Reservation reserved.';
                    if ($_POST["status"] == 'Quoted')
                    {
                        $message = 'Reservation quoted.';
                    }
                    if ($_POST["status"] == 'Completed')
                    {
                        $message = 'Reservation completed.';
                    }
                    if ($_POST["status"] == 'Declined')
                    {
                        $message = 'Reservation declined.';
                    }
                    $output['title'] = "Reservation Status";
                    $output['body'] = $reservation["reservation_id"]." - ".$message;
                    notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $reservation["reservation_id"], $output['title'], $message, $reservation["client_id"]);
                    $output['id'] = $connect->lastInsertId();
                }
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully '.strtolower($_POST["status"]).'.';
            }
        }
		echo json_encode($output);
    }
    
	if($_POST['btn_action'] == 'Reservation_payment' )
    {
        $reservation = fetch_row($connect, "SELECT * FROM $RESERVATION_TABLE WHERE '".trim($_POST['payment_no'])."' IN (payment_one_no,payment_two_no,payment_three_no) AND status IN ('Completed','Payment') ");
        if ($reservation)
        {
            $output['status'] = false;
            $output['message'] = 'Transaction/Reference No. already exists.';
            echo json_encode($output);
            return;
        }

        // notify to admin about payment
        if ($_FILES["files"]["size"] == 0)
        {
            $output['status'] = false;
            $output['message'] = 'Please upload an image.';
            echo json_encode($output);
            return;
        }
        
        $image = $_FILES["files"]["name"];
        $png = strpos($image, 'png');
        $jpg = strpos($image, 'jpg');
        $jpeg = strpos($image, 'jpeg');
        $type = $png !== false ? 'png' : ($jpg !== false ? 'jpg' : ($jpeg !== false ? 'jpeg' : 'false' ));
        if ($type == 'false')
        {
            $output['status'] = false;
            $output['message'] = "Invalid file type, please upload a png, jpg or jpeg.";
            echo json_encode($output);
            return;
        }
        
        $file_type = array("jpg", "png", "jpeg");
        $result = fetch_row($connect, "SELECT * FROM $RESERVATION_TABLE WHERE id = '".$_POST["id"]."' ");
        // if ($result["status"] == 'Accepted')
        if (empty($result["payment_one_date"]))
        {
            $name = $result['payment_method'] == 'Full Payment' ? '_fullpayment' : '_tenthpayment';
            $upload = upload_image($_FILES["files"], $_POST["reservation_id"].$name, 'assets/payment/', $file_type, $type);
            if ($upload["status"] == false)
            {
                $output['status'] = false;
                $output['message'] = $upload["message"];
                echo json_encode($output);
                return;
            }
            
            // $status = $result['payment_method'] == 'Full Payment' ? 'Processing' : 'Reserved';
            $status = 'Payment';
            $connect->beginTransaction();
            $update = query($connect, "UPDATE $RESERVATION_TABLE SET 
                payment_one = '".$upload["message"]."', 
                payment_one_sender = '".trim($_POST['sender_name'])."', 
                payment_one_no = '".trim($_POST['payment_no'])."', 
                payment_one_server = '".trim($_POST['payment_server'])."', 
                payment_one_date = '".date("m-d-Y h:i A")."', 
                payment_one_amount = '".trim($_POST['payment_amount'])."',
                status = '".$status."' 
            WHERE id = '".$_POST['id']."' ");
            if ($update == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully paid.';
                
                // reservation id
                // title : fully paid
                // message : Customer name fully paid the event 
                $message = 'The customer paid an amount of '.trim($_POST['payment_amount']).' through '.trim($_POST['payment_server']).' with a Ref. #: '.trim($_POST['payment_no']);
                $output['title'] = $result['payment_method'] == 'Full Payment' ? 'Full payment' : "First Payment";
                $output['body'] = $result["reservation_id"]." - ".$message;
                notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $result["reservation_id"], $output['title'], $message, '1');
                $output['id'] = $connect->lastInsertId();
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully paid.';
            }
        }
        else
        {
            if (empty($result["payment_two_date"]))
            {
                $upload = upload_image($_FILES["files"], $_POST["reservation_id"].'_fortypayment', 'assets/payment/', $file_type, $type);
                if ($upload["status"] == false)
                {
                    $output['status'] = false;
                    $output['message'] = $upload["message"];
                    echo json_encode($output);
                    return;
                }
                
                $connect->beginTransaction();
                $update = query($connect, "UPDATE $RESERVATION_TABLE SET 
                    payment_two = '".$upload["message"]."', 
                    payment_two_sender = '".trim($_POST['sender_name'])."', 
                    payment_two_no = '".trim($_POST['payment_no'])."', 
                    payment_two_server = '".trim($_POST['payment_server'])."', 
                    payment_two_date = '".date("m-d-Y h:i A")."', 
                    payment_two_amount = '".trim($_POST['payment_amount'])."',
                    status = 'Processing' 
                WHERE id = '".$_POST['id']."' ");
                if ($update == true)
                {
                    $connect->commit();
                    $output['status'] = true;
                    $output['message'] = 'Successfully paid.';
                    
                    $message = 'The customer paid an amount of '.trim($_POST['payment_amount']).' through '.trim($_POST['payment_server']).' with a Ref. #: '.trim($_POST['payment_no']);
                    $output['title'] = "Second Payment";
                    $output['body'] = $result["reservation_id"]." - ".$message;
                    notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $result["reservation_id"], $output['title'], $message, '1');
                    $output['id'] = $connect->lastInsertId();
                }
                else 
                {
                    $connect->rollBack();
                    $output['status'] = false;
                    $output['message'] = 'Unsuccessfully paid.';
                }
            }
            else
            {
                $upload = upload_image($_FILES["files"], $_POST["reservation_id"].'_fiftypayment', 'assets/payment/', $file_type, $type);
                if ($upload["status"] == false)
                {
                    $output['status'] = false;
                    $output['message'] = $upload["message"];
                    echo json_encode($output);
                    return;
                }
                
                $connect->beginTransaction();
                $update = query($connect, "UPDATE $RESERVATION_TABLE SET 
                    payment_three = '".$upload["message"]."', 
                    payment_three_sender = '".trim($_POST['sender_name'])."', 
                    payment_three_no = '".trim($_POST['payment_no'])."', 
                    payment_three_server = '".trim($_POST['payment_server'])."', 
                    payment_three_date = '".date("m-d-Y h:i A")."', 
                    payment_three_amount = '".trim($_POST['payment_amount'])."'
                WHERE id = '".$_POST['id']."' ");
                if ($update == true)
                {
                    $connect->commit();
                    $output['status'] = true;
                    $output['message'] = 'Successfully paid.';
                    
                    $message = 'The customer paid an amount of '.trim($_POST['payment_amount']).' through '.trim($_POST['payment_server']).' with a Ref. #: '.trim($_POST['payment_no']);
                    $output['title'] = "Fully Paid";
                    $output['body'] = $result["reservation_id"]." - ".$message;
                    notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $result["reservation_id"], $output['title'], $message, '1');
                    $output['id'] = $connect->lastInsertId();
                }
                else 
                {
                    $connect->rollBack();
                    $output['status'] = false;
                    $output['message'] = 'Unsuccessfully paid.';
                }
            }
        }
		echo json_encode($output);
    }
    
	if($_POST['btn_action'] == 'Reservation_rate' )
    {
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $RESERVATION_TABLE SET feedback = '".trim($_POST["feedback"])."', rating = '".trim($_POST["ratings"])."' WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully saved.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully saved.';
        }
		echo json_encode($output);
    }
    
	if($_POST['btn_action'] == 'product_list' )
	{
        $products = '<select class="form-control select2" name="product_id" id="product_id" required >
        <option value="" >Select Product</option>"';
        $rslt = fetch_all($connect,"SELECT * FROM $PRODUCT_TABLE WHERE category_id = '".$_POST["category_id"]."' " );
        if ($rslt)
        {
            foreach($rslt as $row)
            {
                $products .= '<option value="'.$row["id"].'" >'.$row["product"].'</option>';
            }
        }
        $products .= '</select>';

        $output['products'] = $products;
		echo json_encode($output);
	}
    if($_POST['btn_action'] == 'fetch_image' )
	{
       
        $rslt = fetch_row($connect,"SELECT * FROM $PRODUCT_TABLE WHERE id = '".$_POST["value"]."' " );
       
        $output['image'] =  $rslt["products"];
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'item_delete' )
	{
        query($connect, "DELETE FROM  $ITEMS_TABLE WHERE reservation_id = '' ");
        $output['status'] = true;
		echo json_encode($output);
    }
    
	if($_POST['btn_action'] == 'items_list' )
	{
        if ($_POST["title"] == 'quotation')
        {
            // if ($_SESSION["user_type"] == 'Superadmin')
            // {
            //     $table = '
            //     <table id="datatables_items" class="table table-bordered ">
            //         <thead>
            //             <tr>
            //                 <th>Category</th>
            //                 <th>Product</th>
            //                 <th>Quantity</th>
            //                 <th>Price</th>
            //                 <th>Total</th>
            //             </tr>
            //         </thead>
            //         <tbody>';
            // }
            // else
            // {
            //     $table = '
            //     <table id="datatables_items" class="table table-bordered ">
            //         <thead>
            //             <tr>
            //                 <th>Category</th>
            //                 <th>Product</th>
            //                 <th>Quantity</th>
            //                 <th>Price</th>
            //             </tr>
            //         </thead>
            //         <tbody>';
            // }
            $table = '
            <table id="datatables_items" class="table table-bordered ">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>';
        }
        else if ($_POST["title"] == 'view')
        {
            $table = '
            <table id="datatables_items" class="table table-bordered ">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Product</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>';
        }
        else
        {
            $table = '
            <table id="datatables_items" class="table table-bordered ">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Category</th>
                        <th>Product</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>';
        }
        $count = 0;
        $total = 0;
        $quantity = 0;
        $total_price = 0;
        if ($_SESSION["user_type"] == 'Client')
        {
            $rslt = fetch_all($connect,"SELECT * FROM $ITEMS_TABLE WHERE reservation_id = '".$_POST["reservation_id"]."' AND user_id = '".$_SESSION["user_id"]."' " );
        }
        else
        {
            $rslt = fetch_all($connect,"SELECT * FROM $ITEMS_TABLE WHERE reservation_id = '".$_POST["reservation_id"]."' " );
        }
        foreach($rslt as $row)
        {
            $count = $count + 1;
            $total += floatval($row["product_price"]);
            $quantity += floatval($row["quantity"]);
            $total_price += floatval($row["total_price"]);
            if ($_POST["title"] == 'quotation')
            {
                $table .= '
                <tr>
                    <td>'.$row["category_name"].'</td>
                    <td>'.$row["product_name"].'</td>
                    <td>'.$row["quantity"].'</td>
                    <td>'.$row["product_price"].'</td>
                    <td>'.$row["total_price"].'</td>
                </tr>';
                // if ($_SESSION["user_type"] == 'Superadmin')
                // {
                //     $total += floatval($row["product_price"]);
                //     $quantity += floatval($row["quantity"]);
                //     $total_price += floatval($row["total_price"]);
                //     $table .= '
                //     <tr>
                //         <td>'.$row["category_name"].'</td>
                //         <td>'.$row["product_name"].'</td>
                //         <td>'.$row["quantity"].'</td>
                //         <td>'.$row["product_price"].'</td>
                //         <td>'.$row["total_price"].'</td>
                //     </tr>';
                // }
                // else
                // {
                //     $table .= '
                //     <tr>
                //         <td>'.$row["category_name"].'</td>
                //         <td>'.$row["product_name"].'</td>
                //         <td>'.$row["quantity"].'</td>
                //         <td>'.$row["product_price"].'</td>
                //     </tr>';
                // }
            }
            else if ($_POST["title"] == 'view')
            {
                $table .= '
                <tr>
                    <td>'.$row["category_name"].'</td>
                    <td>'.$row["product_name"].'</td>
                    <td>'.$row["quantity"].'</td>
                </tr>';
            }
            else
            {
                $table .= '
                <tr>
                    <td><div class="btn-group btn-group-md elevation-2">
                        <a href="#" class="btn btn-dark delete text-bold" name="delete" id="'.$row["id"].'" data-title="items"  data-toggle="tooltip" data-placement="top" title="Delete">Delete</a>
                    </div></td>
                    <td>'.$row["category_name"].'</td>
                    <td>'.$row["product_name"].'</td>
                    <td>'.$row["quantity"].'</td>
                </tr>';
            }
        }
        // if ($_SESSION["user_type"] == 'Superadmin')
        if ($_POST["title"] == 'quotation')
        {
            $table .= '
                </tbody>
                <tfooter>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>'.$quantity.'</th>
                        <th>'.$total.'</th>
                        <th>'.$total_price.'</th>
                    </tr>
                </tfooter>
            </table>';
        }
        else
        {
            $table .= '
                </tbody>
            </table>';
        }
        $output['table'] = $table;
        $output['count'] = $count;
        $output['total'] = $total;
        $output['quantity'] = $quantity;
        $output['total_price'] = $total_price;
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'items_add' )
	{
        $category = fetch_row($connect, "SELECT * FROM $CATEGORY_TABLE WHERE id = '".trim($_POST["category_id"])."' ");
        $products = fetch_row($connect, "SELECT * FROM $PRODUCT_TABLE WHERE category_id = '".trim($_POST["category_id"])."' AND id = '".trim($_POST["product_id"])."' ");
        
        $user_id = $_SESSION["user_id"];
        $reservation_id = '';
        if (isset($_POST["reservation_id"]))
        {
            $reserve = fetch_row($connect, "SELECT * FROM $RESERVATION_TABLE WHERE reservation_id = '".$_POST["reservation_id"]."' ");
            $reservation_id = $_POST["reservation_id"];
            $user_id = $reserve["client_id"];
        }
        $items = fetch_row($connect, "SELECT * FROM $ITEMS_TABLE WHERE category_name = '".$category["category"]."' AND product_name = '".$products["product"]."'
        AND reservation_id = '".$reservation_id."' AND user_id = '".$user_id."' ");
        if ($items)
        {
            // $output['status'] = false;
            // $output['message'] = trim($_POST["product_id"]).' already exists.';
            // echo json_encode($output);
            // return;
            
            $quantity = trim($_POST["quantity"]) + intval($items["quantity"]);
            $total_price = floatval($products["price"]) * intval($quantity);
            
            $connect->beginTransaction();
            $update = query($connect, "UPDATE $ITEMS_TABLE SET 
                quantity = '".$quantity."', 
                total_price = '".$total_price."' 
            WHERE id = '".$items['id']."' ");
            if ($update == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully saved.';
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully saved.';
            }
        }
        else
        {
            $quantity = trim($_POST["quantity"]);
            $product_name = $products["product"];
            $product_price = $products["price"];
            $total_price = floatval($products["price"]) * intval(trim($_POST["quantity"]));
    
            $connect->beginTransaction();
            $create = query($connect, "INSERT INTO $ITEMS_TABLE (user_id, reservation_id, category_name, product_name, quantity, product_price, total_price, date_created) VALUES 
            ('".$user_id."', '".$reservation_id."', '".$category["category"]."', '".$product_name."', '".$quantity."', '".$product_price."', '".$total_price."', '".date("m-d-Y h:i A")."') ");
            if ($create == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully saved.';
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully saved.';
            }
        }

		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'items_delete' )
	{
        $connect->beginTransaction();
        $create = query($connect, "DELETE FROM  $ITEMS_TABLE WHERE id = '".$_POST["id"]."' ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully deleted.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully deleted.';
        }
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'quotation_list' )
	{
        if ($_POST["title"] == 'view')
        {
            $table = '
            <table id="datatables_quotation" class="table table-bordered ">
                <thead>
                    <tr>
                        <th>Item Name</th>
                    </tr>
                </thead>
                <tbody>';
        }
        else
        {
            $table = '
            <table id="datatables_quotation" class="table table-bordered ">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Item Name</th>
                    </tr>
                </thead>
                <tbody>';
        }
        $count = 0;
        $rslt = fetch_all($connect,"SELECT * FROM $EVENT_ITEMS_TABLE WHERE reservation_id = '".$_POST["reservation_id"]."' " );
        foreach($rslt as $row)
        {
            if ($_POST["title"] == 'view')
            {
                $table .= '
                <tr>
                    <td>'.$row["name"].'</td>
                </tr>';
            }
            else
            {
                $table .= '
                <tr>
                    <td><div class="btn-group btn-group-md elevation-2">
                        <a href="#" class="btn btn-dark delete text-bold" name="delete" id="'.$row["id"].'" data-title="quotation" data-toggle="tooltip" data-placement="top" title="Delete">Delete</a>
                    </div></td>
                    <td>'.$row["name"].'</td>
                </tr>';
            }
            $count = $count + 1;
        }
        $output['table'] = $table;
        $output['count'] = $count;
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'quotation_add' )
	{
        $connect->beginTransaction();
        $create = query($connect, "INSERT INTO $EVENT_ITEMS_TABLE (reservation_id, name, date_created) VALUES 
        ('".$_POST["reservation_id"]."', '".trim($_POST["name"])."', '".date("m-d-Y h:i A")."') ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully saved.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully saved.';
        }
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'quotation_delete' )
	{
        $connect->beginTransaction();
        $create = query($connect, "DELETE FROM  $EVENT_ITEMS_TABLE WHERE id = '".$_POST["id"]."' ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully deleted.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully deleted.';
        }
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'reservation_view_payment' )
    {
        $result = fetch_row($connect, "SELECT * FROM $RESERVATION_TABLE WHERE reservation_id = '".$_POST["reservation_id"]."' ");
        $html = '
            <hr class="m-0 p-0">
            <div class="h3 mt-2">Payment</div>
            <div class="row">';
        if ($result["payment_method"] == 'Full Payment')
        {
            $html .= '
                    <div class="form-group col-12 col-md-12">
                        <label>Full Payment: </label>
                        <input type="text" class="form-control payment_server" disabled value="'.$result["payment_one_server"].'" />
                    </div>
                    <div class="form-group col-6 col-md-6">
                        <label>Amount: </label>
                        <input type="text" class="form-control payment_amount" disabled value="'.$result["payment_one_amount"].'"/>
                    </div>
                    <div class="form-group col-6 col-md-6">
                        <label>Date Paid: </label>
                        <input type="text" class="form-control date_paid" disabled value="'.$result["payment_one_date"].'"/>
                    </div>
                    <div class="form-group col-12 col-md-12">
                        <a data-magnify="gallery" class="card-img-top rectangle-image " data-caption="10% Payment" data-group="" href="'.$result["payment_one"].'">
                            <img class="img-fluid " style="height: auto; width: auto; cursor: pointer;" id="user_img" src="'.$result["payment_one"].'" alt="10% Payment">
                        </a>
                    </div>
                ';
        }
        else
        {
            $html .= '
                    <div class="form-group col-12 col-md-12">
                        <label>10% Payment: </label>
                        <input type="text" class="form-control payment_server" disabled value="'.$result["payment_one_server"].'" />
                    </div>
                    <div class="form-group col-6 col-md-6">
                        <label>Amount: </label>
                        <input type="text" class="form-control payment_amount" disabled value="'.$result["payment_one_amount"].'"/>
                    </div>
                    <div class="form-group col-6 col-md-6">
                        <label>Date Paid: </label>
                        <input type="text" class="form-control date_paid" disabled value="'.$result["payment_one_date"].'"/>
                    </div>
                    <div class="form-group col-12 col-md-12">
                        <a data-magnify="gallery" class="card-img-top rectangle-image " data-caption="10% Payment" data-group="" href="'.$result["payment_one"].'">
                            <img class="img-fluid " style="height: auto; width: auto; cursor: pointer;" id="user_img" src="'.$result["payment_one"].'" alt="10% Payment">
                        </a>
                    </div>
                ';
            if (!empty($result["payment_two"]))
            {
                $html .= '
                        <div class="form-group col-12 col-md-12">
                            <label>40% Payment: </label>
                            <input type="text" class="form-control payment_server" disabled value="'.$result["payment_two_server"].'" />
                        </div>
                        <div class="form-group col-6 col-md-6">
                            <label>Amount: </label>
                            <input type="text" class="form-control payment_amount" disabled value="'.$result["payment_two_amount"].'"/>
                        </div>
                        <div class="form-group col-6 col-md-6">
                            <label>Date Paid: </label>
                            <input type="text" class="form-control date_paid" disabled value="'.$result["payment_two_date"].'"/>
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <a data-magnify="gallery" class="card-img-top rectangle-image " data-caption="40% Payment" data-group="" href="'.$result["payment_two"].'">
                                <img class="img-fluid " style="height: auto; width: auto; cursor: pointer;" id="user_img" src="'.$result["payment_two"].'" alt="40% Payment">
                            </a>
                        </div>
                    ';
            }
            if (!empty($result["payment_three"]))
            {
                $html .= '
                        <div class="form-group col-12 col-md-12">
                            <label>50% Payment: </label>
                            <input type="text" class="form-control payment_server" disabled value="'.$result["payment_three_server"].'" />
                        </div>
                        <div class="form-group col-6 col-md-6">
                            <label>Amount: </label>
                            <input type="text" class="form-control payment_amount" disabled value="'.$result["payment_three_amount"].'"/>
                        </div>
                        <div class="form-group col-6 col-md-6">
                            <label>Date Paid: </label>
                            <input type="text" class="form-control date_paid" disabled value="'.$result["payment_three_date"].'"/>
                        </div>
                        <div class="form-group col-12 col-md-12">
                            <a data-magnify="gallery" class="card-img-top rectangle-image " data-caption="50% Payment" data-group="" href="'.$result["payment_three"].'">
                                <img class="img-fluid " style="height: auto; width: auto; cursor: pointer;" id="user_img" src="'.$result["payment_three"].'" alt="50% Payment">
                            </a>
                        </div>
                    ';
            }
        }
        $html .= '
            </div>';
        $output['html'] = $html;
		echo json_encode($output);
    }
	
	if($_POST['btn_action'] == 'profile_load' )
	{
        $result = fetch_row($connect, "SELECT * FROM $USER_TABLE WHERE id = '".$_SESSION["user_id"]."' ");
        $output['email'] = $result['email'];
        $output['last_name'] = $result['last_name'];
        $output['first_name'] = $result['first_name'];
        $output['middle_name'] = $result['middle_name'];
        $output['contact'] = $result['contact'];
        $output['address'] = $result['address'];
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'profile_edit' )
	{
        $password = password_hash(trim($_POST["password_profile"]), PASSWORD_DEFAULT);
        $connect->beginTransaction();
        $update = query($connect, "UPDATE $USER_TABLE SET 
            email = '".trim($_POST["email"])."', 
            password = '".$password."', 
            contact = '".trim($_POST["contact"])."', 
            address = '".trim($_POST["address"])."' 
        WHERE id = '".$_SESSION['user_id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully saved.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully saved.';
        }
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'bartender_list' )
	{
        if ($_POST["title"] == 'view')
        {
            $table = '
            <table id="datatables_bartender" class="table table-bordered ">
                <thead>
                    <tr>
                        <th>Bartender</th>
                    </tr>
                </thead>
                <tbody>';
        }
        else
        {
            $table = '
            <table id="datatables_bartender" class="table table-bordered ">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Bartender</th>
                    </tr>
                </thead>
                <tbody>';
        }
        $count = 0;
        $total = 0;
        $rslt = fetch_all($connect,"SELECT * FROM $BARTENDER_TABLE WHERE reservation_id = '".$_POST["reservation_id"]."' " );
        foreach($rslt as $row)
        {
            if ($_POST["title"] == 'view')
            {
                $table .= '
                <tr>
                    <td>'.$row["name"].'</td>
                </tr>';
            }
            else
            {
                $table .= '
                <tr>
                    <td><div class="btn-group btn-group-md elevation-2">
                        <a href="#" class="btn btn-dark delete text-bold" name="delete" id="'.$row["id"].'" data-title="bartender" data-toggle="tooltip" data-placement="top" title="Delete">Delete</a>
                    </div></td>
                    <td>'.$row["name"].'</td>
                </tr>';
            }
            $count = $count + 1;
        }
        $table .= '
            </tbody>
        </table>';
        $output['table'] = $table;
        $output['count'] = $count;
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'bartender_add' )
	{
        $connect->beginTransaction();
        $create = query($connect, "INSERT INTO $BARTENDER_TABLE (reservation_id, name, date_created) VALUES 
        ('".$_POST["reservation_id"]."', '".trim($_POST["name"])."', '".date("m-d-Y h:i A")."') ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully saved.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully saved.';
        }
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'bartender_delete' )
	{
        $connect->beginTransaction();
        $create = query($connect, "DELETE FROM $BARTENDER_TABLE WHERE id = '".$_POST["id"]."' ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully deleted.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully deleted.';
        }
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Inventory_add' )
	{
        // $count = get_total_count($connect, "SELECT * FROM $INVENTORY_TABLE WHERE reservation_id = '".trim($_POST["reservation_id"])."' AND equipment = '".trim($_POST["equipment"])."'");
        // if ($count > 0)
        // {
        //     $output['status'] = false;
        //     $output['message'] = 'Equipment already exist in this event.';
        // }
        // else 
        // {
            $connect->beginTransaction();
            $create = query($connect, "INSERT INTO $INVENTORY_TABLE (item_name, quantity, date_created) VALUES 
            ('".trim($_POST["item_name"])."', '".trim($_POST["quantity"])."', '".date("m-d-Y h:i A")."') ");
            if ($create == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully saved.';
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully saved.';
            }
        // }
		echo json_encode($output);
	}
	
	if($_POST['btn_action'] == 'Inventory_fetch' )
	{
        $result = fetch_row($connect, "SELECT * FROM $INVENTORY_TABLE WHERE id = '".$_POST["id"]."' ");
        $output['item_name'] = $result['item_name'];
        $output['quantity'] = $result['quantity'];
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Inventory_update' )
	{
        // $count = get_total_count($connect, "SELECT * FROM $INVENTORY_TABLE WHERE id != '".$_POST["id"]."' AND 
        // reservation_id = '".trim($_POST["reservation_id"])."' AND equipment = '".trim($_POST["equipment"])."' ");
        // if($count > 0)
        // {
        //     $output['status'] = false;
        //     $output['message'] = 'Equipment already exist in this event.';
        // }
        // else
        // {
            $connect->beginTransaction();
            $update = query($connect, "UPDATE $INVENTORY_TABLE SET 
                item_name = '".trim($_POST["item_name"])."',
                quantity = '".trim($_POST["quantity"])."'  
            WHERE id = '".$_POST['id']."' ");
            if ($update == true)
            {
                $connect->commit();
                $output['status'] = true;
                $output['message'] = 'Successfully saved.';
            }
            else 
            {
                $connect->rollBack();
                $output['status'] = false;
                $output['message'] = 'Unsuccessfully saved.';
            }
        // }
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'notification_count' )
	{
        $count = get_total_count($connect, "SELECT * FROM $NOTIF_TABLE WHERE date_seen IS NULL AND user_id = '".$_SESSION["user_id"]."' ");

        $list = '';
        $result = fetch_all($connect,"SELECT * FROM $NOTIF_TABLE WHERE user_id = '".$_SESSION["user_id"]."'  ORDER BY id DESC " ); //  AND date_seen IS NULL
        foreach($result as $row)
        {
            $action = '#';
            if ($_SESSION["user_id"] == 1)
            {
                $action = 'payment.php';
            }
            $bg = !empty($row["date_seen"]) ? '' : 'bg-dark';
            $list .= '<a href="'.$action.'" class="dropdown-item click_notif '.$bg.'" id="'.$row["id"].'">
            <div class="media">
                <div class="media-body">
                    <h3 class="dropdown-item-title">
                        '.$row["reservation_id"].' - '.$row["title"].'
                    </h3>
                    <p class="text-sm">'.$row["message"].'</p>
                    <p class="text-sm "><i class="far fa-clock mr-1"></i>'.$row["date_created"].'</p>
                </div>
            </div>
        </a>
        <div class="dropdown-divider"></div>';
        }
        $output['list'] = $list;

        $output['count'] = $count;
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'notification_seen' )
	{
        query($connect, "UPDATE $NOTIF_TABLE SET date_seen = '".date("m-d-Y h:i A")."' WHERE id = '".$_POST['id']."' ");
        $output['status'] = true;
		echo json_encode($output);
	}
    
	if($_POST['btn_action'] == 'notification_load' )
    {
        $table = '<table id="datatables" class="table table-hover  ">
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
            <tbody>';

        $result = fetch_all($connect," SELECT * FROM $NOTIF_TABLE WHERE user_id = '".$_SESSION["user_id"]."' ORDER BY id DESC " );
        foreach($result as $row)
        {
            if (empty($row['date_seen']))
            {
                $date_seen = '';
            }
            else
            {
                $date_seen = '<span class="badge badge-success">Seen</span>';
            }
            $table .= '
                    <tr>
                        <td>'.$row['id'].'</td>
                        <td>'.$row['reservation_id'].'</td>
                        <td>'.$row['title'].'</td>
                        <td>'.$row['message'].'</td>
                        <td>'.$date_seen.'</td>
                        <td>'.$row['date_created'].'</td>
                    </tr>';
        }
        $table .= '
            </tbody>
        </table>';
        $output['table'] = $table;
        echo json_encode($output);
        return;
    }
	
	if($_POST['btn_action'] == 'Inventory_list' )
	{
        $table = '
        <table id="datatables_list" class="table table-bordered ">
            <thead>
                <tr>
                    <th>Equipment</th>
                </tr>
            </thead>
            <tbody>';
        $rslt = fetch_all($connect,"SELECT * FROM $EVENT_ITEMS_TABLE WHERE reservation_id = '".$_POST["id"]."' " );
        if ($rslt)
        {
            foreach($rslt as $row)
            {
                $table .= '
                <tr>
                    <td>'.$row["name"].'</td>
                </tr>';
            }
        }
        else
        {
            $table .= '
            <tr>
                <td class="text-center">No data found.</td>
            </tr>';
        }
        $table .= '
            </tbody>
        </table>';
        $output['table'] = $table;
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Report_add' )
	{
        // $connect->beginTransaction();
        // $create = query($connect, "INSERT INTO $REPORT_TABLE (reservation_id, equipment, date_created) VALUES 
        // ('".trim($_POST["reservation_id"])."', '".trim($_POST["equipment"])."', '".date("m-d-Y h:i A")."') ");
        // if ($create == true)
        // {
        //     $connect->commit();
        //     $output['status'] = true;
        //     $output['message'] = 'Successfully saved.';
        // }
        // else 
        // {
        //     $connect->rollBack();
        //     $output['status'] = false;
        //     $output['message'] = 'Unsuccessfully saved.';
        // }
        $result = fetch_row($connect, "SELECT * FROM $INVENTORY_TABLE WHERE id = '".trim($_POST["item_id"])."' ");
        $item_name = $result['item_name'];
        $connect->beginTransaction();
		$r_id = $_POST["reservation_id"];
        $create = query($connect, "INSERT INTO $REPORT_TABLE (item_id, item_name, reservation, date_created) VALUES 
        ('".trim($_POST["item_id"])."', '".$item_name."', '".$r_id."', '".date("m-d-Y h:i A")."') ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully saved.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully saved.';
        }
		echo json_encode($output);
	}
	
	if($_POST['btn_action'] == 'Report_fetch' )
	{
        $result = fetch_row($connect, "SELECT * FROM $REPORT_TABLE WHERE id = '".$_POST["id"]."' ");
        $output['item_id'] = $result['item_id'];
        $output['quantity'] = $result['quantity'];
        $output['reservation'] = $result['reservation'];
        $result = fetch_row($connect, "SELECT * FROM $INVENTORY_TABLE WHERE id = '".$result["item_id"]."' ");
        $output['qty'] = $result['quantity'];
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Report_update' )
	{
        $result = fetch_row($connect, "SELECT * FROM $INVENTORY_TABLE WHERE id = '".trim($_POST["item_id"])."' ");
        $item_name = $result['item_name'];
        $connect->beginTransaction();
		$r_id = $_POST["reservation_id"];

        $update = query($connect, "UPDATE $REPORT_TABLE SET 
            item_id = '".trim($_POST["item_id"])."',
            reservation = '".trim($_POST["r_id"])."',
            item_name = '".$item_name."'
        WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully saved.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully saved.';
        }
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Settings_add' )
	{
        $image = '';
        if ($_FILES["files"]["size"] != 0)
        {
            $image = $_FILES["files"]["name"];
            $png = strpos($image, 'png');
            $jpg = strpos($image, 'jpg');
            $jpeg = strpos($image, 'jpeg');
            $type = $png !== false ? 'png' : ($jpg !== false ? 'jpg' : ($jpeg !== false ? 'jpeg' : 'false' ));
            if ($type == 'false')
            {
                $output['status'] = false;
                $output['message'] = "Invalid file type, please upload a png, jpg or jpeg.";
                echo json_encode($output);
                return;
            }
            else
            {
                $name = date('mdYHi').rand(999,9999);
                $file_type = array("jpg", "png", "jpeg");
                $upload = upload_image($_FILES["files"], $name, 'assets/settings/', $file_type, $type);
                if ($upload["status"] == false)
                {
                    $output['status'] = false;
                    $output['message'] = $upload["message"];
                    echo json_encode($output);
                    return;
                }
                else
                {
                    $image = $upload["message"];
                }
            }
        }
        else
        {
            $output['status'] = false;
            $output['message'] = "Please upload an image.";
            echo json_encode($output);
            return;
        }

        $connect->beginTransaction();
        $create = query($connect, "INSERT INTO $SETTINGS_TABLE (image, title, description, status, date_created) VALUES 
        ('".$image."', '".str_replace("'","\'",trim($_POST["title"]))."', '".str_replace("'","\'",trim($_POST["description"]))."', 'Active', '".date("m-d-Y h:i A")."') ");
        if ($create == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully saved.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully saved.';
        }
		echo json_encode($output);
    }

	if($_POST['btn_action'] == 'Settings_delete' )
	{
        $connect->beginTransaction();
        $update = query($connect, "DELETE FROM $SETTINGS_TABLE WHERE id = '".$_POST['id']."' ");
        if ($update == true)
        {
            $connect->commit();
            $output['status'] = true;
            $output['message'] = 'Successfully deleted.';
        }
        else 
        {
            $connect->rollBack();
            $output['status'] = false;
            $output['message'] = 'Unsuccessfully deleted.';
        }
		echo json_encode($output);
    }
    
	if($_POST['btn_action'] == 'calendar' ) 
    {
        $array = [];
        $result = fetch_all($connect,"SELECT a.*,b.first_name,b.last_name FROM $RESERVATION_TABLE a left join $USER_TABLE b on a.client_id = b.id WHERE a.status NOT IN ('Cancelled') " );
        foreach($result as $row)
        {
            $color = '#ffc107'; 
            if($row['status'] == 'Pending')
            {
                $color = 'purple'; 
            }
            else if($row['status'] == 'Quoted' || $row['status'] == 'Approved')
            {
                $color = 'orange'; 
            }
            else if($row['status'] == 'Accepted' || $row['status'] == 'Reserved' || $row['status'] == 'Processing')
            {
                $color = 'blue'; 
            }
            else if($row['status'] == 'Completed')
            {
                $color = 'teal'; 
            }
			else if($row['status'] == 'Payment')
            {
                $color = 'green'; 
            }
			else if($row['status'] == 'Declined')
            {
                $color = 'red'; 
            }
            $topic = $row["reservation_id"]." - ". $row["event_time"];

            $arr = array(
                "id"                => $row["id"], 
                "title"             => $topic, 
                "topic"             => $topic, 
                "date_time"         => $row["event_date"], 
                "event_time"        => $row["event_time"], 
                "details"           => $row["events_id"], 
                "types"             => $row["types"], 
                "client"            => $row["first_name"] . ' '. $row["last_name"], 
                "start_y"           => intval(substr($row["event_date"], 6)), 
                "start_m"           => intval(substr($row["event_date"], 0, 2)) - 1, 
                "start_d"           => intval(substr($row["event_date"], 3, 2)), 
                "end_y"             => intval(substr($row["event_date"], 6)), 
                "end_m"             => intval(substr($row["event_date"], 0, 2)) - 1, 
                "end_d"             => intval(substr($row["event_date"], 3, 2)), 
                "color"             => $color, 
                "status"            => $row["status"], 
            );
            array_push($array, $arr);
        }
        echo json_encode($array);
    }
    
	if($_POST['btn_action'] == 'load_ratings' ) 
    {
        $query = "";
        if ($_SESSION["user_type"] == 'Client')
        {
            $query .= " AND client_id = '".$_SESSION["user_id"]."' ";
        }
        $count_5 = get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Completed' AND feedback IS NOT NULL AND rating = 5 $query ");
        $count_4 = get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Completed' AND feedback IS NOT NULL AND rating = 4 $query ");
        $count_3 = get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Completed' AND feedback IS NOT NULL AND rating = 3 $query ");
        $count_2 = get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Completed' AND feedback IS NOT NULL AND rating = 2 $query ");
        $count_1 = get_total_count($connect, "SELECT * FROM $RESERVATION_TABLE WHERE status = 'Completed' AND feedback IS NOT NULL AND rating = 1 $query ");

        $total_count = $count_5 + $count_4 + $count_3 + $count_2 + $count_1;

        $percent_5 = $count_5 == 0 ? 0 : ($count_5 / $total_count) * 100;
        $percent_4 = $count_4 == 0 ? 0 : ($count_4 / $total_count) * 100;
        $percent_3 = $count_3 == 0 ? 0 : ($count_3 / $total_count) * 100;
        $percent_2 = $count_2 == 0 ? 0 : ($count_2 / $total_count) * 100;
        $percent_1 = $count_1 == 0 ? 0 : ($count_1 / $total_count) * 100;

        $output['percent_5'] = $percent_5;
        $output['percent_4'] = $percent_4;
        $output['percent_3'] = $percent_3;
        $output['percent_2'] = $percent_2;
        $output['percent_1'] = $percent_1;

        $times_5 = $count_5 == 0 ? 0 : $count_5 * 5;
        $times_4 = $count_4 == 0 ? 0 : $count_4 * 4;
        $times_3 = $count_3 == 0 ? 0 : $count_3 * 3;
        $times_2 = $count_2 == 0 ? 0 : $count_2 * 2;
        $times_1 = $count_1 == 0 ? 0 : $count_1 * 1;
        
        // $rate = $times_5 + $times_4 + $times_3 + $times_2 + $times_1;
        // 1 = 4
        // 2 = 3
        // 3 = 1
        // 4 = 4
        // 5 = 0
        // AR = 1*4+2*3+3*1+4*4/12
        // AR = (4 + 6 + 3 + 16) / 12
        // AR = 2.416

        // 4*1 + 5*1 / 2
        $rate = ($times_5 + $times_4 + $times_3 + $times_2 + $times_1) / ($count_5 + $count_4 + $count_3 + $count_2 + $count_1);
        $output['rates'] = $rate;
        $output['rate'] = number_format(floatval($rate), 1, '.', '');
        
        $star = '';
		for ($x = 1; $x <= 5; $x++) 
        {
            if ($x <= $rate)
            {
                $star .= '<i class="fa fa-star text-white"></i>';
            }
            else
            {
                $star .= '<i class="far fa-star text-white"></i>';
            }
		}
        $output['star'] = $star;
        $output['status'] = true;
		echo json_encode($output);
    }

}

?>