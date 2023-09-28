<!DOCTYPE html>
<html>
<head>
    <title>비밀번호 변경</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="text-center">비밀번호 변경</h2>
        <form id="passwordChangeForm">
            <div class="form-group">
                <label for="currentPassword">현재 비밀번호</label>
                <input type="password" class="form-control" id="currentPassword" required>
            </div>
            <div class="form-group">
                <label for="newPassword">새로운 비밀번호</label>
                <input type="password" class="form-control" id="newPassword" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">새로운 비밀번호 확인</label>
                <input type="password" class="form-control" id="confirmPassword" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="changePassword()">변경하기</button>
        </form>
    </div>

    <br>
    <div id="error-message" style="color: red;"></div>

    <script>
        function changePassword() {
            var currentPassword = document.getElementById('currentPassword').value;
            var newPassword = document.getElementById('newPassword').value;
            var confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                alert('새 비밀번호와 확인 값이 일치하지 않습니다. 다시 입력해주세요.');
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'admin_info_pwchange_dataprocess.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
                    if (xhr.responseText === "success") {
                        alert('비밀번호 변경이 완료되었습니다.');
                        window.close();
                    } else {
                        document.getElementById('error-message').innerText = xhr.responseText;
                    }
                }
            }
            xhr.send('currentPassword=' + encodeURIComponent(currentPassword) + '&newPassword=' + encodeURIComponent(newPassword));
        }
    </script>
</body>
</html>
