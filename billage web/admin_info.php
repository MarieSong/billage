<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        /* 큰 사각형 틀 스타일 */
        .input-container {
            background-color: rgba(242, 242, 242, 0.8);
            padding: 20px;
            width: 70%; 
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 1200px; /* 폼의 최대 너비 조절 */
            margin: 0 auto; /* 가운데 정렬 추가 */
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

        <!-- 환영 메시지 및 사용자 정보 -->
        <div class="input-container">
            <?php
            // 데이터베이스 연결
            require_once("db_connect.php");

            // 현재 세션의 u_id 값을 가져옴
            $u_id = $_SESSION['u_id'];

            // user 테이블에서 정보를 가져오는 쿼리
            $sql = "SELECT * FROM user WHERE u_id='$u_id'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<p class=\"text-center\">관리자 정보 화면입니다.</p>";
                echo "<p>관리자 ID : " . $row['u_id'] . "</p>";
                echo "<p>관리자 이름 : " . $row['u_name'] . "</p>";
                echo "<p>이메일 : " . $row['u_email'] . "</p>";
                echo "<p>연락처 : " . $row['u_phone'] . "</p>";
            } else {
                echo "사용자 정보를 가져오지 못했습니다.";
            }

            // 데이터베이스 연결 닫기
            $conn->close();
            ?>

            <div>   
                <button type="submit" class="btn btn-primary" onclick="openPasswordChange()">비밀번호 변경</button>
            </div>
        </div>
        
    </div>

    <!-- 하단 메뉴 -->
    <?php
        // bottom.php 파일을 포함
        include('bottom.php');
    ?>

    <script>
        function openPasswordChange() {
            window.open('admin_info_pwchange.php', '_blank', 'width=500,height=400');
        }
    </script>

</body>
</html>
