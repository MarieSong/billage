<?php
session_start();

// 데이터베이스 불러오기
require_once("db_connect.php");

// 세션에서 사용자 ID 가져오기
$u_id = $_SESSION['u_id'];
$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];

$sql = "SELECT u_pwd FROM user WHERE u_id = $u_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $storedPassword = $row['u_pwd'];

    if ($currentPassword === $storedPassword) {
        $updateSql = "UPDATE user SET u_pwd='$newPassword' WHERE u_id=$u_id";
        if ($conn->query($updateSql) === TRUE) {
            echo "success";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "현재 비밀번호가 올바르게 입력되지 않았습니다.";
    }
} else {
    echo "User not found";
}

$conn->close();
?>
