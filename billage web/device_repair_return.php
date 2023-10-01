<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        /* 추가된 CSS */
        .date-input {
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.2rem;
            border: 1px solid #ced4da;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .date-input:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>

</head>
<body>
<div class="container">
    <!-- 상단 메뉴 -->
    <?php
    // top.php 파일을 포함
    include('top.php');
    ?>

    <!-- 수리 정보 불러오기 -->
    <div class="text-center mb-4">
        <?php
        require_once("db_connect.php");

        if(isset($_POST['rp_id'])) {
            $repair_id = $_POST['rp_id'];

            $sql_repair = "SELECT rp_id, d_id, rp_discover, rp_start, rp_info, rp_return
                           FROM Repair
                           WHERE rp_id = '$repair_id'";
            $result_repair = $conn->query($sql_repair);

            if ($result_repair->num_rows > 0) {
                $row_repair = $result_repair->fetch_assoc();
                echo "<h3>수리 정보</h3>";
                echo "<div class='repair-info'>";
                echo "<div class='row'>";
                echo "<div class='col-sm-6'><p><strong>수리 ID :</strong> " . $row_repair['rp_id'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>수리 기기 ID :</strong> " . $row_repair['d_id'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>고장 발견일 :</strong> " . $row_repair['rp_discover'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>수리 맡긴 날짜 :</strong> " . $row_repair['rp_start'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>수리 정보 :</strong> " . $row_repair['rp_info'] . "</p></div>";
                echo "<div class='col-sm-6'>";
                if (is_null($row_repair['rp_return'])) {
                    // If rp_return is null, display input field
                    echo "<form action='device_repair_return_dataprocess.php' method='post'>";
                    echo "<p><strong>수리 반환 일자 :</strong> ";
                    echo "<input type='hidden' name='rp_id' value='" . $row_repair['rp_id'] . "'>";
                    echo "<input type='date' class='form-control' name='rp_return_date' style='display: inline-block; width: 150px; margin-right: 10px;'>";
                    echo "<input type='submit' class='btn btn-primary' value='저장'>";
                    echo "</p></form>";
                } else {
                    // If rp_return has a value, display it
                    echo $row_repair['rp_return'];
                }
                echo "</div>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "해당 수리 정보를 찾을 수 없습니다.";
            }

            $conn->close();
        } else {
            echo "수리 ID를 찾을 수 없습니다.";
        }
        ?>
    </div>

    <!-- 하단 메뉴 -->
    <?php
    // bottom.php 파일을 포함
    include('bottom.php');
    ?>

</div>
</body>
</html>
