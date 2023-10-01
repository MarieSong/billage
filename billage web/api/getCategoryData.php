<?php
// 데이터베이스 불러오기
require_once("../db_connect.php");

// Device 데이터를 가져오는 쿼리 작성
$sql = "SELECT * FROM Category";
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
}

// 데이터베이스 연결 닫기
$conn->close();
?>
