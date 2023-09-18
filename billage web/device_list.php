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

        .pagination {
            display: inline-block;
            padding-left: 0;
            margin: 20px 0;
            border-radius: .25rem;
        }

        .pagination>li {
            display: inline;
        }

        .pagination>li>a,
        .pagination>li>span {
            position: relative;
            float: left;
            padding: 6px 12px;
            margin-left: -1px;
            line-height: 1.42857143;
            color: #337ab7;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        .pagination>li:first-child>a,
        .pagination>li:first-child>span {
            margin-left: 0;
            border-top-left-radius: .25rem;
            border-bottom-left-radius: .25rem;
        }

        .pagination>li:last-child>a,
        .pagination>li:last-child>span {
            border-top-right-radius: .25rem;
            border-bottom-right-radius: .25rem;
        }

        .pagination>li>a:focus,
        .pagination>li>a:hover,
        .pagination>li>span:focus,
        .pagination>li>span:hover {
            color: #23527c;
            background-color: #eee;
            border-color: #ddd;
        }

        .pagination>.active>a,
        .pagination>.active>a:focus,
        .pagination>.active>a:hover,
        .pagination>.active>span,
        .pagination>.active>span:focus,
        .pagination>.active>span:hover {
            z-index: 2;
            color: #fff;
            cursor: default;
            background-color: #337ab7;
            border-color: #337ab7;
        }

        .pagination>.disabled>a,
        .pagination>.disabled>a:focus,
        .pagination>.disabled>a:hover,
        .pagination>.disabled>span,
        .pagination>.disabled>span:focus,
        .pagination>.disabled>span:hover {
            color: #777;
            cursor: not-allowed;
            background-color: #fff;
            border-color: #ddd;
        }

        /* 기기 이름 링크 스타일 */
        .device-link {
            cursor: pointer;
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

        <!-- 사용자 정보 불러오기 -->
        <div class="text-center">
            <p>&lt;전체 기기 조회 및 검색&gt;</p>
        </div>

        <!-- 카테고리 검색 드롭다운과 조회 버튼 -->
        <div class="text-center mb-4">
            <form action="device_list.php" method="get">
                <select name="category_id" class="form-control d-inline-block" style="width: auto;">
                    
                    <!-- '전체' 옵션 추가 -->
                    <option value="">전체</option> 
                    
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

            // 페이지당 표시할 항목 수
            $items_per_page = 10;

            // 현재 페이지
            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

            if(isset($_GET['category_id']) && !empty($_GET['category_id'])) {
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
                                ORDER BY Device.d_id
                                LIMIT " . ($current_page - 1) * $items_per_page . ", $items_per_page";
                        
                        // 페이지네이션을 위한 필터링된 총 아이템 수 쿼리
                        $sql_total_items = "SELECT COUNT(*) as total 
                                            FROM Device, Category 
                                            WHERE Device.c_id = Category.c_id 
                                            AND Category.c_id = '$category_id'";
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
                                LIMIT " . ($current_page - 1) * $items_per_page . ", $items_per_page";

                        // 페이지네이션을 위한 필터링된 총 아이템 수 쿼리
                        $sql_total_items = "SELECT COUNT(*) as total 
                                            FROM Device, Category 
                                            WHERE Device.c_id = Category.c_id 
                                            AND (Category.c_id = '$category_id' OR Category.c_top_id = '$category_id')";
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
                        ORDER BY Device.d_id
                        LIMIT " . ($current_page - 1) * $items_per_page . ", $items_per_page";

                // 페이지네이션을 위한 전체 아이템 수 쿼리
                $sql_total_items = "SELECT COUNT(*) as total FROM Device";
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>번호</th><th>기기명</th><th>모델명</th><th>기기ID</th><th>카테고리</th><th>토큰ID</th><th>기기상태</th></tr>";
 
                // 각 행 정보 출력
                $rowNumber = 1 + ($current_page - 1) * $items_per_page; // 처음 숫자 계산
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $rowNumber . "</td>"; //번호
                    echo "<td class='device-link' data-device-id='" . $row['d_id'] . "'>" . $row['d_name'] . "</td>"; // 기기명
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

                // 페이지네이션 출력
                $result_total_items = $conn->query($sql_total_items);
                $row_total_items = $result_total_items->fetch_assoc();
                $total_items = $row_total_items['total'];
                $total_pages = ceil($total_items / $items_per_page);

                echo "<ul class='pagination'>";
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<li class='" . ($current_page == $i ? "active" : "") . "'><a href='device_list.php?page=$i" . (isset($_GET['category_id']) ? "&category_id=" . $_GET['category_id'] : "") . "'>$i</a></li>";
                }
                echo "</ul>";
            } else {
                echo "등록된 기기가 없습니다.";
            }

            // 데이터베이스 연결 닫기
            $conn->close();


            
            ?>
        </div>
    </div>

    <!-- 기기 상세 정보를 보여주는 페이지로 이동하는 스크립트 -->
    <script>
        document.querySelectorAll('.device-link').forEach(function(element) {
            element.addEventListener('click', function() {
                var deviceId = this.dataset.deviceId;
                window.location.href = 'device_list_detail.php?d_id=' + deviceId;
            });
        });
    </script>
    
</body>
</html>
