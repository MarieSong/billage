<?php
    // db_connect.php 파일을 포함합니다.
    require_once("db_connect.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // POST로 전달받은 rt_id를 가져옵니다.
        $rtId = $_POST['rt_id'];

        // rt_state를 1로 업데이트하는 쿼리를 실행합니다.
        $updateSql = "UPDATE Rental SET rt_state = 1 WHERE rt_id = '$rtId'";

        if ($conn->query($updateSql) === TRUE) {
            echo "<script>alert('수정 성공.');</script>";
        } else {
            echo "<script>alert('에러 발생');</script>";
            echo "Error updating record: " . $conn->error;
        }

        // 데이터베이스 연결을 닫습니다.
        $conn->close();
    }
?>
