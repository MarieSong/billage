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
                    echo "<div class='col-sm-6'><p><strong>기기 상태 :</strong> " . ($row_device['d_state'] == 0 ? '대여 가능' : '수리중') . "</p></div>";
                    echo "<div class='col-sm-6'><p><strong>카테고리 :</strong> " . $row_device['c_name'] . "</p></div>";
                    echo "<div class='col-sm-6' id='tokenId'><p><strong>토큰 ID :</strong> " . $row_device['d_token'] . "</p></div>";
                    echo "<div class='col-sm-12'><p><strong>세부 정보 :</strong> " . $row_device['d_info'] . "</p></div>";
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

                // 렌탈 정보를 배열로 저장할 변수 초기화
                $dbRentalHistory = [];

                echo "<br>";
                echo "<h3>렌탈 정보</h3>";
                if ($result_rental->num_rows > 0) {
                    
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

                        $dbRentalHistory[] = $row_rental['rt_id'];
                        $dbRentalHistory[] = $row_rental['u_id'];
                        $dbRentalHistory[] = $row_rental['rt_start'];
                        $dbRentalHistory[] = $row_rental['rt_deadline'];
                        $dbRentalHistory[] = $row_rental['rt_id'];
                        $dbRentalHistory[] = $row_rental['rt_return'];
                    }

                    echo "</table>";
                } else {
                    echo "<br>";
                    echo "<br>";
                    echo "해당 기기의 렌탈 정보가 없습니다.";
                    echo "<br>";
                }

                //수리 정보 불러오기
                $sql_repair = "SELECT rp_id, rp_discover, rp_start, rp_return, rp_info
                FROM Repair
                WHERE d_id = '$device_id'";
                $result_repair = $conn->query($sql_repair);

                // 수리 정보를 배열로 저장할 변수 초기화
                $dbRepairHistory = [];


                echo "<br>";
                echo "<br>";
                echo "<h3>수리 정보</h3>";
                if ($result_repair->num_rows > 0) {

                echo "<table border='1'>";
                echo "<tr><th>수리 ID</th><th>고장 발견일</th><th>수리 시작일</th><th>수리 종료일</th><th>수리 정보</th></tr>";

                while($row_repair = $result_repair->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row_repair['rp_id'] . "</td>";
                    echo "<td>" . $row_repair['rp_discover'] . "</td>";
                    echo "<td>" . $row_repair['rp_start'] . "</td>";
                
                    // Check if rp_return is null
                    echo "<td>";
                    if (is_null($row_repair['rp_return'])) {
                        // Display '날짜 등록' button
                        echo "<form action='device_repair_return.php' method='post'>";
                        echo "<input type='hidden' name='rp_id' value='" . $row_repair['rp_id'] . "'>";
                        echo "<input type='submit' class='btn btn-primary' value='날짜 등록'>";
                        echo "</form>";
                    } else {
                        // Display rp_return date
                        echo $row_repair['rp_return'];
                    }
                    echo "</td>";
                
                    echo "<td>" . $row_repair['rp_info'] . "</td>";
                    echo "</tr>";

                    $dbRepairHistory[] =  $row_repair['rp_id'];
                    $dbRepairHistory[] =  $row_repair['rp_discover'];
                    $dbRepairHistory[] =  $row_repair['rp_start'];
                    $dbRepairHistory[] =  $row_repair['rp_info'];
                    $dbRepairHistory[] =  $row_repair['rp_id'];
                    $dbRepairHistory[] =  $row_repair['rp_return'];
                    
                    
                }

                echo "</table>";
                } else {
                echo "<br>";
                echo "<br>";
                echo "해당 기기의 수리 정보가 없습니다.";
                echo "<br>";
                }

                $conn->close();
                } else {
                    echo "기기 ID를 찾을 수 없습니다.";
                }

                
            ?>
        </div>

        <br>
        <br>

        <!-- gethistory.js값 확인하기 위한 hidden값 -->
        <!--<p><strong>테스트 ID :</strong> <span id="updatedData">0</span></p>  -->
        <input type="hidden" id="updatedData" name="updatedData" value="0">

        <!-- 뒤로가기 버튼과 기록검증 버튼 -->
        <div class="text-center mt-4">
            <a href="device_list.php" class="btn btn-secondary mr-2">목록보기</a>
            <button class="btn btn-primary mr-2" id='getHistory'>불러오기</button>
            <button class="btn btn-success mr-2" id='checkHistory' disabled>동기화</button>
        </div>


    </div>

    <script src="js/web3.min.js"></script>
    <script src="js/gethistory.js"></script>

    <script>
        const dbRentalHistory = <?php echo json_encode($dbRentalHistory); ?>;
        const dbRepairHistory = <?php echo json_encode($dbRepairHistory); ?>;
        

        const checkHistoryButton = document.getElementById('checkHistory');
        checkHistoryButton.addEventListener('click', onHiddenValueChanged);

        // hidden 값이 변경되었을 때 실행될 함수
        function onHiddenValueChanged() {
            const updatedDataInput = document.getElementById('updatedData');
            const updatedDataString = updatedDataInput.value;
            const updatedData = JSON.parse(updatedDataString);

            const rentalHistory = updatedData.rentalHistory;
            const repairHistory = updatedData.repairHistory;

            // rentalHistory와 dbRentalHistory 비교
            for (let i = 0; i < rentalHistory.length; i++) {
                if (rentalHistory[i] !== dbRentalHistory[i]) {
                    console.log(`rentalHistory와 dbRentalHistory의 ${i+1}번째 값이 다릅니다.`);
                    console.log(`rentalHistory: ${rentalHistory[i]}, dbRentalHistory: ${dbRentalHistory[i]}`);
                    const rentalID = i-i%6;
                    console.log(`rt_id :  ${rentalHistory[rentalID]}`);

                    let editPart;
                    switch (i % 6) {
                        case 0:
                            editPart = 'rt_id';
                            break;
                        case 1:
                            editPart = 'u_id';
                            break;
                        case 2:
                            editPart = 'rt_start';
                            break;
                        case 3:
                            editPart = 'rt_deadline';
                            break;
                        case 4:
                            editPart = 'rt_id';
                            break;
                        case 5:
                            editPart = 'rt_return';
                            break;
                        default:
                            editPart = '';
                    }


                    // 서버로 데이터 전송
                    fetch('device_list_detail_sync.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ table : 'Rental', id_name : 'rt_id', id: rentalHistory[rentalID], editValue: editPart, newValue: rentalHistory[i] })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('데이터베이스 업데이트 결과:', data);
                    })
                    .catch(error => console.error('데이터베이스 업데이트 중 오류 발생:', error));
                }
            }

            // repairHistory와 dbRepairHistory 비교
            for (let i = 0; i < repairHistory.length; i++) {
                if (repairHistory[i] !== dbRepairHistory[i]) {
                    console.log(`repairHistory와 dbRepairHistory의 ${i+1}번째 값이 다릅니다.`);
                    console.log(`repairHistory: ${repairHistory[i]}, dbRepairHistory: ${dbRepairHistory[i]}`);

                    const repairID = i-i%6;
                    console.log(`rp_id :  ${repairHistory[repairID]}`);

                    let editPart2;
                    switch (i % 6) {
                        case 0:
                            editPart2 = 'rp_id';
                            break;
                        case 1:
                            editPart2 = 'rp_discover';
                            break;
                        case 2:
                            editPart2 = 'rp_start';
                            break;
                        case 3:
                            editPart2 = 'rp_info';
                            break;
                        case 4:
                            editPart2 = 'rp_id';
                            break;
                        case 5:
                            editPart2 = 'rp_return';
                            break;
                        default:
                            editPart2 = '';
                    }


                    // 서버로 데이터 전송
                    fetch('device_list_detail_sync.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ table : 'Repair', id_name : 'rp_id', id: repairHistory[repairID], editValue: editPart2, newValue: repairHistory[i] })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('데이터베이스 업데이트 결과:', data);
                    })
                    .catch(error => console.error('데이터베이스 업데이트 중 오류 발생:', error));
                }
            }

            // 모든 값이 일치하면 "success" 출력
            console.log("compare complete");

            // "동기화 완료" 팝업 표시
            alert("동기화 완료");

            // 화면 새로고침
            location.reload();
        }


        
    </script>

    <!-- 하단 메뉴 -->
    <?php
        // bottom.php 파일을 포함
        include('bottom.php');
    ?>
    
</body>
</html>
