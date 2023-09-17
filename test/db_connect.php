<?php
    // MySQL 데이터베이스 연결 정보
    $db_host = 'localhost';      // 호스트 주소
    $db_username = 'billage';      // 데이터베이스 사용자 이름
    $db_password = 'teambym2023!';      // 데이터베이스 비밀번호
    $db_name = 'billage';     // 데이터베이스 이름

    // 데이터베이스 연결
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // 연결 확인
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>