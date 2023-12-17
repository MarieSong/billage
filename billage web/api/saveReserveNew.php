<?php
    // 데이터베이스 불러오기
    require_once("../db_connect.php");

    // POST로 전달받은 값들
    $user_id = $_GET['user_id'];
    $device_id = $_GET['device_id'];
    $rental_start = $_GET['rental_start'];
    $rental_deadline = $_GET['rental_deadline'];

    // 사용자가 이미 대여한 기기가 있는지 확인
    $check_user_rental_sql = "SELECT COUNT(*) AS count
                            FROM Rental
                            WHERE u_id = '$user_id' AND rt_state IN(0, 1, 4)";
    $check_user_rental_result = $conn->query($check_user_rental_sql);
    
    if ($check_user_rental_result->num_rows > 0) {
        $row = $check_user_rental_result->fetch_assoc();
        $count = $row['count'];
        
        if ($count > 0) {
            // 이미 대여한 기기가 있음
            $response = array('status' => 'fail_already');
            // JSON 형태로 출력
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    }

    // 기기가 대여 가능한지 확인
    $check_device_availability_sql = "SELECT COUNT(*) AS count
                                    FROM Rental
                                    WHERE d_id = '$device_id'
                                    AND (rt_start <= '$rental_deadline' AND rt_deadline >= '$rental_start')
                                    AND rt_state IN (0, 1, 4)";
    $check_device_availability_result = $conn->query($check_device_availability_sql);

    if ($check_device_availability_result->num_rows > 0) {
        $row = $check_device_availability_result->fetch_assoc();
        $count = $row['count'];
        
        if ($count > 0) {
            // 기기가 이미 대여 됨(실패)
            $response = array('status' => 'fail_device');
            // JSON 형태로 출력
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    }

    

    // 현재 날짜 및 예약 순서 가져오기s
    $today = date("ymd");
    $sql = "SELECT COUNT(*) AS reservation_count FROM Rental WHERE rt_book = CURDATE()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $reservation_count = str_pad($row['reservation_count'] + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $reservation_count = "001";
    }

    // 예약 ID 생성
    $reservation_id = "rt12345-" . $today . "-" . $reservation_count;

    // 예약 정보를 Rental 테이블에 추가
    $add_rental_sql = "INSERT INTO Rental (rt_id, d_id, u_id, rt_book, rt_start, rt_deadline, rt_return, rt_state)
                        VALUES ('$reservation_id', '$device_id', $user_id , CURDATE(), '$rental_start', '$rental_deadline', NUll, 0)";
    if ($conn->query($add_rental_sql) === TRUE) {
        // 예약 성공 시
        $response = array('status' => 'success');
        // JSON 형태로 출력
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // 예약 실패 시
        $response = array('status' => $add_rental_sql);
        // JSON 형태로 출력
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // 데이터베이스 연결 닫기
    $conn->close();
?>
