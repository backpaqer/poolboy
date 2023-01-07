<?php
    echo "POST STUFF:Relay=".$_POST["RelayID"]." State=".$_POST["RelayState"];

    $table=array();
    $countArrayLength=0;

    function get_relays($relay_id=0)
    {
        global $connect, $table, $countArrayLength;
        $query = sprintf("
            SELECT R.RelayID AS RelayID, 
              R.RelayGPIOpin AS RelayGPIOpin,
              R.RelayDescription AS RelayDescription,
              R.RelayOrder AS RelayOrder,
              R.RelayActive AS RelayActive,
              (SELECT MAX(M.RelayState) FROM MainTimer M
                WHERE M.TimerDay = dayofweek(now())
                AND M.TimerStart <= now()
                AND M.TimerEnd > now()
                AND M.RelayID = R.RelayID
                AND M.TimerType = 'override'
                ) AS RelayState
            FROM Relays R");
        if($relay_id != 0)  
        {
            $query.=" WHERE R.RelayID=".$relay_id." LIMIT 1";
        }
        $query.=" ORDER BY R.RelayOrder";
        $result = mysqli_query($connect, $query);
        $table = array();
        //$response=array();

        while($row=mysqli_fetch_array($result))
        {
            //$response[]=$row;
            array_push($table, array(
                "RelayID" => $row["RelayID"],
                "GPIO" => $row["RelayGPIOpin"],
                "desc" => $row["RelayDescription"],
                "order" => $row["RelayOrder"],
                "active" => $row["RelayActive"],
                "status" => $row["RelayState"]
            ));
        }
        $countArrayLength = count($table);
        //header('Content-Type: application/json');
        //echo json_encode($response);
    }

    function update_relay($relay_id)
    {
        global $connect;
        parse_str(file_get_contents("php://input"),$post_vars);
        $relay_description=$post_vars["relay_description"];
        $relay_GPIO=$post_vars["relay_GPIO_pin"];
        $relay_active=$post_vars["relay_active"];
        $query="UPDATE Relays SET RelayGPIOpin=($relay_GPIO_pin), RelayDescription='($relay_description)',
            RelayOrder=($relay_order), RelayActive=($relay_active) WHERE RelayID=".$relay_id;
        if(mysqli_query($connect,$query))
        {
            $response=array(
                'status' => 1,
                'status_message' => 'Relay Updated Successfully.'
            );
        }
        else
        {
            $response=array(
                'status' => 0,
                'status_message' => 'Relay Update Failed.'
            );
        
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    function toggle_relay($relay_id)
    {
        global $connect;
        parse_str(file_get_contents("php://input"),$post_vars);
        $relay_description=$post_vars["relay_description"];
        $relay_GPIO=$post_vars["relay_GPIO_pin"];
        $relay_active=$post_vars["relay_active"];
        $query="UPDATE Relays SET RelayGPIOpin=($relay_GPIO_pin), RelayDescription='($relay_description)',
            RelayOrder=($relay_order), RelayActive=($relay_active) WHERE RelayID=".$relay_id;
        if(mysqli_query($connect,$query))
        {
            $response=array(
                'status' => 1,
                'status_message' => 'Relay Updated Successfully.'
            );
        }
        else
        {
            $response=array(
                'status' => 0,
                'status_message' => 'Relay Update Failed.'
            );
        
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    $request_method=$_SERVER["REQUEST_METHOD"];
    switch($request_method)
    {
        case 'GET': 
            if(!empty($_GET["RelayID"]))
            {
                $relay_id=intval($_GET["RelayID"]);
                get_relays($relay_id);
            } else {
                get_relays();
            }
            break;
        case 'POST': 
            echo "HELLO".$_POST["RelayID"]."<br>";

            if(isset($_POST["RelayID"]) && !empty($_POST["RelayID"])) {
                $sql = "CALL loadOverrideRelay(".$_POST["RelayID"].",".$_POST["RelayState"].")";
                $_POST = array();
                if(mysqli_query($connect, $sql)) {
                    header("location:settingrelays.php");
                }
            }
            break;
        case 'PUT': 
            // update relay config
            $relay_id=intval($_GET["RelayID"]);
            update_relay($relay_id);
            break;
        }




