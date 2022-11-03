<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수
?>
<div class="container">
    <?
    $conn = dbconnect($host, $dbid, $dbpass, $dbname);
    
    mysqli_query($conn, "set autocommit = 0");
	mysqli_query($conn, "set session transaction isolation level serializable");
	mysqli_query($conn, "start transaction");
    
    $query = "select * from application";
    if (array_key_exists("search_name", $_POST)) {  // array_key_exists() : Checks if the specified key exists in the array
        $search_name = $_POST["search_name"];
        $query .= " where customer_name = '$search_name'";
        echo("<b><h4>{$search_name}</b>님의 배차신청서 목록입니다.</h4><br>");
    }
    if (array_key_exists("search_phone", $_POST)) {  // array_key_exists() : Checks if the specified key exists in the array
        $search_phone = $_POST["search_phone"];
        $query .= " and customer_phone = '$search_phone'";
    }
    if (array_key_exists("search_pw", $_POST)) {  // array_key_exists() : Checks if the specified key exists in the array
        $search_pw = $_POST["search_pw"];
        $query .= " and app_password = '$search_pw'";
    }
    $result = mysqli_query($conn, $query);
    if (!$result) {
    	 mysqli_query($conn, "rollback");
         die('Query Error : ' . mysqli_error());
         return;
    }
    else {
    	mysqli_query($conn, "commit");
    }
    ?>

    <table class="table table-striped table-bordered">
        <tr>
            <th>신청서번호</th>
            <th>신청일자</th>
            <th>배차일자</th>
            <th>배차목적</th>
            <th>출발지</th>
            <th>도착지</th>
            <th>상태</th>
            <th>기능</th>
        </tr>
        <?
        /*$row_index = 1;*/
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td><a href='application_view.php?app_no={$row['app_no']}'>{$row['app_no']}</a></td>";
            echo "<td>{$row['app_date']}</td>";
            echo "<td>{$row['allocation_date']}</a></td>";
            echo "<td>{$row['purpose']}</td>";
            echo "<td>{$row['departure_point']}</td>";
            echo "<td>{$row['arrival_point']}</td>";
            echo "<td>{$row['app_status']}</td>";
        	if($row['app_status'] == '대기') {
            echo "<td width='17%'>
                <a href='application_form.php?app_no={$row['app_no']}'><button class='button primary small'>수정</button></a>
                 <button onclick='javascript:deleteConfirm({$row['app_no']})' class='button danger small'>삭제</button>
                </td>";
        	}
        	else if($row['app_status'] == '승인' || $row['app_status'] == '취소') {
        		echo "<td width='17%', align='center'>
                <a href='certificate_view.php?cert_no={$row['cert_no']}'><button class='button green small'>배차증 조회</button></a>
                </td>";
        	}
        	else {
        		echo "<td width='17%'></td>";
        	}
            echo "</tr>";
            /*$row_index++;*/
        }
        ?>
    </table>
    <script>
        function deleteConfirm(app_no) {
            if (confirm("정말 삭제하시겠습니까?") == true){    //확인
                window.location = "application_delete.php?app_no=" + app_no;
            }else{   //취소
                return;
            }
        }
    </script>
</div>
<? include("footer.php") ?>
