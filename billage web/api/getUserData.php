<?php
    // 데이터베이스 불러오기
    require_once("../db_connect.php");

    // GET로 전달받은 u_id와 u_pw
    $u_id = $_POST['u_id'];
    $u_pw = $_POST['u_pw'];

    // User 데이터를 가져오는 쿼리 작성
    $sql = "SELECT * FROM user WHERE u_id = $u_id AND u_pwd = '$u_pw' AND u_role = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 사용자가 존재하고 올바른 비밀번호를 입력했을 경우
        $row = $result->fetch_assoc();
        // 결과를 배열로 변환
        $data = array(
            'u_id' => $row['u_id'],
            'u_email' => $row['u_email'], // 이메일 등 사용자 정보 추가
            'u_name' => $row['u_name'], // 사용자명
            'u_phone' => $row['u_phone'],
        );
    } else {
        // 사용자가 존재하지 않을 경우 null 반환
        $data = null;
    }

    // JSON 형태로 출력
    header('Content-Type: application/json');
    echo json_encode($data);

    // 데이터베이스 연결 닫기
    $conn->close();
?>
