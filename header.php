﻿<!DOCTYPE html>
<html lang='ko'>
<head>
    <title>GC LOGISTICS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<form action="index.php" method="post">
    <div class='navbar fixed'>
        <div class='container'>
            <a class='pull-left title' href="index.php">GC LOGISTICS</a>
            <ul class='pull-right'>

				<li><a href='application_form.php'>배차 신청</a></li>
                <li><a href='application_search.php'>배차 조회</a></li>
                <li onclick='javascript:authorityConfirm()'><a href='#'>관리자 메뉴</a></li>
                <li><a href='logistics_db.php'>ABOUT</a></li>
            </ul>
        </div>
    <script>
        function authorityConfirm() {
            if (confirm("배차반 직원이십니까?") == true){
                window.location = "menu_admin.php";
            }
            else{
                alert("권한이 없습니다.");
                return;
            }
        }
    </script>
    </div>
</form>