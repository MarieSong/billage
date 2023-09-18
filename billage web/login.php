<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    <style>
        /* 전체 화면을 채우는 스타일 */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-image: url('image/background.jpg'); /* 배경 이미지 설정 */
            background-size: cover; /* 화면에 꽉 차도록 배경 이미지 크기 조절 */
            background-attachment: fixed; /* 배경 이미지를 고정시킵니다. */
        }

        /* 화면 중앙에 로그인 폼 배치 */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* 폼 스타일 */
        form {
            text-align: center;
            padding: 20px;
            background-color: rgba(242, 242, 242, 0.8);
            width: 400px; /* 폼의 너비 조절 */
            border-radius: 10px; /* 폼의 테두리 모서리 둥글게 */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* 그림자 효과 추가 */
        }

        /* ID와 PW 입력창 스타일 */
        input[type="text"],
        input[type="password"] {
            width: 70%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* ID와 PW 라벨의 글씨체를 굵게 설정 */
        label {
            font-weight: bold;
        }


        /* 로그인 버튼 스타일 */
        input[type="submit"] {
            width: 70%;
            padding: 10px;
            box-sizing: border-box;
            border: none;
            background-color: #2583ff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }



        /* 이미지 크기 지정 */
        body img {
            width: 100%; /* 이미지의 가로 너비를 폼에 맞게 조절 */
            height: auto; /* 이미지의 높이를 가로 비율에 맞게 자동 조절 */
        }

        


    </style>
</head>
<body>
    <form action="login_process.php" method="post">
        <img src="image/billage_login.png" alt="Billage Login"> <!-- 이미지 삽입 -->
        <br><br>
        <label for="u_id">I D :</label>
        <input type="text" id="u_id" name="u_id" required><br><br>
        
        <label for="u_pwd">PW :</label>
        <input type="password" id="u_pwd" name="u_pwd" required><br><br>
        
        <input type="submit" value="Login">
    </form>
</body>
</html>
