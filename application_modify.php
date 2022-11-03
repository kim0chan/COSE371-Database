<?
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
$app_no = $_POST['app_no'];

$result = mysqli_query($conn, "update application set customer_name = '$customer_name', customer_phone = '$customer_phone', allocation_date = '$allocation_date', purpose = '$purpose', vehicle_type = '$vehicle_type', weight_or_headcount = '$weight_or_headcount', departure_time = '$departure_time', departure_point = '$departure_point', arrival_time = '$arrival_time', arrival_point = '$arrival_point', app_password = '$app_password' where app_no = '$app_no'");
if(!$result)
{
	mysqli_query($conn, "rollback");
    msg('Query Error : '.mysqli_error($conn));
    return;
}
else
{
	mysqli_query($conn, "commit");
    s_msg ('성공적으로 수정되었습니다');
    echo "<script>location.replace('application_view.php?app_no='+'$app_no');</script>";
    return;
}

?>

