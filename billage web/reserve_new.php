<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .divider {
            border-top: 1px solid #ccc;
            margin: 20px 0;
        }


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

        /* 버튼 중앙 정렬 스타일 추가 */
        .center-button {
            display: flex;
            justify-content: center;
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

        <div class="text-center">
            <p>&lt;신규 예약 화면&gt;</p>
        </div>


        <!-- 큰 사각형 틀 시작 -->
        <div class="input-container">

            <!-- 예약 정보 입력 폼 -->
            <form action="reserve_new_dataprocess.php" method="POST">

                <!-- 예약자 ID 입력란 -->
                <div class="form-group">
                    <label for="reserver_id">예약자 ID</label>
                    <input type="text" class="form-control" id="reserver_id" name="reserver_id" required>
                </div>

                <!-- 기기 ID 입력란 -->
                <div class="form-group">
                    <label for="device_id">기기 ID</label>
                    <input type="text" class="form-control" id="device_id" name="device_id" required>
                </div>

                <!-- 대여 기간 입력란 -->
                <div class="form-group">
                    <label for="start_date">대여 시작일</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>

                <div class="form-group">
                    <label for="end_date">기기 반납일</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
                

                <!-- 버튼을 중앙에 정렬하는 클래스 추가 -->
                <div class="center-button">
                    <button type="submit" class="btn btn-primary">등록</button>
                </div>
                
            </form>
        </div>

    
        
    </div>

    <!-- 하단 메뉴 -->
    <?php
        // bottom.php 파일을 포함
        include('bottom.php');
    ?>
</body>
</html>
