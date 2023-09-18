<?php
session_start();

// 데이터베이스 불러오기
require_once("db_connect.php");

// 세션에서 관리자 ID 가져오기
$admin_id = $_SESSION['u_id'];

// 현재 날짜 및 예약 순서 가져오기s
$today = date("ymd");
$sql = "SELECT COUNT(*) AS reservation_count FROM Rental WHERE rt_book = CURDATE()";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $reservation_count = str_pad($row['reservation_count'] + 1, 3, '0', STR_PAD_LEFT);
} else {
    $reservation_count = "001";
}

// 예약 ID 생성
$reservation_id = "rt" . $admin_id . "-" . $today . "-" . $reservation_count;

// 폼에서 받아온 값들
$reserver_id = $_POST['reserver_id'];
$device_id = $_POST['device_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

// 데이터베이스에 삽입
$sql = "INSERT INTO Rental (rt_id, d_id, u_id, rt_book, rt_start, rt_deadline, rt_return, rt_state)
        VALUES ('$reservation_id', '$device_id', $reserver_id, CURDATE(), '$start_date', '$end_date', NULL, 0)";

if ($conn->query($sql) === TRUE) {
    // 등록 성공 시 팝업창 띄우고 reserve_new.php로 되돌아가기
    echo "<script>alert('예약이 등록되었습니다.');</script>";
    echo "<script>window.location.href='reserve_new.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// 데이터베이스 연결 종료
$conn->close();
?>
