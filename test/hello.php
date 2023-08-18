<!DOCTYPE html>
<html>
<head>
    <title>Hello Page</title>
</head>
<body>
    <h1>Hello Page</h1>
    <button onclick="goBack()">Go Back</button>
    <button onclick="openDataCon()">Open datacon.php</button>
    <button onclick="openCategory()">Open category.php</button>
    <button onclick="openRental()">Open rental.php</button>

    <script>
        function goBack() {
            // 뒤로가기 버튼 클릭 시 이전 페이지로 돌아감
            window.history.back();
        }

        function openDataCon() {
            // datacon.php 파일을 열기 위해 window.location.href 사용
            window.location.href = 'datacon.php';
        }

        function openCategory() {
            // category.php 파일을 열기 위해 window.location.href 사용
            window.location.href = 'category.php';
        }

        function openRental() {
            // rental.php 파일을 열기 위해 window.location.href 사용
            window.location.href = 'rental.php';
        }
    </script>
</body>
</html>
