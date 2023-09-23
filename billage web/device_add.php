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
        function generateToken() {
            // device_id, device_name, device_model 값을 가져와서 토큰을 생성
            // ... (토큰 생성 코드)

            // 생성된 토큰을 받아와서 화면에 표시
            var generatedToken = '새로운_토큰_값'; // 여기에 실제로 생성된 토큰 값이 들어가야 합니다.
            document.getElementById('token_id').textContent = generatedToken;
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
            <p>&lt;기기 등록 화면&gt;</p>
        </div>


        <!-- 큰 사각형 틀 시작 -->
        <div class="input-container">

            <!-- 기기 정보 입력 폼 -->
            <form id="createNFTForm" action="device_add_dataprocess.php" method="POST">
                <div class="form-group">
                    <label for="device_id">기기 ID</label>
                    <?php
                        // 데이터베이스 불러오기
                        require_once("db_connect.php");

                        // d_id 생성 로직
                        // 현재까지 가장 큰 번호를 가져오는 쿼리
                        $sql_max_id = "SELECT MAX(SUBSTRING_INDEX(d_id, '-', -1)) AS max_id FROM Device";
                        $result_max_id = $conn->query($sql_max_id);
                        if ($result_max_id->num_rows > 0) {
                            $row_max_id = $result_max_id->fetch_assoc();
                            $max_id = $row_max_id['max_id'];
                            $next_id = $max_id + 1;
                            $admin_id = '12345';
                            $d_id = 'd' . $admin_id . '-' . str_pad($next_id, 3, '0', STR_PAD_LEFT);
                        } else {
                            // 만약 데이터베이스에 아무런 레코드가 없다면 초기값으로 설정
                            $d_id = 'd12345-001';
                        }
                        // 기기 ID 출력
                        echo '<input type="text" class="form-control" id="device_id" name="device_id" value="'.$d_id.'" readonly>';
                    ?>
                </div>
                <div class="form-group">
                    <label for="device_name">기기명</label>
                    <input type="text" class="form-control" id="device_name" name="device_name" required>
                </div>
                <div class="form-group">
                    <label for="device_model">모델명</label>
                    <input type="text" class="form-control" id="device_model" name="device_model" required>
                </div>
                <div class="form-group">
                    <label for="device_category">카테고리</label>
                    <select class="form-control" id="device_category" name="device_category" required>
                        <?php

                            // 카테고리 목록 불러오기 (최상위 카테고리만)
                            $sql_top_categories = "SELECT * FROM Category WHERE c_top_id IS NULL";
                            $result_top_categories = $conn->query($sql_top_categories);

                            // 최상위 카테고리가 있을 경우 드롭다운 목록에 추가
                            if ($result_top_categories->num_rows > 0) {
                                while($row_top_category = $result_top_categories->fetch_assoc()) {
                                    $top_category_id = $row_top_category['c_id'];
                                    $top_category_name = $row_top_category['c_name'];
                                    echo "<option value='".$top_category_id."'>".$top_category_name."</option>";

                                    // 하위 카테고리 목록 불러오기
                                    $sql_sub_categories = "SELECT * FROM Category WHERE c_top_id = '$top_category_id'";
                                    $result_sub_categories = $conn->query($sql_sub_categories);

                                    // 하위 카테고리가 있을 경우 드롭다운 목록에 추가
                                    if ($result_sub_categories->num_rows > 0) {
                                        while($row_sub_category = $result_sub_categories->fetch_assoc()) {
                                            $sub_category_id = $row_sub_category['c_id'];
                                            $sub_category_name = $row_sub_category['c_name'];
                                            echo "<option value='".$sub_category_id."'>&nbsp;&nbsp;&nbsp;&nbsp;".$sub_category_name."</option>";
                                        }
                                    }
                                }
                            }

                            // 데이터베이스 연결 종료
                            $conn->close();
                        ?>
                    </select>
                </div>
                
                <!-- 구분선 -->
                <div class="divider"></div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="device_storage">저장공간</label>
                            <input type="text" class="form-control" id="device_storage" name="device_storage" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="device_ram">RAM</label>
                            <input type="text" class="form-control" id="device_ram" name="device_ram" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="device_cpu">CPU</label>
                            <input type="text" class="form-control" id="device_cpu" name="device_cpu" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="device_size">크기(인치)</label>
                            <input type="text" class="form-control" id="device_size" name="device_size" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="device_os">운영체제</label>
                            <input type="text" class="form-control" id="device_os" name="device_os" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="device_weight">무게</label>
                            <input type="text" class="form-control" id="device_weight" name="device_weight" required>
                        </div>
                    </div>
                </div>

                <div class="row"> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="device_manufacturer">제조사</label>
                            <input type="text" class="form-control" id="device_manufacturer" name="device_manufacturer" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="token_id">토큰 ID</label>
                            <span id="token_id" name="token_id" >토큰 생성 필요</span>
                        </div>
                    </div>
                </div>

                
                <!-- 버튼을 중앙에 정렬하는 클래스 추가 -->
                <div class="center-button">
                    <button class="button-submit" onclick="generateToken()">토큰 생성</button>
                    <button type="submit" class="button-submit btn btn-primary">등록</button>
                </div>
                
            </form>
        </div>

    
        
    </div>

    <!-- 하단 메뉴 -->
    <?php
        // bottom.php 파일을 포함
        include('bottom.php');
    ?>

    <script src="web3.min.js"></script>
    <script src="app.js"></script>
</body>
</html>
