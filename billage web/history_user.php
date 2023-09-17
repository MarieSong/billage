<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Billage Administrator Page</h1>

        <!-- 상단 메뉴 -->
        <?php
        // top.php 파일을 포함
        include('top.php');
        ?>

        <!-- 사용자 정보 불러오기 -->
        <div class="text-center">
            <p>&lt;사용자조회&gt;</p>
        </div>
        <div class="text-center">

            <?php
        
            //데이터베이스 불러오기
            require_once("db_connect.php");

            // 사용자가 입력한 이름 가져오기
            if (isset($_POST['search_name'])) {
                $search_name = $_POST['search_name'];
            } else {
                $search_name = '';
            }

            // user 테이블 정보 가져오기 (검색)
            $sql = "SELECT * FROM user WHERE u_name LIKE '%$search_name%' AND u_role=1"; //관리자를 제외한 사용자만 찾는다.
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>번호</th><th>사용자 ID</th><th>이름</th><th>연락처</th><th>E-mail</th></tr>";
 
                // 각 행 정보 출력
                $rowNumber = 1; // 처음 숫자를 1로 설정
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $rowNumber . "</td>"; //번호
                    echo "<td>" . $row['u_id'] . "</td>"; //사용자ID
                    // Add a link to show rental history when clicking on the name
                    echo "<td><a href='history_user.php?u_id=" . $row['u_id'] . "'>" . $row['u_name'] . "</a></td>";
                    echo "<td>" . $row['u_phone'] . "</td>"; //연락처
                    echo "<td>" . $row['u_email'] . "</td>"; //e-mail
                    echo "</tr>";

                    // 다음 행을 위해 숫자 증가
                    $rowNumber++;
                }

                echo "</table>";
            } else {
                echo "No users found.";
            }

            // Show rental history when u_id is passed in the URL parameter
            if (isset($_GET['u_id'])) {
                $user_id = $_GET['u_id'];
                $sql_rental = "SELECT d_id, rt_book, rt_start, rt_deadline, IF(rt_return IS NULL, '미반납', rt_return) AS rt_return 
                            FROM Rental
                            WHERE u_id = '$user_id'";
                $result_rental = $conn->query($sql_rental);

                if ($result_rental->num_rows > 0) {
                    echo "<h2>Rental History : " . $user_id . "</h2>";
                    echo "<table border='1'>";
                    echo "<tr><th>Device ID</th><th>Reservation Date</th><th>Rental Start Date</th><th>Rental End Date</th><th>Return Date</th></tr>";

                    // 각 행 정보 출력
                    while ($row_rental = $result_rental->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row_rental['d_id'] . "</td>";
                        echo "<td>" . $row_rental['rt_book'] . "</td>";
                        echo "<td>" . $row_rental['rt_start'] . "</td>";
                        echo "<td>" . $row_rental['rt_deadline'] . "</td>";
                        echo "<td>" . $row_rental['rt_return'] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "No rental history found for this user.";
                }
            }

            // 데이터베이스 연결 닫기
            $conn->close();
            ?>
        </div>

        <!-- 이름 검색 입력 창과 버튼 추가 -->
        <form method="post">
            <label for="search_name">Search by Name:</label>
            <input type="text" id="search_name" name="search_name" placeholder="Enter name">
            <button type="submit">Search</button>
        </form>

            
    </div>

</body>
</html>
