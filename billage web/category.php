<?php
// 모든 하위 카테고리를 가져오는 함수 정의
function getAllSubCategories($tree, $parent_id, &$subCategories) {
    if (isset($tree[$parent_id])) {
        foreach ($tree[$parent_id] as $item) {
            $subCategories[] = $item['c_id'];
            getAllSubCategories($tree, $item['c_id'], $subCategories);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Category Information</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1>Category Information</h1>

    <?php
    // MySQL 데이터베이스 연결 정보
    $db_host = 'localhost';      // 호스트 주소
    $db_username = 'billage';      // 데이터베이스 사용자 이름
    $db_password = 'teambym2023!';      // 데이터베이스 비밀번호
    $db_name = 'billage';     // 데이터베이스 이름

    // 데이터베이스 연결
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // 연결 확인
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Category 테이블 정보 가져오기
    $sql = "SELECT * FROM Category ORDER BY c_top_id, c_id";
    $result = $conn->query($sql);

    // 트리 구조 생성을 위한 배열 초기화
    $tree = array();

    // 각 행 정보를 배열에 추가
    while ($row = $result->fetch_assoc()) {
        if ($row['c_top_id'] === NULL) {
            // c_top_id가 NULL인 경우를 최상위 카테고리로 간주하여 별도 배열에 추가
            $tree[NULL][] = $row;
        } else {
            // 그 외의 경우는 c_top_id를 기준으로 하위 카테고리로 추가
            $tree[$row['c_top_id']][] = $row;
        }
    }

    // 재귀적으로 트리 구조를 생성하고 표현하는 함수
    function buildTree($tree, $parent_id = NULL, $level = 0) {
        if (isset($tree[$parent_id])) {
            foreach ($tree[$parent_id] as $item) {
                echo str_repeat('&nbsp;&nbsp;&nbsp;', $level); // 간격 조절 (예: 3칸)
                echo "<a href='?category_id=" . $item['c_id'] . "'>" . $item['c_name'] . "</a><br>";
                buildTree($tree, $item['c_id'], $level + 1);
            }
        }
    }

    // 최상위 카테고리부터 트리 구조 시작
    buildTree($tree);


    // 카테고리 체크 버튼을 클릭했을 때
    if (isset($_POST['category_check'])) {
        echo "<h2>Subcategories in Selected Category</h2>";

        if (isset($_GET['category_id'])) {
            $selected_category_id = $_GET['category_id'];

            $subCategories = array();
            getAllSubCategories($tree, $selected_category_id, $subCategories);

            if (count($subCategories) > 0) {
                echo "<ul>";
                foreach ($subCategories as $subCategory) {
                    echo "<li>$subCategory</li>";
                }
                echo "</ul>";
            } else {
                echo "No subcategories found in the selected category.";
            }
        }
    }


    if (isset($_GET['category_id'])) {
        $category_id = $_GET['category_id'];
        
        // c_bottom 값을 가져와서 확인
        $sql_category = "SELECT c_bottom FROM Category WHERE c_id = '$category_id'";
        $result_category = $conn->query($sql_category);
    
        if ($result_category->num_rows > 0) {
            $row_category = $result_category->fetch_assoc();
            $c_bottom = $row_category['c_bottom'];
    
            // c_bottom 값이 0이면 하위 카테고리의 Device 정보를 가져옴
            if ($c_bottom == 0) {
                $subCategories = array();
                getAllSubCategories($tree, $category_id, $subCategories);
    
                if (!empty($subCategories)) {
                    $subCategoryList = "'" . implode("', '", $subCategories) . "'";
    
                    $sql = "SELECT d_id, d_name, d_info FROM Device WHERE c_id IN ($subCategoryList) ORDER BY d_id";
                    //echo "sql : " . $sql;
                    $result = $conn->query($sql);
    
                    if ($result->num_rows > 0) {
                        echo "<h2>Devices in Subcategories</h2>";
                        echo "<table border='1'>";
                        echo "<tr><th>Device ID</th><th>Device Name</th><th>Device Info</th></tr>";
    
                        // 각 행 정보 출력
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['d_id'] . "</td>";
                            echo "<td>" . $row['d_name'] . "</td>";
                            echo "<td>" . $row['d_info'] . "</td>";
                            echo "</tr>";
                        }
    
                        echo "</table>";
                    } else {
                        echo "No devices found in the subcategories.";
                    }
                } else {
                    echo "No subcategories found.";
                }
            } else {
                // c_bottom 값이 0이 아니면 해당 카테고리의 Device 정보를 가져옴
                $sql = "SELECT d_id, d_name, d_info FROM Device WHERE c_id = '$category_id'";
                $result = $conn->query($sql);
    
                if ($result->num_rows > 0) {
                    echo "<h2>Devices in Selected Category</h2>";
                    echo "<table border='1'>";
                    echo "<tr><th>Device ID</th><th>Device Name</th><th>Device Info</th></tr>";
    
                    // 각 행 정보 출력
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['d_id'] . "</td>";
                        echo "<td>" . $row['d_name'] . "</td>";
                        echo "<td>" . $row['d_info'] . "</td>";
                        echo "</tr>";
                    }
    
                    echo "</table>";
                } else {
                    echo "No devices found in the selected category.";
                }
            }
        } else {
            echo "Category not found.";
        }
    }

    // 데이터베이스 연결 닫기
    $conn->close();
    ?>


<!-- 카테고리 체크 버튼 -->
<form method="post">
        <button type="submit" name="category_check">Category Check</button>
    </form>        




<!-- 부트스트랩 사용한 표현 -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="list-unstyled">
                    <?php
                    // 여기에는 buildTree() 함수를 호출하지 않습니다.
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- home으로 돌아가는 버튼 -->
    <button onclick="goHome()">Go Home</button>

    <script>
        function goHome() {
            // home (index.php) 페이지로 이동
            window.location.href = "index.php";
        }
    </script>

</body>
</html>
