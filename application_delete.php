<?
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);

$app_no = $_GET['app_no'];

$pid_ret = mysqli_query($conn, "select app_no from application where app_no = $app_no");

if(mysqli_fetch_array($pid_ret)){
	$ret = mysqli_query($conn, "delete from application where app_no = $app_no");

	if(!$ret)
	{
	    msg('Query Error : '.mysqli_error($conn));
	}
	else
	{
	    s_msg ('성공적으로 삭제 되었습니다');
	    echo "<meta http-equiv='refresh' content='0;url=index.php'>";
	}	
}
else{
	s_msg ('삭제할 수 없습니다.');
    echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}

?>

