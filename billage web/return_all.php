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

    <!-- 추가된 JavaScript 코드 -->
    <script>
        function openConfirmPage(rtId) {
        // 현재 창에서 페이지 이동
        window.location.href = 'return_confirm.php?rt_id=' + rtId;
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
            <p>&lt;전체 반납 예정 목록&gt;</p>
        </div>
        <div class="text-center">

            <?php
        
            // 데이터베이스 불러오기
            require_once("db_connect.php");

            // Rental 테이블 정보 가져오기 (검색)
            $sql = "SELECT d.d_name, d.d_model, d.d_id, rt.u_id, rt.rt_deadline, rt.rt_id, rt.rt_state 
                    FROM Rental rt, Device d 
                    WHERE d.d_id = rt.d_id AND rt.rt_deadline >= CURDATE() AND rt.rt_start <= CURDATE() AND rt.rt_state=1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>번호</th><th>기기이름</th><th>모델명</th><th>기기ID</th><th>대여자</th><th>반납일</th><th>수령상태</th></tr>";
 
                // 각 행 정보 출력
                $rowNumber = 1; // 처음 숫자를 1로 설정
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $rowNumber . "</td>"; // 번호
                    echo "<td>" . $row['d_name'] . "</td>"; // 기기이름
                    echo "<td>" . $row['d_model'] . "</td>"; // 모델명
                    echo "<td>" . $row['d_id'] . "</td>"; // 기기ID
                    echo "<td>" . $row['u_id'] . "</td>"; // 대여자
                    echo "<td>" . $row['rt_deadline'] . "</td>"; // 반납일
                    echo "<td>";
    
                    switch($row['rt_state']) {
                        case 0:
                            echo "예약 완료";
                            break;
                        case 1:
                            echo "수령 완료(사용중)";
                            break;
                        case 2:
                            echo "예약 취소";
                            break;
                        case 3:
                            echo "반납 완료";
                            break;
                        case 4:
                            echo "연체";
                            break;
                        default:
                            echo "알 수 없음";
                            break;
                    }
                    
                    echo "</td>"; // 수령상태

                    // '확인' 버튼 추가
                    zzecho "<td>";
                    echo "<button type='button' class='btn btn-info' onclick='openConfirmPage(\"" . $row['rt_id'] . "\")'>확인</button>";
                    echo "</td>";

                    echo "</tr>";

                    // 다음 행을 위해 숫자 증가
                    $rowNumber++;
                }

                echo "</table>";
            } else {
                echo "반납 예정 기기가 없습니다.";
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
