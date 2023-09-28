<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/pagination_form.css"> <!-- 추가된 줄 -->

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

        /* 비고 칸 스타일 */
        .note-column {
            width: 100px; /* 비고 칸의 너비를 조절합니다. */
        }

        /* 가운데 정렬 및 줄 바꿈 스타일 */
        .centered-cell {
            text-align: center;
            white-space: pre-line; /* 줄 바꿈을 가능하게 합니다. */
        }

        /* rt_state가 2인 경우에만 적용하는 스타일 */
        tr.state-2 td.centered-cell {
            background-color: #ffeeee; /* 예시로 배경색 추가 */
        }
    </style>

    <script>
        function searchRental() {
            var option = document.getElementById('searchOption').value;
            var startDate = document.getElementById('startDate').value;
            var endDate = document.getElementById('endDate').value;

            // 유효성 검사: 시작일이 종료일보다 이전인지 확인
            if (new Date(startDate) > new Date(endDate)) {
                alert("시작일은 종료일보다 이전이어야 합니다.");
                return;
            }

            var url = "history_rental.php?option=" + option + "&startDate=" + startDate + "&endDate=" + endDate;
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

        <!-- 사용자 정보 불러오기 -->
        <div class="text-center">
            <p>&lt;대여 기록 조회&gt;</p>
        </div>

        <!-- 검색 기능 추가 -->
        <div class="text-center" style="margin-bottom: 20px;">
            <select id="searchOption" class="form-control" style="display: inline-block; width: 150px; margin-right: 10px;">
                <option value="rt_book">예약일</option>
                <option value="rt_start">수령일</option>
                <option value="rt_deadline">반납 예정일</option>
            </select>
            <input type="date" id="startDate" class="form-control" style="display: inline-block; width: 150px; margin-right: 10px;" value="<?php echo isset($_GET['startDate']) ? $_GET['startDate'] : ''; ?>">
            <span>~ </span>
            <input type="date" id="endDate" class="form-control" style="display: inline-block; width: 150px; margin-right: 10px;" value="<?php echo isset($_GET['endDate']) ? $_GET['endDate'] : ''; ?>">
            <button onclick="searchRental()" class="btn btn-primary">검색</button>
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

            // 검색 옵션 및 기간
            $searchOption = isset($_GET['option']) ? $_GET['option'] : '';
            $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
            $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';

            // SQL 쿼리에 사용될 조건
            $condition = '';
            if ($searchOption && $startDate && $endDate) {
                $condition = "AND $searchOption BETWEEN '$startDate' AND '$endDate'";
            }

            $sql = "SELECT d_id, u_id, rt_book, rt_start, rt_deadline, 
                        IF(rt_state = 2, '취소', COALESCE(rt_return, '미반납')) AS rt_return,
                        IF(rt_state = 4 OR rt_return > rt_deadline, CONCAT('연체(', DATEDIFF(COALESCE(rt_return, NOW()), rt_deadline), '일)'), '') AS note 
                    FROM Rental 
                    WHERE 1 $condition
                    ORDER BY rt_start DESC
                    LIMIT $offset, $items_per_page";


            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>번호</th><th>기기ID</th><th>대여자</th><th>예약일</th><th>수령일</th><th>반납예정일</th><th>반납현황</th><th class='note-column'>비고</th></tr>";
 
                // 각 행 정보 출력
                $rowNumber = 1 + $offset; // 처음 숫자를 계산
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $rowNumber . "</td>"; // 번호
                    echo "<td>" . $row['d_id'] . "</td>"; // 기기ID
                    echo "<td>" . $row['u_id'] . "</td>"; // 대여자
                    echo "<td>" . $row['rt_book'] . "</td>"; // 예약일
                    echo "<td>" . $row['rt_start'] . "</td>"; // 수령일
                    echo "<td>" . $row['rt_deadline'] . "</td>"; // 반납예정일
                    echo "<td>" . $row['rt_return'] . "</td>"; // 반납현황
                    echo "<td>" . $row['note'] . "</td>"; // 비고
                    echo "</tr>";

                    // 다음 행을 위해 숫자 증가
                    $rowNumber++;
                }

                echo "</table>";

                // 페이지네이션 출력
                $result_total_items = $conn->query("SELECT COUNT(*) as total FROM Rental");
                $row_total_items = $result_total_items->fetch_assoc();
                $total_items = $row_total_items['total'];
                $total_pages = ceil($total_items / $items_per_page);

                echo "<ul class='pagination'>";
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<li class='" . ($current_page == $i ? "active" : "") . "'><a href='?page=$i'>$i</a></li>";
                }
                echo "</ul>";
            } else {
                echo "No results found.";
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
