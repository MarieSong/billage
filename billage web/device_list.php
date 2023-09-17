<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* 표의 각 칸 간격을 넓히는 CSS 스타일 */
        table {
            width: 100%; /* 표의 전체 너비를 화면 너비에 맞춥니다. */
            border-collapse: collapse; /* 표의 경계를 합칩니다. */
        }

        th, td {
            padding: 10px; /* 각 셀의 안팎 여백을 더 넓힙니다. */
            text-align: center; /* 셀 내부의 텍스트 가운데 정렬합니다. */
        }

        /* 표의 헤더 셀(제목 행) 스타일 */
        th {
            background-color: #f2f2f2; /* 배경색 지정 */
        }

        /* 번호 셀 너비를 고정하기 위한 스타일 */
        td:first-child {
            width: 50px; /* 번호 셀의 너비를 조절합니다. */
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

        <!-- 사용자 정보 불러오기 -->
        <div class="text-center">
            <p>&lt;전체 기기 조회 및 검색&gt;</p>
        </div>

        <!-- 카테고리 검색 드롭다운과 조회 버튼 -->
        <div class="text-center mb-4">
            <form action="device_list.php" method="get">
                <select name="category_id" class="form-control d-inline-block" style="width: auto;">
                    <?php
                        // 데이터베이스 불러오기
                        require_once("db_connect.php");

                        // 재귀적으로 카테고리를 가져와서 계층 구조를 유지
                        function fetchCategories($conn, $parent_id = null, $prefix = '') {
                            $sql = "SELECT * FROM Category WHERE c_top_id " . ($parent_id ? "= '$parent_id'" : "IS NULL");
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $selected = isset($_GET['category_id']) && $_GET['category_id'] == $row['c_id'] ? 'selected' : '';
                                    echo "<option value='" . $row['c_id'] . "' $selected>" . $prefix . $row['c_name'] . "</option>";
                                    fetchCategories($conn, $row['c_id'], $prefix . '--');
                                }
                            }
                        }

                        fetchCategories($conn);
                    ?>
                </select>
                <button type="submit" class="btn btn-primary">조회</button>
            </form>
        </div>

        <!-- 기기 목록 -->
        <div class="text-center">

            <?php
        
            // 데이터베이스 불러오기
            require_once("db_connect.php");

            if(isset($_GET['category_id'])) {
                // URL에서 전달받은 카테고리 ID 가져오기
                $category_id = $_GET['category_id'];

                // 해당 카테고리가 상위 카테고리인지 확인
                $sql_check_top = "SELECT c_bottom FROM Category WHERE c_id = '$category_id'";
                $result_check_top = $conn->query($sql_check_top);

                if ($result_check_top->num_rows > 0) {
                    $row_check_top = $result_check_top->fetch_assoc();

                    if ($row_check_top['c_bottom'] == 1) {
                        // 하위 카테고리인 경우
                        $sql = "SELECT 
                                    d_name, 
                                    d_model, 
                                    d_id, 
                                    c_name, 
                                    d_token, 
                                    CASE 
                                        WHEN d_state = 0 THEN '대여 가능'
                                        WHEN d_state = 1 THEN '수리중'
                                        WHEN d_state = 2 THEN '대여 불가능'
                                        ELSE '알 수 없음'
                                    END AS d_state
                                FROM 
                                    Device, 
                                    Category 
                                WHERE 
                                    Device.c_id = Category.c_id
                                    AND Category.c_id = '$category_id'
                                ";
                    } else {
                        // 상위 카테고리인 경우
                        $sql = "SELECT 
                                    d_name, 
                                    d_model, 
                                    d_id, 
                                    c_name, 
                                    d_token, 
                                    CASE 
                                        WHEN d_state = 0 THEN '대여 가능'
                                        WHEN d_state = 1 THEN '수리중'
                                        WHEN d_state = 2 THEN '대여 불가능'
                                        ELSE '알 수 없음'
                                    END AS d_state
                                FROM 
                                    Device, 
                                    Category 
                                WHERE 
                                    Device.c_id = Category.c_id
                                    AND (Category.c_id = '$category_id' OR Category.c_top_id = '$category_id')
                                ORDER BY Device.d_id
                                ";
                    }
                }
            } else {
                // 카테고리가 선택되지 않은 경우 모든 기기 정보 가져오기
                $sql = "SELECT 
                            d_name, 
                            d_model, 
                            d_id, 
                            c_name, 
                            d_token, 
                            CASE 
                                WHEN d_state = 0 THEN '대여 가능'
                                WHEN d_state = 1 THEN '수리중'
                                WHEN d_state = 2 THEN '대여 불가능'
                                ELSE '알 수 없음'
                            END AS d_state
                        FROM 
                            Device, 
                            Category 
                        WHERE 
                            Device.c_id = Category.c_id
                        ";
            }

            $result = $conn->query($sql);


            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>번호</th><th>기기명</th><th>모델명</th><th>기기ID</th><th>카테고리</th><th>토큰ID</th><th>기기상태</th></tr>";
 
                // 각 행 정보 출력
                $rowNumber = 1; // 처음 숫자를 1로 설정
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $rowNumber . "</td>"; //번호
                    echo "<td>" . $row['d_name'] . "</td>"; // 기기명
                    echo "<td>" . $row['d_model'] . "</td>"; // 모델명
                    echo "<td>" . $row['d_id'] . "</td>"; // 기기ID
                    echo "<td>" . $row['c_name'] . "</td>"; // 카테고리
                    echo "<td>" . $row['d_token'] . "</td>"; // 토큰ID
                    echo "<td>" . $row['d_state'] . "</td>"; // 기기상태
                    echo "</tr>";

                    // 다음 행을 위해 숫자 증가
                    $rowNumber++;
                }

                echo "</table>";
            } else {
                echo "등록된 기기가 없습니다.";
            }

            // 데이터베이스 연결 닫기
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
