<?php
    // 데이터베이스 불러오기
    require_once("../db_connect.php");

    // POST로 전달받은 값들
    $user_id = $_POST['user_id'];
    $device_id = $_POST['device_id'];
    $rental_start = $_POST['rental_start'];
    $rental_deadline = $_POST['rental_deadline'];

    // 사용자가 이미 대여한 기기가 있는지 확인
    $check_user_rental_sql = "SELECT COUNT(*) AS count
                            FROM Rental
                            WHERE u_id = '$user_id' AND rt_state IN(0, 1, 4)";
    $check_user_rental_result = $conn->query($check_user_rental_sql);
    
    if ($check_user_rental_result->num_rows > 0) {
        $row = $check_user_rental_result->fetch_assoc();
        $count = $row['count'];
        
        if ($count > 0) {
            echo "fail"; // 이미 대여한 기기가 있음
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
            echo "fail"; // 기기가 이미 대여됨
            exit();
        }
    }

    // 예약 정보를 Rental 테이블에 추가
    $add_rental_sql = "INSERT INTO Rental (u_id, d_id, rt_start, rt_deadline, rt_state)
                        VALUES ('$user_id', '$device_id', '$rental_start', '$rental_deadline', 0)";
    if ($conn->query($add_rental_sql) === TRUE) {
        echo "success"; // 예약 성공
    } else {
        echo "fail"; // 예약 실패
    }

    // 데이터베이스 연결 닫기
    $conn->close();
?>
