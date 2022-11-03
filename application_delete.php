<?
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);

mysqli_query($conn, "set autocommit = 0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "start transaction");

$app_no = $_GET['app_no'];

$pid_ret = mysqli_query($conn, "select app_no from application where app_no = $app_no");
if(!$pid_ret) {
	mysqli_query($conn, "rollback");
	msg('Query Error : '.mysqli_error($conn));
	return;
}

if(mysqli_fetch_array($pid_ret)){
	$ret = mysqli_query($conn, "delete from application where app_no = $app_no");

	if(!$ret)
	{
		mysqli_query($conn, "rollback");
		msg('Query Error : '.mysqli_error($conn));
		return;
	}
	else
	{
		mysqli_query($conn, "commit");
	    s_msg ('성공적으로 삭제 되었습니다');
	    echo "<meta http-equiv='refresh' content='0;url=index.php'>";
	}	
}
else{
	s_msg ('삭제할 수 없습니다.');
    echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}

?>

