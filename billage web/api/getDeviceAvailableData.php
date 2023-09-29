<?php
    // 데이터베이스 불러오기
    require_once("../db_connect.php");

    // POST로 전달받은 카테고리 id, 시작일, 종료일
    $category_id = $_POST['category_id'];
    $rental_start = $_POST['rental_start'];
    $rental_deadline = $_POST['rental_deadline'];

    // SQL 쿼리 작성
    $sql = "SELECT *
            FROM Device
            WHERE c_id = '$category_id'
            AND d_id NOT IN (
                SELECT Device.d_id
                FROM Device, Rental 
                WHERE Device.d_id = Rental.d_id 
                AND c_id = '$category_id'
                AND (rt_start <= '$rental_deadline' AND rt_deadline >= '$rental_start')
            )";

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
        echo "No available devices";
    }

    // 데이터베이스 연결 닫기
    $conn->close();
?>

