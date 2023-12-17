<?php
    require_once("db_connect.php");

    if(isset($_POST['rp_id']) && isset($_POST['rp_return_date'])) {
        $repair_id = $_POST['rp_id'];
        $return_date = $_POST['rp_return_date'];
        $sql = "SELECT d_id FROM Repair WHERE rp_id = '$repair_id'";
        $result_sql = $conn->query($sql);
        $row_sql = $result_sql->fetch_assoc();

        // SQL 쿼리를 이용하여 rp_return 값을 업데이트합니다.
        $update_query = "UPDATE Repair SET rp_return = '$return_date' WHERE rp_id = '$repair_id'";

        if ($conn->query($update_query) === TRUE) {
            // Device 테이블에서 d_id에 해당하는 기기의 d_state를 1로 변경
            $update_device_sql = "UPDATE Device SET d_state = 0 WHERE d_id = '" . $row_sql['d_id'] . "'";
            echo "<script>alert('저장이 완료되었습니다.');</script>";
            echo "<script>window.location.href = 'device_list_detail.php?d_id=" . $row_sql['d_id'] . "';</script>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Invalid request.";
    }

    $conn->close();
    ?>
