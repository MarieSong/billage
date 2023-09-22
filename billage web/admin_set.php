<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">

        <!-- 상단 메뉴 -->
        <?php
        // top.php 파일을 포함
        include('top.php');
        ?>

        <!-- 환영 메시지 -->
        <div class="text-center">
            <p>환경설정 화면입니다.</p>
        </div>
    </div>

    <!-- 하단 메뉴 -->
    <?php
        // bottom.php 파일을 포함
        include('bottom.php');
    ?>

</body>
</html>
