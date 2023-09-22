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
    </style>
</head>
<body>
<div class="container">

        <!-- 상단 메뉴 -->
        <?php
        // top.php 파일을 포함
        include('top.php');
        ?>

        <!-- 기기 정보 불러오기 -->
        <div class="text-center mb-4">
            <?php
            require_once("db_connect.php");

            if(isset($_GET['d_id'])) {
                $device_id = $_GET['d_id'];

                $sql_device = "SELECT Device.d_id, d_name, d_model, d_info, d_state, Category.c_name, d_token
                                FROM Device, Category
                                WHERE Device.c_id = Category.c_id
                                AND Device.d_id = '$device_id'";
                $result_device = $conn->query($sql_device);

                if ($result_device->num_rows > 0) {
                    $row_device = $result_device->fetch_assoc();
                    echo "<h3>기기 정보</h3>";
                    echo "<div class='device-info'>";
                    echo "<div class='row'>";
                    echo "<div class='col-sm-6'><p><strong>기기 ID :</strong> " . $row_device['d_id'] . "</p></div>";
                    echo "<div class='col-sm-6'><p><strong>기기 이름 :</strong> " . $row_device['d_name'] . "</p></div>";
                    echo "<div class='col-sm-6'><p><strong>모델명 :</strong> " . $row_device['d_model'] . "</p></div>";
                    echo "<div class='col-sm-6'><p><strong>세부 정보 :</strong> " . $row_device['d_info'] . "</p></div>";
                    echo "<div class='col-sm-6'><p><strong>기기 상태 :</strong> " . ($row_device['d_state'] == 0 ? '대여 가능' : '수리중') . "</p></div>";
                    echo "<div class='col-sm-6'><p><strong>카테고리 :</strong> " . $row_device['c_name'] . "</p></div>";
                    echo "<div class='col-sm-6'><p><strong>토큰 ID :</strong> " . $row_device['d_token'] . "</p></div>";
                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "해당 기기를 찾을 수 없습니다.";
                }

                // 렌탈 정보 불러오기
                $sql_rental = "SELECT rt_id, u_id, rt_book, rt_start, rt_deadline, rt_return, rt_state
                                FROM Rental
                                WHERE d_id = '$device_id'";
                $result_rental = $conn->query($sql_rental);

                if ($result_rental->num_rows > 0) {
                    echo "<h3>렌탈 정보</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>렌탈 ID</th><th>대여자</th><th>예약일</th><th>수령일</th><th>반납 예정일</th><th>반납 현황</th><th>렌탈 상태</th></tr>";

                    while($row_rental = $result_rental->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row_rental['rt_id'] . "</td>";
                        echo "<td>" . $row_rental['u_id'] . "</td>";
                        echo "<td>" . $row_rental['rt_book'] . "</td>";
                        echo "<td>" . $row_rental['rt_start'] . "</td>";
                        echo "<td>" . $row_rental['rt_deadline'] . "</td>";
                        echo "<td>" . ($row_rental['rt_return'] ? '반납 완료' : '미반납') . "</td>";
                        echo "<td>";
                        switch ($row_rental['rt_state']) {
                            case 0:
                                echo '예약 완료';
                                break;
                            case 1:
                                echo '사용중';
                                break;
                            case 2:
                                echo '예약 취소';
                                break;
                            case 3:
                                echo '반납 완료';
                                break;
                            case 4:
                                echo '연체';
                                break;
                            default:
                                echo '알 수 없음';
                                break;
                        }
                        echo "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "해당 기기의 렌탈 정보가 없습니다.";
                }

                $conn->close();
            } else {
                echo "기기 ID를 찾을 수 없습니다.";
            }
            ?>
        </div>

        <!-- 뒤로가기 버튼 -->
        <div class="text-center">
            <a href="javascript:history.go(-1);" class="btn btn-secondary">뒤로가기</a>
        </div>

    </div>

    <!-- 하단 메뉴 -->
    <?php
        // bottom.php 파일을 포함
        include('bottom.php');
    ?>
    
</body>
</html>
