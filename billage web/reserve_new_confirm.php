<?php
    // 데이터베이스 연결 설정 파일 포함
    require_once("db_connect.php");

    // POST 요청으로 받은 데이터 가져오기
    $reserver_id = $_POST['reserver_id'];
    $device_id = $_POST['device_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $sql = "SELECT * FROM user WHERE u_id = '$reserver_id'";

    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        echo "user not found";

        // 데이터베이스 연결 종료
        $conn->close();

        exit;
        
    }

    $sql = "SELECT * FROM Device WHERE d_id = '$device_id'";

    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        echo "device not found";

        // 데이터베이스 연결 종료
        $conn->close();

        exit;
        
    }

    



    //사용자가 현재 대여한 기기가 없는지 확인
    $sql = "SELECT COUNT(*) AS count
            FROM Rental
            WHERE u_id = '$reserver_id' AND rt_state IN(0, 1, 4)";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row['count'];

        if ($count > 0){
            // 이미 대여중인 경우(대여 불가능)
            echo "unavailable user";
        } else {
            // 기기가 대여 가능한지 여부 확인
            $sql = "SELECT COUNT(*) AS count
            FROM Rental
            WHERE d_id = '$device_id'
            AND (rt_start <= '$end_date' AND rt_deadline >= '$start_date')
            AND rt_state IN (0, 1, 4)";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = $row['count'];
            if ($count > 0) {
                // 대여 불가능한 경우
                echo "unavailable device";
            } else {
                // 대여 가능한 경우
                echo "available";
            }
            } else {
            // 쿼리 실행 에러
            echo "error";
            }


        }


    } else {
        // 쿼리 실행 에러
        echo "error";
    }


    

    // 데이터베이스 연결 종료
    $conn->close();
?>
