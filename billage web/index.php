<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        /* 이미지 스타일 */
        img {
            max-width: 90%;
            height: auto;
            display: block;
            margin: 0 auto; /* 화면 중앙에 정렬 */
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

        <!-- 환영 메시지 -->
        <div class="text-center">
            <img src="image/welcome_center.png" alt="Welcome"> <!-- 이미지 삽입 -->
        </div>
    </div>

</body>
</html>
