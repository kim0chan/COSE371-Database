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
    
    $query = "select * from application where app_status = '대기'";
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
	<p>승인 대기중인 신청서 목록입니다.</p>
    <table class="table table-striped table-bordered">
        <tr>
            <th>신청서번호</th>
            <th>신청일자</th>
            <th>배차일자</th>
            <th>차종</th>
            <th>배차목적</th>
            <th>출발지</th>
            <th>출발시간</th>
            <th>도착지</th>
            <th>도착시간</th>
        </tr>
        <?
        $row_index = 1;
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td><a href='application_view_eval.php?app_no={$row['app_no']}'>{$row['app_no']}</a></td>";
            echo "<td>{$row['app_date']}</td>";
            echo "<td>{$row['allocation_date']}</a></td>";
            echo "<td>{$row['vehicle_type']}</a></td>";
            echo "<td>{$row['purpose']}</td>";
            echo "<td>{$row['departure_point']}</td>";
            echo "<td>{$row['departure_time']}</td>";
            echo "<td>{$row['arrival_point']}</td>";
            echo "<td>{$row['arrival_time']}</td>";
            echo "</tr>";
            $row_index++;
        }
        ?>
    </table>
</div>
<? include("footer.php") ?>
