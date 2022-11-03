﻿<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수
?>

<body>
	<h3 align='center'>관리자 메뉴입니다.</h3>
	<br>
	<h4 align='center'><a href='application_list_eval.php'>배차 신청 승인</href></h4>
	<h6><br></h6>
	<h4 align='center'><a href='certificate_list.php'>배차증 조회 및 취소</href></h4>
	<h6><br></h6>
	<h4 align='center'><a href='driver_list.php'>근무자 조회</href></h4>
	<h6><br></h6>
	<h4 align='center'><a href='vehicle_list.php'>차량 조회</href></h4>
	<h6><br></h6>
	<h4 align='center'><a href='application_list.php'>배차 신청서 목록</href></h4>
	<br>
</body>


<? include("footer.php") ?>