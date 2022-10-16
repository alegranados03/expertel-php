<?php
/*  
Your task is to write a function that will try to schedule a meeting for one or more users.
Your function should detect any potential conflicts, if any user has a conflicting meeting it should print
the user_id and the meeting name.
Assume all times are in the same time zone.  
Meetings might span for more than 1 day. 
Feel free to write helper functions if needed.   

*/

$serverName = 'localhost';
$user = 'root';
$password = '';
$db = 'expertel';

//create connection
$mysqli = new mysqli($serverName, $user, $password, $db);
if ($mysqli->connect_error) {
    echo "Connection Failed";
    exit();
}

function schedule_meeting($mysqli, $meeting_name, $start_time, $end_time, $users)
{
    $start = new DateTime($start_time);
    $end = new DateTime($end_time);
    if($start >= $end){
        echo "not valid date and time";
        return ;
    }
    $queryString = "SELECT name, meeting_name FROM meetings
    INNER JOIN users ON meetings.user_id = users.id
     WHERE (
        start_time BETWEEN '$start_time' AND '$end_time' OR
        end_time BETWEEN '$start_time' AND '$end_time' OR
        '$start_time' BETWEEN start_time AND end_time OR
        '$end_time' BETWEEN start_time AND end_time
    )
     AND user_id in " . "(" . implode(',', array_map(function ($value) {
        return intval($value);
    }, $users)) . ")
    ";

    print("For meeting ".$meeting_name.": \n");
    $statement = $mysqli->prepare($queryString);
    $statement->execute();
    $result = $statement->get_result();
    if ($result->num_rows > 0) {
        while ($m = $result->fetch_row()) {
            $u_name = $m[0];
            $m_name = $m[1];
            print($u_name . " has a conflicting meeting: " . $m_name."\n");
        }
        print("Meeting has not been booked \n");
    } else {

        $insertQuery = "INSERT INTO meetings(`user_id`, `start_time`, `end_time`, `meeting_name`) VALUES (?,?,?,?)";
        $statement = $mysqli->prepare($insertQuery);
        $mysqli->begin_transaction();
        foreach ($users as $user_id) {
            $statement->bind_param("isss", $user_id, $start_time, $end_time, $meeting_name);
            $statement->execute();
        }
        $mysqli->commit();
        print("The meeting has been successfully booked \n");
    }
}

schedule_meeting($mysqli,'Status Meeting', '2022-09-27 09:00:00', '2022-09-27 10:00:00', [1,2,3]);
schedule_meeting($mysqli,'Peer Review', '2022-09-27 09:30:00', '2022-09-27 10:00:00', [3,4]);