<?
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);
$app_no = $_GET['app_no'];
//echo($app_no);

mysqli_query($conn, "set autocommit = 0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "start transaction");


$res = mysqli_query($conn, "update application set app_status = '반려' where app_no = '$app_no'");

if(!$res)
{
	mysqli_query($conn, "rollback");
    msg('Query Error : '.mysqli_error($conn));
    return;
}
else
{
	mysqli_query($conn, "commit");
    s_msg ('반려되었습니다.');
    echo "<script>location.replace('application_list_eval.php');</script>";
    return;
}

?>

