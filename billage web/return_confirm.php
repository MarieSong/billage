<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        .container {
            text-align: center;
        }

        .user-info-text {
            text-align: center;
        }
    </style>

    <script>
        function closeWindow() {
            window.close();
        }

        function confirmRow(rtId) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'return_update_state.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 400) {
                    alert('기기 반납 완료.');
                    // 현재 창을 새로고침
                    window.location.reload();
                }
            };

            xhr.onerror = function () {
                console.log('Error');
            };

            xhr.send('rt_id=' + rtId);
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

        <div class="user-info-text">
            <h2>반납 정보</h2>
        </div>

        <?php
        // 데이터베이스 연결
        require_once("db_connect.php");

        // 사용자 정보 가져오기
        if(isset($_GET['rt_id'])) {
            $rental_id = $_GET['rt_id'];
            $sql_rental = "SELECT d.d_name, d.d_model, d.d_token, d.d_id, rt.rt_id, rt.u_id, rt_deadline, rt.rt_state 
                            FROM Rental rt, Device d 
                            WHERE rt.d_id = d.d_id AND rt.rt_id = '$rental_id'";
            $result_rental = $conn->query($sql_rental);

            if ($result_rental->num_rows > 0) {
                $row_rental = $result_rental->fetch_assoc();
                //echo "<h2>User Information</h2>";
                echo "<div class='row'>";
                echo "<div class='col-sm-6' id='rentalId'><p><strong>대여 ID :</strong> " . $row_rental['rt_id'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>기기 ID :</strong> " . $row_rental['d_id'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>기기 이름 :</strong> " . $row_rental['d_name'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>모델명 :</strong> " . $row_rental['d_model'] . "</p></div>";
                echo "<div class='col-sm-6' id='tokenIdTransfer'><p><strong>토큰 ID :</strong> " . $row_rental['d_token'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>대여자 ID :</strong> " . $row_rental['u_id'] . "</p></div>";
                echo "<div class='col-sm-6'><p><strong>반납 예정일 :</strong> " . $row_rental['rt_deadline'] . "</p></div>";
                $todayDate = date("Y-m-d");
                echo "<div class='col-sm-6'  id='todayDate'><p><strong>오늘 날짜 :</strong> <span>$todayDate</span></p></div>";
                echo "<div class='col-sm-6'><p><strong>현재 상태 :</strong> ";

                // rt_state에 따른 상태 표시
                switch($row_rental['rt_state']) {
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
                        $overdue_days = date_diff(new DateTime($row_rental['rt_deadline']), new DateTime())->format('%a');
                        echo "연체 ({$overdue_days}일)";
                        break;
                    default:
                        echo "알 수 없음";
                        break;
                }

                echo "</p></div>";
                echo "</div>";

                // "반납확인" 버튼 추가
                //echo "<div class='text-center mt-4'>";
                //echo "<button class='btn btn-primary' onclick='confirmRow(\"" . $rental_id . "\")'>반납확인</button>";
                //echo "</div>";

                echo "<div class='text-center mt-4'>";

                
                echo "<button class='btn btn-primary' id='confirmButton' onclick='confirmRow(\"$rental_id\")'>반납확인</button>";
                

                echo "&nbsp;";
                echo "<a href=\"javascript:history.go(-1);\" class=\"btn btn-secondary\">뒤로가기</a>";
                echo "</div>";

            } else {
                echo $rental_id;
                echo "Rental not found.";
            }
        } else {
            echo "User ID not provided.";
        }

        // 데이터베이스 연결 닫기
        $conn->close();
        ?>

        <!-- 닫기 버튼 추가
        <div class="text-center mt-4">
            <button class="btn btn-secondary" onclick="closeWindow()">닫기</button> 
            <button class='btn btn-primary' onclick='confirmRow("" . $rental_id . "")'>반납확인</button> 
            <button class='btn btn-primary' id='confirmButton' onclick='confirmRow("" . $rental_id . "")'>반납확인</button>
            <a href="javascript:history.go(-1);" class="btn btn-secondary">뒤로가기</a>
        </div>  -->

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var rtDeadline = "<?php echo $row_rental['rt_deadline']; ?>";
                var rtState = "<?php echo $row_rental['rt_state']; ?>";
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0');
                var yyyy = today.getFullYear();
                today = yyyy + '-' + mm + '-' + dd;

                if (rtDeadline > today || (rtState !== '1' && rtState !== '4')) {
                    var button = document.getElementById('confirmButton');
                    button.disabled = true;
                }
            });
        </script>

    </div>

    <script src="js/web3.min.js"></script>
    <script src="js/return.js"></script>

    <!-- 하단 메뉴 -->
    <?php
        // bottom.php 파일을 포함
        include('bottom.php');
    ?>
</body>
</html>
