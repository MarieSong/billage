<?php
    // db_connect.php 파일을 포함합니다.
    require_once("../db_connect.php");

    // u_id와 u_pwd 값을 가져옵니다.
    $u_id = $_POST['u_id'];
    $u_pwd_old = $_POST['u_pwd_old'];
    $u_pwd_new = $_POST['u_pwd_new'];

    $sql_current_pwd = "SELECT u_pwd FROM user WHERE u_id = $u_id";
    $response_current = $conn->query($sql_current_pwd); //현재 비밀번호

    if ($response_current ->num_rows > 0) {
        $row_current = $response_current->fetch_assoc();
        $storedPassword = $row_current['u_pwd'];

        if ($u_pwd_old === $storedPassword) { //비밀번호 일치 확인
            // user 테이블에서 해당하는 u_id의 u_pwd 값을 변경합니다.
            $sql_update = "UPDATE user SET u_pwd = '$u_pwd_new' WHERE u_id = $u_id";

            if ($conn->query($sql_update) === TRUE) {
                $response = array('status' => 'success');
                header('Content-Type: application/json');
                echo json_encode($response);
            } else {
                $response = array('status' => 'fail_sql');
                header('Content-Type: application/json');
                echo json_encode($response);
            }


        } else {
            $response = array('status' => 'fail_old');
            header('Content-Type: application/json');
            echo json_encode($response);
        }

    } else {
        $response = array('status' => 'fail_user');
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // 데이터베이스 연결을 닫습니다.
    $conn->close();
?>
