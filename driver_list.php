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
    
    $query = "select * from driver";
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
	<h4>근무자(운전자) 목록</h4><br>
    <table class="table table-striped table-bordered">
        <tr>
            <th>번호</th>
            <th>성명</th>
            <th>연락처</th>
            <th>면허</th>
            <th>나이</th>
        </tr>
        <?
        $row_index = 1;
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>{$row['driver_no']}</td>";
            echo "<td>{$row['driver_name']}</a></td>";
            echo "<td>{$row['driver_phone']}</td>";
        	if($row['driver_license'] == '0') {
            	echo "<td>1종 대형</td>";
        	}
        	else if($row['driver_license'] == '1') {
            	echo "<td>1종 보통</td>";
        	}
        	else {
        		echo "<td>2종 보통</td>";
        	}
        	echo "<td>{$row['age']}</td>";
            echo "</tr>";
            $row_index++;
        }
        ?>
    </table>
</div>
<? include("footer.php") ?>
