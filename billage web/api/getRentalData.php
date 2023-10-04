<?php
    // 데이터베이스 불러오기
    require_once("../db_connect.php");

    if (isset($_GET['u_id'])) {
        $user_id = $_GET['u_id'];

        // u_id 값을 이용하여 Rental 데이터를 가져오는 쿼리 작성
        $sql = "SELECT * FROM Rental WHERE u_id = $user_id ORDER BY rt_book";
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
            echo json_encode(array('message' => 'No data available for user_id: ' . $user_id));
        }
    } else {
        // u_id 값이 전달되지 않은 경우 모든 데이터를 가져오는 쿼리 작성
        $sql = "SELECT * FROM Rental";
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
            echo json_encode(array('message' => 'No data available'));
        }
    }

    // 데이터베이스 연결 닫기
    $conn->close();
?>
