<?php
    // 데이터베이스 불러오기
    require_once("../db_connect.php");

    // POST로 전달받은 데이터 받기
    $u_id = $_POST['u_id'];
    $u_name = $_POST['u_name'];
    $u_email = $_POST['u_email'];
    $u_pwd = $_POST['u_pwd'];
    $u_phone = $_POST['u_phone'];

    // u_role은 고정값으로 1로 설정
    $u_role = 1;

    // 데이터베이스에 데이터 삽입
    $sql = "INSERT INTO user (u_id, u_name, u_email, u_pwd, u_phone, u_role)
            VALUES ($u_id, '$u_name', '$u_email', '$u_pwd', '$u_phone', $u_role)";

    if ($conn->query($sql) === TRUE) {
        // 삽입 성공 시
        echo "success";
    } else {
        // 삽입 실패 시
        echo "fail";
    }

    // 데이터베이스 연결 종료
    $conn->close();
?>
