<!DOCTYPE html>
<html>
<head>
    <title>Billage Administrator Page</title>
    <!-- 부트스트랩 CSS 추가 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        /* 상단 메뉴 스타일 */
        .navbar {
            width: 100%;
            background-color: #f2f2f2;
            color: #333;
        }

        .navbar ul.navbar-nav {
            display: flex;
            justify-content: space-around;
        }

        .navbar ul.navbar-nav li.nav-item {
            list-style-type: none;
        }

        .navbar a.nav-link {
            color: #333;
        }

        .submenu {
            background-color: #f2f2f2;
            display: none;
            position: absolute;
        }

        .submenu li {
            padding: 10px;
        }

        .submenu a {
            color: #333;
            text-decoration: none;
        }

        .navbar li:hover .submenu {
            display: block;
        }
    </style>


    
</head>
<body>
    <div class="container">

        <!-- 상단 메뉴 -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">예약현황</a>
                    <ul class="submenu">
                        <li><a href="reserve_today.php">금일수령예정목록</a></li>
                        <li><a href="reserve_all.php">예약전체목록</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">반납현황</a>
                    <ul class="submenu">
                        <li><a href="return_today.php">금일반납예정목록</a></li>
                        <li><a href="return_all.php">전체반납예정목록</a></li>
                        <li><a href="return_overdue.php">연체현황</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">기록조회</a>
                    <ul class="submenu">
                        <li><a href="history_rental.php">대여기록조회</a></li>
                        <li><a href="history_user.php">사용자조회</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">기기관리</a>
                    <ul class="submenu">
                        <li><a href="device_list.php">기기검색</a></li>
                        <li><a href="device_add.php">기기등록</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">관리자정보</a>
                    <ul class="submenu">
                        <li><a href="admin_info.php">관리자정보</a></li>
                        <li><a href="admin_set.php">환경설정</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

    </div>
</body>
</html>