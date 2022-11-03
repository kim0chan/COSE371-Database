﻿<?
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);

mysqli_query($conn, "set autocommit = 0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "start transaction");
	
$cert_no = $_GET['cert_no'];

$ret1 = mysqli_query($conn, "update certificate set remark = '취소' where cert_no = '$cert_no'");
$ret2 = mysqli_query($conn, "update application set app_status = '취소' where cert_no = '$cert_no'");

if(!$ret1)
{
	mysqli_query($conn, "rollback");
    msg('Query Error : '.mysqli_error($conn));
    return;
}
else if(!$ret2) {
	mysqli_query($conn, "rollback");
    msg('Query Error : '.mysqli_error($conn));
    return;
}
else
{
	mysqli_query($conn, "commit");
    s_msg ('배차가 취소되었습니다.');
    echo "<script>location.replace('certificate_list.php');</script>";
    return;
}

?>

