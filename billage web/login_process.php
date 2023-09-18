<?php
// 데이터베이스 불러오기
require_once("db_connect.php");

// POST로부터 입력 받은 값 가져오기
$u_id = $_POST['u_id'];
$u_pwd = $_POST['u_pwd'];

// SQL 쿼리 작성
$sql = "SELECT * FROM user WHERE u_id='$u_id' AND u_pwd='$u_pwd' AND u_role=0";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // 로그인 성공 시 세션 시작 및 u_id 저장
    session_start();
    $_SESSION['u_id'] = $u_id;

    // index.php로 리다이렉트
    header("Location: index.php");
} else {
    // 로그인 실패 시 경고창 출력
    echo "<script>alert('일치하는 회원 정보가 없습니다.');</script>";
    header("Refresh:0; url=login.php");
}

// 데이터베이스 연결 종료
$conn->close();
?>
