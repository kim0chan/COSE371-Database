<?
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);

mysqli_query($conn, "set autocommit = 0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "start transaction");

$cert_no = rand(0000000000, 9999999999);
$app_no = $_POST['app_no'];
$dispatcher_no = $_POST['dispatcher_no'];
$driver_no = $_POST['driver_no'];
$vehicle_no = $_POST['vehicle_no'];
$cost = $_POST['cost'];
$remark = $_POST['remark'];


$res = mysqli_query($conn, "insert into certificate (cert_no, cost, date, dispatcher_no, driver_no, vehicle_no, remark) values('$cert_no', '$cost', NOW(), '$dispatcher_no', '$driver_no', '$vehicle_no', '$remark')");

if(!$res)
{
	mysqli_query($conn, "rollback");
    msg('Query Error : '.mysqli_error($conn));
    return;
}
else
{
	$query = "update application set app_status='승인', cert_no = '$cert_no' where app_no = '$app_no'";
	$result = mysqli_query($conn, $query);
	if(!$result) {
		mysqli_query($conn, "rollback");
		msg('Query Error : '.mysqli_error($conn));
    	return;
	}
	else {
		s_msg ('성공적으로 승인 되었습니다.\n배차증 번호는 '.$cert_no.'입니다.');
    	echo "<script>location.replace('application_list_eval.php');</script>";
	}
}

?>

