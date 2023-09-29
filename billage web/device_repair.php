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
            <form id="createNFTForm" action="device_repair_dataprocess.php" method="POST">
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
                    <input type="text" class="form-control" id="device_id" name="device_id" required>
                </div>
                <div class="form-group">
                    <label for="repiar_discover">고장 발견일</label>
                    <input type="date" class="form-control" id="repiar_discover" name="repair_discover" required>
                </div>
                <div class="form-group">
                    <label for="repair_start">수리 시작일</label>
                    <input type="date" class="form-control" id="repair_start" name="repair_start" required>
                    
                </div>
                <div class="form-group">
                    <label for="repair_info">수리 내용</label>
                    <input type="text" class="form-control" id="repair_info" name="repair_info" required>
                    
                </div>

                
                <!-- 버튼을 중앙에 정렬하는 클래스 추가 -->
                <div class="center-button">
                    <div class="mx-2">
                        <button type="submit" class="button-submit btn btn-primary">등록</button>
                    </div>
                </div>
                
            </form>
        </div>

    
        
    </div>

    
    <!--<script src="web3.min.js"></script>
    <script src="create.js"></script>-->

    <!-- 하단 메뉴 -->
    <?php
        // bottom.php 파일을 포함
        include('bottom.php');
    ?>

</body>
</html>
