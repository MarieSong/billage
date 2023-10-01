<!DOCTYPE html>
<html>
<head>
    <title>Device Search</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Device Search</h2>

        <?php
            // 데이터베이스 연결
            require_once("db_connect.php");

            // Device와 Category 테이블에서 데이터를 가져옴
            $sql = "SELECT d.d_id, d.d_name, d.d_model, c.c_name, d.d_token
                    FROM Device d
                    INNER JOIN Category c ON d.c_id = c.c_id
                    ORDER BY d.d_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead class='thead-dark'><tr><th>기기 ID</th><th>기기 이름</th><th>모델명</th><th>카테고리</th><th></th></tr></thead>";
                echo "<tbody>";

                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["d_id"] . "</td>";
                    echo "<td>" . $row["d_name"] . "</td>";
                    echo "<td>" . $row["d_model"] . "</td>";
                    echo "<td>" . $row["c_name"] . "</td>";
                    echo "<td><button class='btn btn-primary' onclick='selectDevice(\"" . $row["d_id"] . "\", \"" . $row["d_token"] . "\")'>선택</button></td>";
                    echo "</tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "0 results";
            }

            // 데이터베이스 연결 닫기
            $conn->close();
        ?>

        <script>
            function selectDevice(deviceId, deviceToken) {
                window.opener.onDeviceSelected(deviceId, deviceToken);
                window.close();
            }
        </script>
    </div>
</body>
</html>
