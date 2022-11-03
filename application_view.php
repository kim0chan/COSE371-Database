﻿﻿<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

mysqli_query($conn, "set autocommit = 0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "start transaction");

if (array_key_exists("app_no", $_GET)) {
    $app_no = $_GET["app_no"];
    $query = "select * from application where app_no = $app_no";
    $result = mysqli_query($conn, $query);
    $application = mysqli_fetch_assoc($result);
    if (!$application) {
    	mysqli_query($conn, "rollback");
        msg("신청서가 존재하지 않습니다.");
        return;
    }
    else {
    	mysqli_query($conn, "commit");
    }
}
?>
    <div class="container fullwidth">

        <h3>배차 신청서</h3>
		<p><h4>신청자 정보</h4></p>
        <p>
            <label for="customer_name">신청자</label>
            <input readonly type="text" id="customer_name" name="customer_name" value="<?= $application['customer_name'] ?>"/>
        </p>

        <p>
            <label for="customer_phone">연락처</label>
            <input readonly type="text" id="customer_phone" name="customer_phone" value="<?= $application['customer_phone'] ?>"/>
        </p>
		
		<br><br>
		<p><h4>배차 정보</h4></p>
		<p>
            <label for="app_no">신청서 번호</label>
            <input readonly type="text" id="app_no" name="app_No" value="<?= $application['app_no'] ?>"/>
        </p>
        <p>
            <label for="app_date">신청 일자</label>
            <input readonly type="text" id="app_date" name="app_date" value="<?= $application['app_date'] ?>"/>
        </p>
        <br><br>
        <p>
            <label for="allocation_date">배차 일자</label>
            <input readonly type="text" id="allocation_date" name="allocation_date" value="<?= $application['allocation_date'] ?>"/>
        </p>
        <p>
            <label for="allocation_date">배차 목적</label>
            <input readonly type="text" id="purpose" name="purpose" value="<?= $application['purpose'] ?>"/>
        </p>
        <p>
            <label for="vehicle_type">차종</label>
            <input readonly type="text" id="vehicle_type" name="vehicle_type" value="<?= $application['vehicle_type'] ?>"/>
        </p>
        <p>
        	<?
            if($application['purpose'] == '화물 운송') {
        		echo "<label for=\"weight_or_headcount\">하중(kg)</label>";
        	}
        	else {
        		echo "<label for=\"weight_or_headcount\">인원 수(명)</label>";
        	}
        	?>
        	<input readonly type="text" id="weight_or_headcount" name="weight_or_headcount" value="<?= $application['weight_or_headcount'] ?>"/>
        </p>
        <br><br>
        <p>
            <label for="departure_point">출발지</label>
            <input readonly type="text" id="departure_point" name="departure_point" value="<?= $application['departure_point'] ?>"/>
        </p>
        <p>
            <label for="departure_time">출발 시각</label>
            <input readonly type="text" id="departure_time" name="departure_time" value="<?= $application['departure_time'] ?>"/>
        </p>
        <p>
            <label for="departure_point">도착지</label>
            <input readonly type="text" id="arrival_point" name="arrival_point" value="<?= $application['arrival_point'] ?>"/>
        </p>
        <p>
            <label for="departure_time">도착 시각</label>
            <input readonly type="text" id="arrival_time" name="arrival_time" value="<?= $application['arrival_time'] ?>"/>
        </p>
        <br><br>
        <p>
            <label for="app_status">상태</label>
            <?
            if($application['app_status'] == '승인') {
        		echo "<a href='certificate_view.php?cert_no={$application['cert_no']}'><input readonly type=\"text\" id=\"app_status\" name=\"app_status\" value=\"{$application['app_status']}(배차증 조회)\"/></a>";
        	}
        	else {
        		echo "<input readonly type=\"text\" id=\"app_status\" name=\"app_status\" value=\"{$application['app_status']}\"/>";
        	}
        	?>
        </p>
    </div>
<? include "footer.php" ?>