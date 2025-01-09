<?php

function notification($connect, $table, $column, $reservation_id, $title, $message, $user_id)
{
    $statement = $connect->prepare("INSERT INTO $table ($column) VALUES ('".$reservation_id."', '".$title."', '".$message."', '".$user_id."', '".date("m-d-Y h:i A")."') ");
    $statement->execute();
}

function query($connect, $query) //insert, update, delete
{
    $statement = $connect->prepare($query);
    $result = $statement->execute();
    if(isset($result))
    {
        return true;
    }
    return false;
}

function fetch_row($connect, $query) 
{
	$statement = $connect->prepare($query);
	$statement->execute();
    $result = $statement->fetch();
	return $result;
}

function fetch_all($connect, $query)
{
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    return $result;
}

function get_total_count($connect, $query)
{
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

function get_total_sum($connect, $query)
{
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetch();
    return $result["total"] == NULL ? 0 : $result["total"];
}

function upload_image($file, $name, $path, $allowed_extension, $extension) 
{
    if (!file_exists($path)) {
        mkdir($path);
    }
    if(in_array($extension, $allowed_extension))
    {
        $file_link = $path . trim($name).'.' . $extension;
        if(!move_uploaded_file($file["tmp_name"], $file_link))
        {
            $output['status'] = false;
            $output['message'] = "Can't upload image.";
            return $output;
        }
        else
        {
            $output['status'] = true;
            $output['message'] = $file_link;
            $output['file_name'] = $file["name"];
            return $output;
        }
    }
    else
    {
        $output['status'] = false;
        $output['message'] = 'Invalid image type.';
        return $output;
    }
}

function send_mail($receipient, $name, $subject, $message)
{
	require 'assets/class/class.phpmailer.php';

    $title = "Tiyo Pau's";
    $email = "criseldareneespeleta@gmail.com";
    $password = "mbdxjoorqtfstmmt";
	
	$mail = new PHPMailer;
	$mail->isSMTP();
	// $mail->SMTPDebug = 2;
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = $email;
	$mail->Password = $password; //
	$mail->SMTPSecure = 'ssl';  // ssl tls
	$mail->Port = 465; // 465 587
	$mail->SMTPKeepAlive = true;  
	$mail->Mailer = "smtp"; // don't change the quotes!
	$mail->setFrom($email, $title);
	$mail->addAddress($receipient, $name);
	$mail->Subject =  $subject;
	$mail->Body = $message;		
	$mail->IsHTML(true);        
	if (!$mail->send()) 
    {
        return false;
    } 
    else 
    {
        return true;
    }
}

?>