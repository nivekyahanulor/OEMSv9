<?php
    
    try{

        $server_link = "ws://localhost:8080";
        
        define('DB_HOST','localhost');
        define('DB_USER','root');
        define('DB_PASS','');
        define('DB_NAME','oems');
        date_default_timezone_set('Asia/Manila');
        
        $conn_pdo = new PDO("mysql:host=".DB_HOST, DB_USER, DB_PASS);
        // set the PDO error mode to exception
        $conn_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn_pdo->query("CREATE DATABASE IF NOT EXISTS ".DB_NAME);

        $connect = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS); 
        $USER_TABLE = 'user_account';
        $USER_COLUMN = 'last_name, first_name, middle_name, email, password, contact, address, user_type, status, date_created';
        session_start();
        
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        include('function.php');

        $query = "SHOW TABLES LIKE '$USER_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $USER_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `last_name` VARCHAR(255) DEFAULT NULL,
                `first_name` VARCHAR(255) DEFAULT NULL,
                `middle_name` VARCHAR(255) DEFAULT NULL,
                `email` VARCHAR(255) DEFAULT NULL,
                `email_code` VARCHAR(255) DEFAULT NULL,
                `password` VARCHAR(255) DEFAULT NULL,
                `contact` VARCHAR(255) DEFAULT NULL,
                `address` VARCHAR(255) DEFAULT NULL,
                `user_type` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
            $password = password_hash('admin', PASSWORD_DEFAULT);
            query($connect, "INSERT INTO $USER_TABLE ($USER_COLUMN) VALUES ('Admin', '', '', 'admin@admin.com', '".$password."' , '', '', 'Superadmin', 'Active','".date("m-d-Y h:i A")."') ");
        }

        $PRODUCT_TABLE = 'products';
        $query = "SHOW TABLES LIKE '$PRODUCT_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $PRODUCT_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `image` VARCHAR(255) DEFAULT NULL,
                `category_id` VARCHAR(255) DEFAULT NULL,
                `product` VARCHAR(255) DEFAULT NULL,
                `price` VARCHAR(255) DEFAULT NULL,
                `description` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $CATEGORY_TABLE = 'category';
        $query = "SHOW TABLES LIKE '$CATEGORY_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $CATEGORY_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `category` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
            query($connect, "INSERT INTO $CATEGORY_TABLE (category, status, date_created) VALUES ('Cocktails', 'Active','".date("m-d-Y h:i A")."') ");
            query($connect, "INSERT INTO $CATEGORY_TABLE (category, status, date_created) VALUES ('Milktea', 'Active','".date("m-d-Y h:i A")."') ");
            query($connect, "INSERT INTO $CATEGORY_TABLE (category, status, date_created) VALUES ('Coffee', 'Active','".date("m-d-Y h:i A")."') ");
            query($connect, "INSERT INTO $CATEGORY_TABLE (category, status, date_created) VALUES ('Fruit Tea', 'Active','".date("m-d-Y h:i A")."') ");
            query($connect, "INSERT INTO $CATEGORY_TABLE (category, status, date_created) VALUES ('Juice', 'Active','".date("m-d-Y h:i A")."') ");
        }

        $EVENTS_TABLE = 'events';
        $query = "SHOW TABLES LIKE '$EVENTS_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $EVENTS_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `event` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $RESERVATION_TABLE = 'reservation';
        $query = "SHOW TABLES LIKE '$RESERVATION_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $RESERVATION_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `reservation_id` VARCHAR(255) DEFAULT NULL,
                `types` VARCHAR(255) DEFAULT NULL,
                `client_id` VARCHAR(255) DEFAULT NULL,
                `events_id` VARCHAR(255) DEFAULT NULL,
                `event_date` VARCHAR(255) DEFAULT NULL,
                `event_time` VARCHAR(255) DEFAULT NULL,
                `total_days` VARCHAR(255) DEFAULT 0,
                `total_hours` VARCHAR(255) DEFAULT 0,
                `guests` VARCHAR(255) DEFAULT 0,
                `event_place` VARCHAR(255) DEFAULT NULL,
                `address` VARCHAR(255) DEFAULT NULL,
                `notes` VARCHAR(255) DEFAULT NULL,
                `bartender` VARCHAR(255) DEFAULT NULL,
                `bartender_no` VARCHAR(255) DEFAULT NULL,
                `services_amount` VARCHAR(255) DEFAULT NULL,
                `fees_amount` VARCHAR(255) DEFAULT NULL,
                `total_amount` VARCHAR(255) DEFAULT NULL,
                `payment_method` VARCHAR(255) DEFAULT NULL,
                `contract_image` VARCHAR(255) DEFAULT NULL,
                `payment_one` VARCHAR(255) DEFAULT NULL,
                `payment_one_sender` VARCHAR(255) DEFAULT NULL,
                `payment_one_no` VARCHAR(255) DEFAULT NULL,
                `payment_one_server` VARCHAR(255) DEFAULT NULL,
                `payment_one_date` VARCHAR(255) DEFAULT NULL,
                `payment_one_amount` VARCHAR(255) DEFAULT NULL,
                `payment_one_due` VARCHAR(255) DEFAULT NULL,
                `payment_two` VARCHAR(255) DEFAULT NULL,
                `payment_two_sender` VARCHAR(255) DEFAULT NULL,
                `payment_two_no` VARCHAR(255) DEFAULT NULL,
                `payment_two_server` VARCHAR(255) DEFAULT NULL,
                `payment_two_date` VARCHAR(255) DEFAULT NULL,
                `payment_two_amount` VARCHAR(255) DEFAULT NULL,
                `payment_two_due` VARCHAR(255) DEFAULT NULL,
                `payment_three` VARCHAR(255) DEFAULT NULL,
                `payment_three_sender` VARCHAR(255) DEFAULT NULL,
                `payment_three_no` VARCHAR(255) DEFAULT NULL,
                `payment_three_server` VARCHAR(255) DEFAULT NULL,
                `payment_three_date` VARCHAR(255) DEFAULT NULL,
                `payment_three_amount` VARCHAR(255) DEFAULT NULL,
                `payment_three_due` VARCHAR(255) DEFAULT NULL,
                `total_payment` VARCHAR(255) DEFAULT NULL,
                `feedback` VARCHAR(255) DEFAULT NULL,
                `rating` VARCHAR(255) DEFAULT NULL,
                `reason` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_accepted` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $ITEMS_TABLE = 'items';
        $query = "SHOW TABLES LIKE '$ITEMS_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $ITEMS_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `user_id` VARCHAR(255) DEFAULT NULL,
                `reservation_id` VARCHAR(255) DEFAULT NULL,
                `category_name` VARCHAR(255) DEFAULT NULL,
                `product_name` VARCHAR(255) DEFAULT NULL,
                `quantity` VARCHAR(255) DEFAULT NULL,
                `product_price` VARCHAR(255) DEFAULT NULL,
                `total_price` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $EVENT_ITEMS_TABLE = 'event_items'; // (X)
        $query = "SHOW TABLES LIKE '$EVENT_ITEMS_TABLE'"; 
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $EVENT_ITEMS_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `reservation_id` VARCHAR(255) DEFAULT NULL,
                `name` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        // $QUOTATION_TABLE = 'quotation';
        // $query = "SHOW TABLES LIKE '$QUOTATION_TABLE'";
        // $statement = $connect->prepare($query);
        // $statement->execute();
        // if ($statement->rowCount() == 0)
        // {
        //     $create = "CREATE TABLE $QUOTATION_TABLE(
        //         `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
        //         `reservation_id` VARCHAR(255) DEFAULT NULL,
        //         `name` VARCHAR(255) DEFAULT NULL,
        //         `price` VARCHAR(255) DEFAULT 0,
        //         `date_created` VARCHAR(255) DEFAULT NULL,
        //         INDEX (`id`)
        //     );";
        //     $connect->exec($create);
        // }

        $BARTENDER_TABLE = 'bartender';
        $query = "SHOW TABLES LIKE '$BARTENDER_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $BARTENDER_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `reservation_id` VARCHAR(255) DEFAULT NULL,
                `name` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $INVENTORY_TABLE = 'equipment_list';
        $query = "SHOW TABLES LIKE '$INVENTORY_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $INVENTORY_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `item_name` VARCHAR(255) DEFAULT NULL,
                `quantity` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $NOTIF_TABLE = 'notifications';
        $NOTIF_COLUMN = 'reservation_id, title, message, user_id, date_created';
        $query = "SHOW TABLES LIKE '$NOTIF_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $NOTIF_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `reservation_id` VARCHAR(255) DEFAULT NULL,
                `title` VARCHAR(255) DEFAULT NULL,
                `message` VARCHAR(255) DEFAULT NULL,
                `date_seen` VARCHAR(255) DEFAULT NULL,
                `user_id` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $REPORT_TABLE = 'report';
        $query = "SHOW TABLES LIKE '$REPORT_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $REPORT_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `item_id` VARCHAR(255) DEFAULT NULL,
                `item_name` VARCHAR(255) DEFAULT NULL,
                `quantity` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }

        $SETTINGS_TABLE = 'settings';
        $query = "SHOW TABLES LIKE '$SETTINGS_TABLE'";
        $statement = $connect->prepare($query);
        $statement->execute();
        if ($statement->rowCount() == 0)
        {
            $create = "CREATE TABLE $SETTINGS_TABLE(
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `image` VARCHAR(255) DEFAULT NULL,
                `title` VARCHAR(255) DEFAULT NULL,
                `description` VARCHAR(255) DEFAULT NULL,
                `status` VARCHAR(255) DEFAULT NULL,
                `date_created` VARCHAR(255) DEFAULT NULL,
                INDEX (`id`)
            );";
            $connect->exec($create);
        }
        
        $date_now = new DateTime();
        $result = fetch_all($connect," SELECT * FROM $RESERVATION_TABLE WHERE status IN ('Qouted','Payment') " );
        foreach($result as $row)
        {
            if ($row["payment_method"] == 'Installment')
            {
                if (empty($row["payment_three"]))
                {
                    $date_payment    = new DateTime(str_replace("-", "/", $row["payment_three_due"]));
                    if ($date_now > $date_payment) 
                    {
                        $fifty = (50 / 100) * floatval($row['total_amount']);
                        $title = 'Due Date of Final Payment';
                        $message = 'Due date : '.$row["payment_three_due"].' with an amount of '.$fifty.'.';
                        notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $row["reservation_id"], $title, $message, '1');
                        notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $row["reservation_id"], $title, $message, $row["client_id"]);
                        
                        query($connect, "UPDATE $RESERVATION_TABLE SET payment_three = '".date("m-d-Y h:i A")."' WHERE id = '".$row['id']."' ");
                    }
                }
                if (empty($row["payment_two"]))
                {
                    $date_payment    = new DateTime(str_replace("-", "/", $row["payment_two_due"]));
                    if ($date_now > $date_payment) 
                    {
                        $forty = (40 / 100) * floatval($row['total_amount']);
                        $title = 'Due Date of Second Payment';
                        $message = 'Due date : '.$row["payment_two_due"].' with an amount of '.$forty.'.';
                        notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $row["reservation_id"], $title, $message, '1');
                        notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $row["reservation_id"], $title, $message, $row["client_id"]);
                        
                        query($connect, "UPDATE $RESERVATION_TABLE SET payment_two = '".date("m-d-Y h:i A")."' WHERE id = '".$row['id']."' ");
                    }
                }
                if (empty($row["payment_one"]))
                {
                    $date_payment    = new DateTime(str_replace("-", "/", $row["payment_one_due"]));
                    if ($date_now > $date_payment) 
                    {
                        $ten = (10 / 100) * floatval($row['total_amount']);
                        $title = 'Due Date of First Payment';
                        $message = 'Due date : '.$row["payment_one_due"].' with an amount of '.$ten.'.';
                        notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $row["reservation_id"], $title, $message, '1');
                        notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $row["reservation_id"], $title, $message,  $row["client_id"]);
                        
                        query($connect, "UPDATE $RESERVATION_TABLE SET payment_one = '".date("m-d-Y h:i A")."' WHERE id = '".$row['id']."' ");
                    }
                }
            }
            else
            {
                if (empty($row["payment_one"]))
                {
                    $date_payment    = new DateTime(str_replace("-", "/", $row["payment_one_due"]));
                    if ($date_now > $date_payment) 
                    {
                        $ten = (10 / 100) * floatval($row['total_amount']);
                        $title = 'Due Date of Full Payment';
                        $message = 'Due date : '.$row["payment_one_due"].' with an amount of '.$ten.'.';
                        notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $row["reservation_id"], $title, $message, '1');
                        notification($connect, $NOTIF_TABLE, $NOTIF_COLUMN, $row["reservation_id"], $title, $message,  $row["client_id"]);
                        
                        query($connect, "UPDATE $RESERVATION_TABLE SET payment_one = '".date("m-d-Y h:i A")."' WHERE id = '".$row['id']."' ");
                    }
                }
            }
        }


    } catch(PDOException $err){   
        $connect = null;
        return;
    }

?>