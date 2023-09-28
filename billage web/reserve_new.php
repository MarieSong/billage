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

        /* 탐색 버튼과 등록 버튼 간격 조절 */
        .button-spacing {
            margin-right: 20px; /* 원하는 간격으로 조절하세요 */
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
                

                <!-- 등록 버튼과 탐색 버튼을 포함하는 div -->
                <div class="center-button">
                    <button type="button" class="btn btn-secondary button-spacing" id="exploreButton">탐색</button>
                    <button type="submit" class="btn btn-primary" id="registerButton" disabled>등록</button>
                </div>
                
            </form>
        </div>

    
        
    </div>

    <!-- 하단 메뉴 -->
    <?php
        // bottom.php 파일을 포함
        include('bottom.php');
    ?>

    <script>
        document.getElementById('exploreButton').addEventListener('click', function() {
            updateRegisterButtonAvailability();
        });

        function updateRegisterButtonAvailability() {
            // 예약자 ID, 기기 ID, 대여 시작일, 기기 반납일 값을 가져옴
            var reserver_id = document.getElementById('reserver_id').value;
            var device_id = document.getElementById('device_id').value;
            var start_date = document.getElementById('start_date').value;
            var end_date = document.getElementById('end_date').value;

            // AJAX를 이용하여 값을 reserve_new_confirm.php로 전달
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'reserve_new_confirm.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
                    if (xhr.responseText === "available") {
                        // 대여 가능한 경우 등록 버튼 활성화
                        document.getElementById('registerButton').disabled = false;
                        alert('대여가 가능합니다.');
                    } else if (xhr.responseText === "user not found") {
                        // 대여 불가능한 경우 등록 버튼 비활성화
                        document.getElementById('registerButton').disabled = true;
                        alert('등록된 사용자가 아닙니다. 예약자 ID를 확인하세요.');
                    } else if (xhr.responseText === "device not found") {
                        // 대여 불가능한 경우 등록 버튼 비활성화
                        document.getElementById('registerButton').disabled = true;
                        alert('등록된 기기가 아닙니다. 기기 ID를 확인하세요.');
                    } else if (xhr.responseText === "unavailable user") {
                        // 대여 불가능한 경우 등록 버튼 비활성화
                        document.getElementById('registerButton').disabled = true;
                        alert('이미 대여중인 기기가 있습니다. 대여가 불가능합니다.');
                    } else if (xhr.responseText === "unavailable device") {
                        // 대여 불가능한 경우 등록 버튼 비활성화
                        document.getElementById('registerButton').disabled = true;
                        alert('이미 다른 사용자가 사용중입니다. 대여가 불가능합니다.');
                    } else {
                        document.getElementById('registerButton').disabled = true;
                        alert('오류가 발생했습니다. 다시 시도해 주세요.');
                    }
                }
            }
            xhr.send('reserver_id=' + encodeURIComponent(reserver_id) + '&device_id=' + encodeURIComponent(device_id) + '&start_date=' + encodeURIComponent(start_date) + '&end_date=' + encodeURIComponent(end_date));
        }


        function checkTextWindowChange() {
            // 등록 버튼 비활성화
            document.getElementById('registerButton').disabled = true;
        }

        document.getElementById('reserver_id').addEventListener('input', checkTextWindowChange);
        document.getElementById('device_id').addEventListener('input', checkTextWindowChange);
        document.getElementById('start_date').addEventListener('input', checkTextWindowChange);
        document.getElementById('end_date').addEventListener('input', checkTextWindowChange);

    </script>
</body>
</html>
