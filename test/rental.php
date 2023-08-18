<!DOCTYPE html>
<html>
<head>
    <title>Rental System</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1>Rental System</h1>

    <form method="post">
        <div class="form-group">
            <label for="device_id">Device ID:</label>
            <input type="text" class="form-control" id="device_id" name="device_id" value="<?php echo isset($_POST['device_id']) ? $_POST['device_id'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="start_date">Rental Start Date:</label>
            <input type="number" class="form-control" id="start_year" name="start_year" placeholder="Year" value="<?php echo isset($_POST['start_year']) ? $_POST['start_year'] : ''; ?>" required>
            <input type="number" class="form-control" id="start_month" name="start_month" placeholder="Month" value="<?php echo isset($_POST['start_month']) ? $_POST['start_month'] : ''; ?>" required>
            <input type="number" class="form-control" id="start_day" name="start_day" placeholder="Day" value="<?php echo isset($_POST['start_day']) ? $_POST['start_day'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="end_date">Rental End Date:</label>
            <input type="number" class="form-control" id="end_year" name="end_year" placeholder="Year" value="<?php echo isset($_POST['end_year']) ? $_POST['end_year'] : ''; ?>" required>
            <input type="number" class="form-control" id="end_month" name="end_month" placeholder="Month" value="<?php echo isset($_POST['end_month']) ? $_POST['end_month'] : ''; ?>" required>
            <input type="number" class="form-control" id="end_day" name="end_day" placeholder="Day" value="<?php echo isset($_POST['end_day']) ? $_POST['end_day'] : ''; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary" name="check_rental">Check Rental</button>
        
    </form>

    <?php
    // MySQL 데이터베이스 연결 정보
    $db_host = 'localhost';      // 호스트 주소
    $db_username = 'billage';      // 데이터베이스 사용자 이름
    $db_password = 'teambym2023!';      // 데이터베이스 비밀번호
    $db_name = 'billage';     // 데이터베이스 이름

    // 데이터베이스 연결
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // 연결 확인
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST['check_rental'])) {
        $device_id = $_POST['device_id'];
        $start_year = $_POST['start_year'];
        $start_month = $_POST['start_month'];
        $start_day = $_POST['start_day'];
        $end_year = $_POST['end_year'];
        $end_month = $_POST['end_month'];
        $end_day = $_POST['end_day'];

        $start_date = "$start_year-$start_month-$start_day";
        $end_date = "$end_year-$end_month-$end_day";

        //발견되는 쿼리가 존재하면 해당 기간에 이미 대여된 것(즉, 대여)
        $sql = "SELECT *
                FROM Rental
                WHERE d_id = '$device_id'
                    AND (rt_start <= '$end_date' AND rt_deadline >= '$start_date')";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) { //대여 불가능
            echo "<p class='text-danger'>Device is not available for rental during the specified period.</p>";
        } else {
            echo "<p class='text-success'>Device is available for rental during the specified period.</p>";
        }
    }

    // 데이터베이스 연결 닫기
    $conn->close();
    ?>


    <br>
        <!-- Go Home 버튼 추가 -->
        <button onclick="goHome()" class="btn btn-secondary">Go Home</button>

        <script>
            function goHome() {
                // index.php로 이동
                window.location.href = "index.php";
            }
        </script>

</body>
</html>
