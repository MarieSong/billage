<?php
    // 데이터베이스 불러오기
    require_once("../db_connect.php");

    // POST로 전달받은 카테고리 id, 시작일, 종료일
    $category_id = $_GET['category_id'];
    $rental_start = $_GET['rental_start'];
    $rental_deadline = $_GET['rental_deadline'];

    // SQL 쿼리 작성
    $sql = "SELECT Device.*, COUNT(Rental.rt_id) AS rental_count
            FROM Device
            LEFT JOIN Rental ON Device.d_id = Rental.d_id
            WHERE Device.c_id = '$category_id'
            AND Device.d_id NOT IN (
                SELECT Device.d_id
                FROM Device, Rental 
                WHERE Device.d_id = Rental.d_id 
                AND Device.c_id = '$category_id'
                AND (Rental.rt_start <= '$rental_deadline' AND Rental.rt_deadline >= '$rental_start')
                AND Rental.rt_state != 2
            )
            AND Device.d_state = 0
            GROUP BY Device.d_id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 결과를 배열로 변환
        $data = array();
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        // JSON 형태로 출력
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        $data = null;
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // 데이터베이스 연결 닫기
    $conn->close();
?>

