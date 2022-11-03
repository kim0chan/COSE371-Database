﻿<?
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);

mysqli_query($conn, "set autocommit = 0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "start transaction");

$customer_name = $_POST['customer_name'];
$customer_phone = $_POST['customer_phone'];
$allocation_date = $_POST['allocation_date'];
$purpose = $_POST['purpose'];
$vehicle_type = $_POST['vehicle_type'];
$weight_or_headcount = $_POST['weight_or_headcount'];
$departure_time = $_POST['departure_time'];
$departure_point = $_POST['departure_point'];
$arrival_time = $_POST['arrival_time'];
$arrival_point = $_POST['arrival_point'];
$app_password = $_POST['app_password'];
$app_no = rand(0000000000, 9999999999);

$result = mysqli_query($conn, "insert into application (app_no, app_date, app_password, app_status, customer_name, customer_phone, allocation_date, departure_time, arrival_time, departure_point, arrival_point, purpose, vehicle_type, weight_or_headcount, cert_no) values('$app_no', NOW(), '$app_password', '대기', '$customer_name', '$customer_phone', '$allocation_date', '$departure_time', '$arrival_time', '$departure_point', '$arrival_point', '$purpose', '$vehicle_type', '$weight_or_headcount', NULL)");
if(!$result)
{
	mysqli_query($conn, "rollback");
    msg('Query Error : '.mysqli_error($conn));
    return;
}
else
{
	mysqli_query($conn, "commit");
    s_msg ('성공적으로 입력되었습니다.\n신청서 번호는 '.$app_no.'입니다.');
    echo "<script>location.replace('index.php');</script>";
    return;
}

?>

