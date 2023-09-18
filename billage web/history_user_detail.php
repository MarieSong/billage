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
    </script>
</head>
<body>
    <div class="container">

        <?php
        // 데이터베이스 연결
        require_once("db_connect.php");

        // 사용자 정보 가져오기
        if(isset($_GET['u_id'])) {
            $user_id = $_GET['u_id'];
            $sql_user = "SELECT * FROM user WHERE u_id = '$user_id'";
            $result_user = $conn->query($sql_user);

            if ($result_user->num_rows > 0) {
                $row_user = $result_user->fetch_assoc();
                echo "<h2>User Information</h2>";
                echo "<div class='row'>";
                echo "<div class='col-sm-6'><p><strong>User ID :</strong> " . $row_user['u_id'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>Name :</strong> " . $row_user['u_name'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>Email :</strong> " . $row_user['u_email'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>Phone :</strong> " . $row_user['u_phone'] . "</p></div>";
                echo "</div>";
            } else {
                echo "User not found.";
            }
        } else {
            echo "User ID not provided.";
        }

        // 대여 기록 가져오기
        $sql_rental = "SELECT rt_id, d_id, rt_book, rt_start, rt_deadline, IF(rt_return IS NULL, '미반납', rt_return) AS rt_return, rt_state
                       FROM Rental
                       WHERE u_id = '$user_id'";
        $result_rental = $conn->query($sql_rental);

        if ($result_rental->num_rows > 0) {
            echo "<h2>Rental History</h2>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Reservation ID</th><th>Device ID</th><th>Reservation Date</th><th>Rental Start Date</th><th>Rental End Date</th><th>Return Date</th><th>Rental State</th></tr>";

            while ($row_rental = $result_rental->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_rental['rt_id'] . "</td>";
                echo "<td>" . $row_rental['d_id'] . "</td>";
                echo "<td>" . $row_rental['rt_book'] . "</td>";
                echo "<td>" . $row_rental['rt_start'] . "</td>";
                echo "<td>" . $row_rental['rt_deadline'] . "</td>";
                echo "<td>" . $row_rental['rt_return'] . "</td>";
                echo "<td>";

                switch ($row_rental['rt_state']) {
                    case 0:
                        echo "예약 완료";
                        break;
                    case 1:
                        echo "수령 완료";
                        break;
                    case 2:
                        echo "예약 취소";
                        break;
                    case 3:
                        echo "반납 완료";
                        break;
                    case 4:
                        echo "연체";
                        break;
                    default:
                        echo "알 수 없음";
                        break;
                }

                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "</div>";
        } else {
            echo "No rental history found for this user.";
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
