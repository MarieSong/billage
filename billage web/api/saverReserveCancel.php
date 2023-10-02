<?php

    // 데이터베이스 불러오기
    require_once("../db_connect.php");
    
    // POST로 전달받은 rt_id 값 가져오기
    $rt_id = $_POST['rt_id'];

    // Return 테이블에서 해당 rt_id의 데이터 삭제 쿼리
    $sql = "DELETE FROM Return WHERE rt_id = '$rt_id'";

    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "fail";
    }

    // MySQL 연결 종료
    $conn->close();
?>