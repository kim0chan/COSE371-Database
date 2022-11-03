<?
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
    $query_dispatcher = "select * from dispatcher";
    $result = mysqli_query($conn, $query);
    if (!$result) {
    	mysqli_query("rollback");
        msg("신청서가 존재하지 않습니다.");
        return;
    }
    $result_dispatcher = mysqli_query($conn, $query_dispatcher);
    if(!$result_dispatcher) {
    	mysqli_query("rollback");
        msg("오류가 발생했습니다.");
        return;
    }
    $application = mysqli_fetch_assoc($result);
    $type = $application['vehicle_type'];
    if($type == '트럭(5톤)' || $type == '버스(25인승)' || $type == '버스(45인승)' || $type == '윙바디') {
    	$license = 0;
    }
    else if($type == '트럭(1톤)' || $type == '트럭(2톤)' || $type == '승합차') {
    	$license = 1;
    }
    else {
    	$license = 2;
    }
    $query_vehicle = "select * from vehicle where type = '$type' and vehicle_no not in (select vehicle_no from certificate natural join vehicle natural join application where((app_status != '취소') and (allocation_date = '{$application['allocation_date']}') and ((departure_time between '{$application['departure_time']}' and '{$application['arrival_time']}') or (arrival_time between '{$application['departure_time']}' and '{$application['arrival_time']}') or (departure_time <= '{$application['departure_time']}' and arrival_time >= '{$application['arrival_time']}'))))";
    $result_vehicle = mysqli_query($conn, $query_vehicle);
    if(!$result_vehicle) {
    	mysqli_query("rollback");
        msg("오류가 발생했습니다.");
        return;
    }
    $query_driver = "select * from driver where driver_name not in (select driver_name from certificate natural join driver natural join application where ((app_status != '취소') and (allocation_date = '{$application['allocation_date']}') and ((departure_time between '{$application['departure_time']}' and '{$application['arrival_time']}') or (arrival_time between '{$application['departure_time']}' and '{$application['arrival_time']}') or (departure_time <= '{$application['departure_time']}' and arrival_time >= '{$application['arrival_time']}')))) and driver_license <= $license";
    $result_driver = mysqli_query($conn, $query_driver);
    if(!$result_driver) {
    	mysqli_query("rollback");
        msg("오류가 발생했습니다.");
        return;
    }
    
    mysqli_query("commit");
    
}

?>
    <div class="container fullwidth">
		<h3>배차 신청서</h3>
		<p><h4>신청자 정보</h4></p>
    	<p>
        	<label for="manufacturer_id">신청자</label>
	    	<input readonly type="text" id="customer_name" name="customer_name" value="<?= $application['customer_name'] ?>"/>
    	</p>
        <p>
	    	<label for="manufacturer_name">연락처</label>
        	<input readonly type="text" id="customer_phone" name="customer_phone" value="<?= $application['customer_phone'] ?>"/>
        </p>
			
		<br><br>
		<p><h4>배차 신청 정보</h4></p>
		<p>
	    	<label for="app_no">신청서 번호</label>
        	<input readonly type="text" id="app_no" name="app_no" value="<?= $application['app_no'] ?>"/>
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
        <form name="certificate_form" action="certificate_insert.php" method="post" class="fullwidth">
        	<input type="hidden" name="app_no" value="<?=$application['app_no']?>"/>
        	<h3>배차증 정보 입력</h3>
        	<p>
        		<label for="dispatcher_no">배차 담당자</label>
        		<select name="dispatcher_no" id="dispatcher_no">
        			<option value="-1">선택해 주십시오.</option>
        			<?
        				while ($row = mysqli_fetch_array($result_dispatcher)) {
        					echo "<option value='{$row['dispatcher_no']}'>{$row['dispatcher_name']}</option>";
        				}
        			?>
        		</select>
            </p>
            <p>
	           	<label for="driver_no">운전자</label>
            	<select name="driver_no" id="driver_no">
            		<option value="-1">선택해 주십시오.</option>
            		<?
        				while ($row = mysqli_fetch_array($result_driver)) {
        					echo "<option value='{$row['driver_no']}'>{$row['driver_name']}</option>";
        				}
        			?>
            	</select>
            </p>
            <p>
	           	<label for="vehicle_no">차량</label>
            	<select name="vehicle_no" id="vehicle_no">
            		<option value="-1">선택해 주십시오.</option>
            		<?
        				while ($row = mysqli_fetch_array($result_vehicle)) {
        					echo "<option value='{$row['vehicle_no']}'>{$row['vehicle_no']} [{$row['type']}]</option>";
        				}
        			?>
            	</select>
            </p>
            <p>
	           	<label for="cost">단가</label>
            	<input type="number" placeholder="단가 입력" id="cost" name="cost"/>
            </p>
            <p>
	            <label for="remark">비고</label>
               	<textarea placeholder="비고 입력" id="remark" name="remark" rows="10"></textarea>
            </p>
            <h1><br></h1>
            <p align="center">
            	<button class="button primary small" onclick="javascript:return validate();">승인</button>
            </p>
            <script>
                function validate() {
                	/////////////////////////
                	/////////////////////////
                    if(document.getElementById("dispatcher_no").value == "-1") {
                        alert ("배차 담당자를 지정하십시오"); return false;
                    }
                    else if(document.getElementById("driver_no").value == "-1") {
                        alert ("운전자를 지정하십시오"); return false;
                    }
                    else if(document.getElementById("vehicle_no").value == "-1") {
                        alert ("차량을 지정하십시오"); return false;
                    }
                    else if(document.getElementById("cost").value == "") {
                        alert ("금액을 입력하십시오"); return false;
                    }
                    else if(document.getElementById("remark").value == "취소") {
                    	alert ("장난치면 안됩니다!\n(비고에 '취소' 입력하면 큰일납니다.)"); return false;
                    }
                    return true;
                }
            </script>
        </form>
        <p align="center">
            	<button class="button danger small" onclick="javascript:return returnConfirm(<?= $app_no ?>);">반려</button>
        </p>
        <script>
                function returnConfirm(app_no) {
                	/////////////////////////
                    if (confirm("정말 반려하시겠습니까?") == true){    //확인
                		window.location = "certificate_return.php?app_no=" + app_no;
            		}else{   //취소
                		return;
            		}
                }
        </script>
    </div>
<? include "footer.php" ?>