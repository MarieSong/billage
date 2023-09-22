<?php
// 데이터베이스 불러오기
require_once("db_connect.php");

// 폼에서 전송된 데이터 가져오기
$device_name = $_POST['device_name'];
$device_model = $_POST['device_model'];
$device_category = $_POST['device_category'];
$device_storage = $_POST['device_storage'];
$device_ram = $_POST['device_ram'];
$device_cpu = $_POST['device_cpu'];
$device_size = $_POST['device_size'];
$device_weight = $_POST['device_weight'];
$device_os = $_POST['device_os'];
$device_manufacturer = $_POST['device_manufacturer'];
$device_id = $_POST['device_id'];

// device_info : 하나의 문자열로 합치기
$device_info = "저장공간: $device_storage, RAM: $device_ram, CPU: $device_cpu, 크기(인치): $device_size, 무게: $device_weight, 운영체제: $device_os, 제조사: $device_manufacturer";


// d_id 생성 로직
// 현재까지 가장 큰 번호를 가져오는 쿼리
/*$sql_max_id = "SELECT MAX(SUBSTRING_INDEX(d_id, '-', -1)) AS max_id FROM Device";
$result_max_id = $conn->query($sql_max_id);
if ($result_max_id->num_rows > 0) {
    $row_max_id = $result_max_id->fetch_assoc();
    $max_id = $row_max_id['max_id'];
    $next_id = $max_id + 1;
    $admin_id = '12345';
    $d_id = 'd' . $admin_id . '-' . str_pad($next_id, 3, '0', STR_PAD_LEFT);
} else {
    // 만약 데이터베이스에 아무런 레코드가 없다면 초기값으로 설정
    $d_id = 'd12345-001';
}*/

//블록체인 실행

// 데이터베이스에 정보 저장
$sql = "INSERT INTO Device (d_id, d_name, d_model, d_info, d_state, u_id, c_id, d_token) 
        VALUES ('$device_id', '$device_name', '$device_model', '$device_info', 0, 12345, '$device_category', 8)";


if ($conn->query($sql) === TRUE) {
    // 성공적으로 저장된 경우

    // JavaScript 코드를 사용하여 팝업창 띄우기
    echo "<script>
            setTimeout(function() {
                alert('기기 등록을 완료했습니다.');
                window.location.href = 'device_add.php';
            }, 100); // 100 밀리초(0.1초) 후에 실행
          </script>";
    
} else {
    echo "오류: " . $sql . "<br>" . $conn->error;
}

// 데이터베이스 연결 종료
$conn->close();
?>
