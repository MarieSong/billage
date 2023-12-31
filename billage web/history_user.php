<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/pagination_form.css">
    <style>
        /* 표 전체에 적용되는 스타일 */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* 표 헤더에 적용되는 스타일 */
        th {
            background-color: #f2f2f2;
        }

        /* 표 셀에 적용되는 스타일 */
        td, th {
            padding: 10px;
            text-align: center;
        }

        /* 번호 셀 너비를 고정하기 위한 스타일 */
        td:first-child {
            width: 50px; /* 번호 셀의 너비를 조절합니다. */
        }

    </style>
    <script>
        function openRentalHistory(userId) {
            window.open('history_user_detail.php?u_id=' + userId, '_blank', 'width=1200,height=400');
        }

        function searchUser() {
            var searchOption = document.getElementById('searchOption').value;
            var searchText = document.getElementById('searchText').value;
            var url = "history_user.php?option=" + searchOption + "&text=" + searchText;
            window.location.href = url;
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

        <!-- 사용자 정보 검색 -->
        <div class="text-center">
            <p>&lt;사용자조회&gt;</p>
        </div>
        <div class="text-center" style="margin-bottom: 20px;">
            <select id="searchOption" class="form-control" style="display: inline-block; width: 150px; margin-right: 10px;">
                <option value="u_id">사용자 ID</option>
                <option value="u_name">이름</option>
            </select>
            <input type="text" id="searchText" class="form-control" style="display: inline-block; width: 200px; margin-right: 10px;" placeholder="검색어를 입력하세요">
            <button onclick="searchUser()" class="btn btn-primary">검색</button>
        </div>

        <div class="text-center">

            <?php
        
            // 데이터베이스 불러오기
            require_once("db_connect.php");

            // 페이지당 표시할 항목 수
            $items_per_page = 10;

            // 현재 페이지
            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

            // Rental 테이블 정보 가져오기 (검색)
            $offset = ($current_page - 1) * $items_per_page;

            // 검색 옵션 및 텍스트
            $searchOption = isset($_GET['option']) ? $_GET['option'] : '';
            $searchText = isset($_GET['text']) ? $_GET['text'] : '';

            // SQL 쿼리에 사용될 조건
            $condition = '';
            if ($searchOption && $searchText) {
                $condition = "AND $searchOption LIKE '%$searchText%'";
            }

            // user 테이블 정보 가져오기 (전체 사용자)
            $sql = "SELECT * FROM user 
                    WHERE u_role=1 $condition
                    LIMIT $offset, $items_per_page";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>번호</th><th>사용자 ID</th><th>이름</th><th>연락처</th><th>E-mail</th></tr>";
 
                // 각 행 정보 출력
                $rowNumber = 1 + ($current_page - 1) * $items_per_page; // 처음 숫자 계산
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $rowNumber . "</td>"; //번호
                    echo "<td>" . $row['u_id'] . "</td>"; //사용자ID
                    // Add a link to open rental history in a new window
                    echo "<td><a href='#' onclick='openRentalHistory(" . $row['u_id'] . "); return false;'>" . $row['u_name'] . "</a></td>";
                    echo "<td>" . $row['u_phone'] . "</td>"; //연락처
                    echo "<td>" . $row['u_email'] . "</td>"; //e-mail
                    echo "</tr>";

                    // 다음 행을 위해 숫자 증가
                    $rowNumber++;
                }

                echo "</table>";

                // 페이지네이션 출력
                $result_total_items = $conn->query("SELECT COUNT(*) as total FROM user WHERE u_role=1 $condition");
                $row_total_items = $result_total_items->fetch_assoc();
                $total_items = $row_total_items['total'];
                $total_pages = ceil($total_items / $items_per_page);

                echo "<ul class='pagination'>";
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<li class='" . ($current_page == $i ? "active" : "") . "'><a href='?page=$i'>$i</a></li>";
                }
                echo "</ul>";
            } else {
                echo "No users found.";
            }

            // 데이터베이스 연결 닫기
            $conn->close();
            ?>
        </div>
    </div>

    <!-- 하단 메뉴 -->
    <?php
        // bottom.php 파일을 포함
        include('bottom.php');
    ?>
</body>
</html>
