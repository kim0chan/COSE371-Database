﻿<?
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
    
    $query = "select * from certificate natural join application natural join dispatcher natural join driver";
    $result = mysqli_query($conn, $query);
    if (!$result) {
    	 mysqli_query($conn, "rollback");
         die('Query Error : ' . mysqli_error());
         return;
    }
    else {
    	mysqli_query($conn, "commit");
    }
    $today = date("Y-m-d");
    ?>
    

    <table class="table table-striped table-bordered">
        <tr>
            <th>배차증번호</th>
            <th>배차일자</th>
            <th>승인일자</th>
            <th>배차담당자</th>
            <th>운전자</th>
            <th>차종</th>
            <th>차량번호</th>
            <th>기타</th>
        </tr>
        <?
        /*$row_index = 1;*/
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td><a href='certificate_view.php?cert_no={$row['cert_no']}'>{$row['cert_no']}</a></td>";
            echo "<td>{$row['allocation_date']}</td>";
            echo "<td>{$row['date']}</td>";
            echo "<td>{$row['dispatcher_name']}</a></td>";
            echo "<td>{$row['driver_name']}</td>";
            echo "<td>{$row['vehicle_type']}</td>";
            echo "<td>{$row['vehicle_no']}</td>";
            if($row['remark'] == '취소') {
            	echo "<td>취소됨</td>";
        	}
        	else if($row['allocation_date'] < $today) {
        		echo "<td>집행됨</td>";
        	}
        	else {
        		echo "<td width='17%', align='center'>
                 <button onclick='javascript:cancelConfirm({$row['cert_no']})' class='button danger small'>취소</button>
                </td>";
        	}
            echo "</tr>";
            /*$row_index++;*/
        }
        ?>
    </table>
    <script>
        function cancelConfirm(cert_no) {
            if (confirm("정말 취소하시겠습니까?") == true){    //확인
                window.location = "certificate_cancel.php?cert_no=" + cert_no;
            }else{   //취소
                return;
            }
        }
    </script>
</div>
<? include("footer.php") ?>
