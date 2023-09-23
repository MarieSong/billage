<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script>
        function closeWindow() {
            window.close();
        }

        function confirmRow(rtId) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'reserve_update_state.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 400) {
                    alert('기기 수령 완료.');
                    location.reload();
                }
            };

            xhr.onerror = function () {
                console.log('Error');
            };

            xhr.send('rt_id=' + rtId);
        }
    </script>

</head>
<body>
    <div class="container">

        <?php
        // 데이터베이스 연결
        require_once("db_connect.php");

        // 사용자 정보 가져오기
        if(isset($_GET['rt_id'])) {
            $rental_id = $_GET['rt_id'];
            $sql_rental = "SELECT d.d_name, d.d_model, d.d_id, rt.u_id, rt_start, rt.rt_state FROM Rental rt, Device d WHERE rt.d_id = d.d_id AND rt.rt_id = '$rental_id'";
            $result_rental = $conn->query($sql_rental);

            if ($result_rental->num_rows > 0) {
                $row_rental = $result_rental->fetch_assoc();
                echo "<h2>User Information</h2>";
                echo "<div class='row'>";
                echo "<div class='col-sm-6'><p><strong>기기 ID :</strong> " . $row_rental['d_id'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>기기 이름 :</strong> " . $row_rental['d_name'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>모델명 :</strong> " . $row_rental['d_model'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>대여자 ID :</strong> " . $row_rental['u_id'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>대여 시작일 :</strong> " . $row_rental['rt_start'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>현재 상태 :</strong> " . $row_rental['rt_state'] . "</p></div>";
                echo "</div>";

                // "수령확인" 버튼 추가
                echo "<div class='text-center mt-4'>";
                echo "<button class='btn btn-primary' onclick='confirmRow(\"" . $rental_id . "\")'>수령확인</button>";
                echo "</div>";
                } else {
                echo $rental_id;
                echo "Rental not found.";
            }
        } else {
            echo "User ID not provided.";
        }

        // 데이터베이스 연결 닫기
        $conn->close();
        ?>

        <!-- 닫기 버튼 추가 -->
        <div class="text-center mt-4">
            <button class="btn btn-secondary" onclick="closeWindow()">닫기</button>
        </div>

    </div>
</body>
</html>