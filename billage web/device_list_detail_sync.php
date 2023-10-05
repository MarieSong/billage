<?php
// db_connect.php 파일을 포함합니다.
require_once("db_connect.php");

// POST 요청에서 받은 데이터를 파싱합니다.
$data = json_decode(file_get_contents("php://input"), true);

// rt_id와 newValue 값을 가져옵니다.
$table = $data['table']; //Rental 또는 Repair
$id_name = $data['id_name']; //rt_id인지 rp_id인지
$id = $data['id']; //받아오는 rt_id 또는 rp_id의 실제 값
$editValue = $data['editValue']; //바꾸려는 column 이름
$newValue = $data['newValue']; //column의 값(블록체인에서 받아온 실제 값)

// SQL 쿼리를 사용하여 Rental 테이블을 업데이트합니다.
if ($editValue === 'u_id') {
    $sql_update = "UPDATE $table SET $editValue = $newValue WHERE $id_name = '$id'";
} else {
    $sql_update = "UPDATE $table SET $editValue = '$newValue' WHERE $id_name = '$id'";
}


if ($conn->query($sql_update) === TRUE) {
    $response = array("success" => true);
    echo json_encode($response);
} else {
    $response = array("success" => false, "error" => $conn->error);
    echo json_encode($response);
}

// 데이터베이스 연결을 닫습니다.
$conn->close();
?>
