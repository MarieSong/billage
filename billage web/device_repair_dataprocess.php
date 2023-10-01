<?php
    // 데이터베이스 연결
    require_once("db_connect.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 폼에서 전달받은 값 가져오기
        $repair_id = $_POST['repair_id'];
        $device_id = $_POST['device_id'];
        $repair_discover = $_POST['repair_discover'];
        $repair_start = $_POST['repair_start'];
        $repair_info = $_POST['repair_info'];

        // SQL 쿼리 실행
        $sql = "INSERT INTO Repair (rp_id, d_id, rp_discover, rp_start, rp_return, rp_info)
                VALUES ('$repair_id', '$device_id', '$repair_discover', '$repair_start', NULL, '$repair_info')";

        if ($conn->query($sql) === TRUE) { //성공한 경우

            // Device 테이블에서 d_id에 해당하는 기기의 d_state를 1로 변경
            $update_device_sql = "UPDATE Device SET d_state = 1 WHERE d_id = '$device_id'";
            if ($conn->query($update_device_sql) !== TRUE) {
                echo "Error updating device state: " . $conn->error;
            }

            echo "<script>
                    setTimeout(function() {
                        
                        window.location.replace('device_repair.php');
                    }, 100); // 100 밀리초(0.1초) 후에 실행
                  </script>";
        } else { //실패한 경우(에러 처리)
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // 데이터베이스 연결 닫기
    $conn->close();
?>
