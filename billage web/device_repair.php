<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/button_submit.css">
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

    <script>
        function openDeviceSearch() {
            // 새 창 열기
            var deviceSearchWindow = window.open('device_repair_devicesearch.php', '_blank', 'width=600,height=400');

            /* 새 창이 로드될 때 실행될 콜백 함수 정의
            deviceSearchWindow.onload = function() {
                // 부모 창으로부터 기기 정보를 받아온다면
                deviceSearchWindow.onDeviceSelected = function(deviceId, deviceToken) {
                    // 받아온 기기 정보를 텍스트 창에 설정
                    
                };
            };*/
        }

        function onDeviceSelected(deviceId, deviceToken) {
            document.getElementById('device_id').value = deviceId;
            document.getElementById('device_token').value = deviceToken;
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

        <div class="text-center">
            <p>&lt;수리 기록 등록 화면&gt;</p>
        </div>


        <!-- 큰 사각형 틀 시작 -->
        <div class="input-container">

            <!-- 기기 정보 입력 폼 -->
            
                <div class="form-group">
                    <label for="repair_id">수리 ID</label>
                    <?php
                        // 데이터베이스 연결
                        require_once("db_connect.php");

                        // 현재 날짜 가져오기 (YYMMDD 형식)
                        $todayDate = date("ymd");

                        // 관리자 ID
                        $adminID = $_SESSION['u_id'];

                        // rp_id 생성을 위한 SQL 쿼리
                        $sql = "SELECT MAX(SUBSTRING_INDEX(rp_id, '-', -1)) as max_num 
                                FROM Repair 
                                WHERE SUBSTRING_INDEX(rp_id, '-', 2) = 'rp{$adminID}-{$todayDate}'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $maxNum = $row['max_num'];

                            // 오늘 등록된 수리 기록이 없을 경우
                            if ($maxNum === null) {
                                $newNum = "001";
                            } else {
                                $newNum = str_pad((intval($maxNum) + 1), 3, '0', STR_PAD_LEFT);
                            }

                            // rp_id 생성
                            $rpID = "rp{$adminID}-{$todayDate}-{$newNum}";
                            // 기기 ID 출력
                        echo '<input type="text" class="form-control" id="repair_id" name="repair_id" value="'.$rpID.'" readonly>';
                        } else {
                            echo "Error: " . $conn->error;
                        }

                        // 데이터베이스 연결 닫기
                        $conn->close();
                    ?>
                </div>
                <div class="form-group">
                    <label for="device_id">기기 ID</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="device_id" name="device_id" readonly>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary" onclick="openDeviceSearch()">찾기</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="device_tokne">토큰 ID</label>
                    <input type="text" class="form-control" id="device_token" name="device_token" readonly>
                </div>
                <div class="form-group">
                    <label for="repair_discover">고장 발견일</label>
                    <input type="date" class="form-control" id="repair_discover" name="repair_discover" required>
                </div>
                <div class="form-group">
                    <label for="repair_start">수리 시작일</label>
                    <input type="date" class="form-control" id="repair_start" name="repair_start" required>
                    
                </div>

                <!-- repair_info를 hidden 필드로 추가 -->
                <input type="hidden" id="repair_info" name="repair_info" value="">

                <div class="form-group">
                    <label for="repair_info">수리 내용</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="repair_info_0" value="0" onchange="updateRepairInfo()">
                        <label class="form-check-label" for="repair_info_0">액정 파손</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="repair_info_1" value="1" onchange="updateRepairInfo()">
                        <label class="form-check-label" for="repair_info_1">키보드 고장</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="repair_info_2" value="2" onchange="updateRepairInfo()">
                        <label class="form-check-label" for="repair_info_2">카메라 고장</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="repair_info_3" value="3" onchange="updateRepairInfo()">
                        <label class="form-check-label" for="repair_info_3">터치패드 고장</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="repair_info_4" value="4" onchange="updateRepairInfo()">
                        <label class="form-check-label" for="repair_info_4">HDMI/USB 포트 고장</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="repair_info_5" value="5" onchange="updateRepairInfo()">
                        <label class="form-check-label" for="repair_info_5">스피커/오디오 고장</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="repair_info_6" value="6" onchange="updateRepairInfo()">
                        <label class="form-check-label" for="repair_info_62">메인보드 고장</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="repair_info_7" value="7" onchange="updateRepairInfo()">
                        <label class="form-check-label" for="repair_info_7">배터리 교체</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="repair_info_8" value="8" onchange="updateRepairInfo()">
                        <label class="form-check-label" for="repair_info_8">RAM 업그레이드</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="repair_info_9" value="9" onchange="updateRepairInfo()">
                        <label class="form-check-label" for="repair_info_9">SSD 업그레이드</label>
                    </div>
                    
                </div>

                
                <!-- 버튼을 중앙에 정렬하는 클래스 추가 -->
                <div class="center-button">
                    <div class="mx-2">
                        <button type="button" class="btn btn-primary" id='repairEnter' onclick="submitForm()">등록</button>
                    </div>
                </div>
                
            

            <script>
                function updateRepairInfo() {
                    // 체크된 체크박스들의 값을 합쳐서 repair_info에 저장
                    var repair_info = "";
                    for (var i = 0; i < 10; i++) {
                        if (document.getElementById('repair_info_' + i).checked) {
                            repair_info += i;
                        }
                    }

                    // hidden 필드의 값을 업데이트
                    document.getElementById('repair_info').value = repair_info;
                }

                function submitForm() {
                    // 입력값 가져오기
                    var repair_id = document.getElementById('repair_id').value;
                    var device_id = document.getElementById('device_id').value;
                    var device_token = document.getElementById('device_token').value;
                    var repair_discover = document.getElementById('repair_discover').value;
                    var repair_start = document.getElementById('repair_start').value;
                    var repair_info = document.getElementById('repair_info').value;
                    
                    /* repair_info 값을 초기화
                    var repair_info = "";

                    // 체크된 체크박스들의 값을 합쳐서 repair_info에 저장
                    for (var i = 0; i < 10; i++) {
                        if (document.getElementById('repair_info_' + i).checked) {
                            repair_info += i;
                        }
                    }*/

                    // 데이터를 FormData 객체로 만들어서 전송
                    var formData = new FormData();
                    formData.append('repair_id', repair_id);
                    formData.append('device_id', device_id);
                    formData.append('device_token', device_token);
                    formData.append('repair_discover', repair_discover);
                    formData.append('repair_start', repair_start);
                    formData.append('repair_info', repair_info);

                    // AJAX를 사용하여 폼 데이터를 서버에 전송
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'device_repair_dataprocess.php', true);
                    xhr.onload = function () {
                        if (xhr.status >= 200 && xhr.status < 400) {
                            // 서버에서 응답을 받았을 때 수행할 작업
                            if (xhr.responseText === 'Success') {
                                alert('저장 완료.');
                                // 저장 완료 후 화면 새로고침
                                location.reload();
                            } else {
                                // 에러 처리
                                console.error('Error:', xhr.responxeText);
                            }
                        } else {
                            // 에러 처리
                            console.error('Error:', xhr);
                        }
                    };
                    xhr.onerror = function () {
                        // 통신 중 에러 발생
                        console.error('Network Error');
                    };
                    xhr.send(formData);
                }
            </script>
        </div>

    
        
    </div>

    
    <script src="js/web3.min.js"></script>
    <script src="js/repair.js"></script>

    

    <!-- 하단 메뉴 -->
    <?php
        // bottom.php 파일을 포함
        include('bottom.php');
    ?>

</body>
</html>
