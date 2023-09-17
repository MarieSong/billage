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
    </style>


</head>
<body>
    <div class="container">
        <h1>Billage Administrator Page</h1>

        <!-- 상단 메뉴 -->
        <?php
        // top.php 파일을 포함
        include('top.php');
        ?>

        <div class="text-center">
            <p>&lt;기기 등록 화면&gt;</p>
        </div>


        <!-- 기기 정보 입력 폼 -->
        <form action="device_add_dataprocess.php" method="POST">
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
                        // 데이터베이스 불러오기
                        require_once("db_connect.php");

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
            </div>

            
            <button type="submit" class="btn btn-primary">등록</button>
        </form>

    
        
    </div>
</body>
</html>
