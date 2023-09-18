<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* 표 전체에 적용되는 스타일 */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px; /* 표 간격 조절 */
        }

        /* 표 헤더에 적용되는 스타일 */
        th {
            background-color: #f2f2f2;
        }

        /* 표 셀에 적용되는 스타일 */
        td, th {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
        }

        /* 링크에 적용되는 스타일 */
        a {
            color: #007bff;
            text-decoration: none;
        }

        /* 링크에 호버 효과 적용 */
        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function openRentalHistory(userId) {
            window.open('history_user_detail.php?u_id=' + userId, '_blank', 'width=1200,height=400');
        }
    </script>
</head>
<body>
    <div class="container">

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

            // user 테이블 정보 가져오기 (전체 사용자)
            $sql = "SELECT * FROM user WHERE u_role=1"; //관리자를 제외한 사용자만 찾는다.
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
                    // Add a link to open rental history in a new window
                    echo "<td><a href='#' onclick='openRentalHistory(" . $row['u_id'] . "); return false;'>" . $row['u_name'] . "</a></td>";
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

            // 데이터베이스 연결 닫기
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
