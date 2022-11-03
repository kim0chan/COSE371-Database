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
    
    $query = "select * from vehicle";
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
	<h4>차량 목록</h4><br>
    <table class="table table-striped table-bordered">
        <tr>
            <th>차량번호</th>
            <th>차종</th>
            <th>모델명</th>
            <th>운전가능(최소)면허</th>
        </tr>
        <?
        $row_index = 1;
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>{$row['vehicle_no']}</td>";
            echo "<td>{$row['type']}</a></td>";
            echo "<td>{$row['model']}</td>";
        	if($row['vehicle_license'] == '0') {
            	echo "<td>1종 대형</td>";
        	}
        	else if($row['vehicle_license'] == '1') {
            	echo "<td>1종 보통</td>";
        	}
        	else {
        		echo "<td>2종 보통</td>";
        	}
            echo "</tr>";
            $row_index++;
        }
        ?>
    </table>
</div>
<? include("footer.php") ?>
