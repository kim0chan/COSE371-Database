<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

mysqli_query($conn, "set autocommit = 0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "start transaction");

if (array_key_exists("cert_no", $_GET)) {
    $cert_no = $_GET["cert_no"];
    $query = "select * from ((select * from application where cert_no = $cert_no) as app natural join (certificate natural join driver natural join dispatcher))";
    $result = mysqli_query($conn, $query);
    $certificate = mysqli_fetch_assoc($result);
    if (!$certificate) {
    	mysqli_query($conn, "rollback");
        msg("배차증이 존재하지 않습니다.");
        return;
    }
    else {
    	mysqli_query($conn, "commit");
    }
}
?>
    <div class="container fullwidth">

        <h3>배차증<?if($certificate['remark'] == '취소') echo"(취소)"?></h3>
        <p align='right'>배차증번호 : <?= $certificate['cert_no']?><br></p>
        <p align='right'>승인일 : <?= $certificate['date']?><br></p>
        <p align='right'>배차 담당자: <?= $certificate['dispatcher_name'] ?> (☎<?= $certificate['dispatcher_phone']?>)</p>
        <p align="right"><img src="images/stamp.PNG"></p>
		<p><h4>신청자 정보</h4></p>
        <p>
            <label for="manufacturer_id">신청자</label>
            <input readonly type="text" id="customer_name" name="customer_name" value="<?= $certificate['customer_name'] ?>"/>
        </p>

        <p>
            <label for="manufacturer_name">연락처</label>
            <input readonly type="text" id="customer_phone" name="customer_phone" value="<?= $certificate['customer_phone'] ?>"/>
        </p>
		
		<br><br>
		<p><h4>배차 정보</h4></p>
        <br><br>
        <p>
            <label for="allocation_date">배차 일자</label>
            <input readonly type="text" id="allocation_date" name="allocation_date" value="<?= $certificate['allocation_date'] ?>"/>
        </p>
        <p>
            <label for="allocation_date">배차 목적</label>
            <input readonly type="text" id="purpose" name="purpose" value="<?= $certificate['purpose'] ?>"/>
        </p>
        <p>
        	<label for="driver_name">운전자</label>
            <input readonly type="text" id="driver_name" name="driver_name" value="<?= $certificate['driver_name'] ?> (☎<?= $certificate['driver_phone'] ?>)"/>
        </p>
        <p>
            <label for="vehicle_type">차종</label>
            <input readonly type="text" id="vehicle_type" name="vehicle_type" value="<?= $certificate['vehicle_type'] ?>"/>
        </p>
        <p>
        	<?
            if($certificate['purpose'] == '화물 운송') {
        		echo "<label for=\"weight_or_headcount\">하중(kg)</label>";
        	}
        	else {
        		echo "<label for=\"weight_or_headcount\">인원 수(명)</label>";
        	}
        	?>
        	<input readonly type="text" id="weight_or_headcount" name="weight_or_headcount" value="<?= $certificate['weight_or_headcount'] ?>"/>
        </p>
        <br><br>
        <p>
            <label for="departure_point">출발지</label>
            <input readonly type="text" id="departure_point" name="departure_point" value="<?= $certificate['departure_point'] ?>"/>
        </p>
        <p>
            <label for="departure_time">출발 시각</label>
            <input readonly type="text" id="departure_time" name="departure_time" value="<?= $certificate['departure_time'] ?>"/>
        </p>
        <p>
            <label for="departure_point">도착지</label>
            <input readonly type="text" id="arrival_point" name="arrival_point" value="<?= $certificate['arrival_point'] ?>"/>
        </p>
        <p>
            <label for="departure_time">도착 시각</label>
            <input readonly type="text" id="arrival_time" name="arrival_time" value="<?= $certificate['arrival_time'] ?>"/>
        </p>
        <br><br>
        <p><h4>기타</h4></p>
        <br><br>
        <p>
            <label for="remark">비고</label>
            <input readonly type="text" id="remark" name="remark" value="<?= $certificate['remark'] ?>"/>
        </p>
        <p>
            <label for="cost">지불 비용</label>
            <input readonly type="text" id="cost" name="cost" value="<?= $certificate['cost'] ?>"/>
        </p>
    </div>
    <br>
    <p align="center">
            	<button class="button green large" onclick="javascript:return printConfirm();">출력</button>
        </p>
        <script>
                function printConfirm() {
                	/////////////////////////
                    if (confirm("출력하시겠습니까?") == true){    //확인
                		window.print();
            		}else{   //취소
                		return;
            		}
                }
        </script>
<? include "footer.php" ?>