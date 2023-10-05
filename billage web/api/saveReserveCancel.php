<?php

    // 데이터베이스 불러오기
    require_once("../db_connect.php");
    
    // POST로 전달받은 rt_id 값 가져오기
    $rt_id = $_POST['rt_id'];

    // Return 테이블에서 해당 rt_id의 데이터 삭제 쿼리
    $sql = "UPDATE Rental SET rt_state = 2 WHERE rt_id = '$rt_id'";

    if ($conn->query($sql) === TRUE) {
        // 삭제 성공 시
        $response = array('status' => 'success');
        // JSON 형태로 출력
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // 삭제 실패 시
        $response = array('status' => 'fail');
        // JSON 형태로 출력
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // MySQL 연결 종료
    $conn->close();
?>