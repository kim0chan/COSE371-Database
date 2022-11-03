<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

mysqli_query($conn, "set autocommit = 0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "start transaction");

$mode = "작성";
$action = "application_insert.php";

if (array_key_exists("app_no", $_GET)) {
	$app_no = $_GET['app_no'];
    $query =  "select * from application where app_no = $app_no";
    $result = mysqli_query($conn, $query);
    if(!$result) {
    	mysqli_query($conn, "rollback");
    	msg("오류가 발생했습니다.");
    	return;
    }
    $application = mysqli_fetch_array($result);
    if(!$application) {
    	mysqli_query($conn, "rollback");
        msg("배차 신청서가 존재하지 않습니다.");
        return;
    }
    $mode = "수정";
    $action = "application_modify.php";
}

$arr_purpose = array('화물 운송', '인원 수송');
$arr_vehicle = array('트럭(1톤)', '트럭(2톤)', '트럭(5톤)', '윙바디', '버스(25인승)', '버스(45인승)', '승용차', '승합차');

mysqli_query($conn, "commit");

?>
    <div class="container">
        <form name="application_form" action="<?=$action?>" method="post" class="fullwidth">
            <input type="hidden" name="app_no" value="<?=$application['app_no']?>"/>
            <h3>배차 신청서 <?=$mode?></h3>
            <br>
            <p><h4>신청자 정보</h4></p>
            <p>
                <p>성명</p><input type="text" placeholder="신청자 성명" id="customer_name" name="customer_name" value="<?=$application['customer_name']?>"/>
                <p>연락처</p><input type="text" placeholder="신청자 연락처('-' 없이 입력)" id="customer_phone" name="customer_phone" value="<?=$application['customer_phone']?>"/>
            </p>
            <br><br>
            <p><h4>배차 정보</h4></p>
            <p>
            	<label for="allocation_date">배차 희망 일자: </label>
            	<input type="date" placeholder="날짜 입력" id="allocation_date" name="allocation_date" value="<?=$application['allocation_date']?>"/>
            </p>
            <p>
                <label for="purpose">배차 목적</label>
                <select name="purpose" id="purpose">
                    <option value="-1">선택해 주십시오.</option>
                    <?
                        foreach($arr_purpose as $name) {
                            if($application && $name == $application['purpose']){
                                echo "<option value='{$name}' selected>{$name}</option>";
                            } else {
                                echo "<option value='{$name}'>{$name}</option>";
                            }
                        }
                    ?>
                </select>
            </p>
            <p>
                <label for="vehicle_type">배차 차종</label>
                <select name="vehicle_type" id="vehicle_type">
                    <option value="-1">선택해 주십시오.</option>
                    <?
                        foreach($arr_vehicle as $name) {
                            if($application && $name == $application['vehicle_type']){
                                echo "<option value='{$name}' selected>{$name}</option>";
                            } else {
                                echo "<option value='{$name}'>{$name}</option>";
                            }
                        }
                    ?>
                </select>
            </p>
            <p>하중(인원 수)</p><input type="number" placeholder="단위: kg(화물), 명(인원)" id="weight_or_headcount" name="weight_or_headcount" min="0" value="<?=$application['weight_or_headcount']?>"/>
            <p>
                <label for="departure_time">출발 시각</label>
                <select name="departure_time" id="departure_time">
                    <option value="-1" selected>선택해 주십시오.</option>
                    <?
                    	$i = 6;
                        for($i; $i < 24; $i++) {
                        	if($application && $i == $application['departure_time']){
                            	echo "<option value='{$i}' selected>{$i}</option>";
                        	}
                        	else {
                        		echo "<option value='{$i}'>{$i}</option>";
                        	}
                        }
                    ?>
                </select>
            </p>
            <p>출발지</p><input type="text" placeholder="출발지 입력" id="departure_point" name="departure_point" value="<?=$application['departure_point']?>"/>
            <p>
                <label for="arrival_time">도착 시각</label>
                <select name="arrival_time" id="arrival_time">
                    <option value="-1" selected>선택해 주십시오.</option>
                    <?
                    	$i = 6;
                        for($i; $i < 24; $i++) {
                        	if($application && $i == $application['arrival_time']){
                            	echo "<option value='{$i}' selected>{$i}</option>";
                        	}
                        	else {
                        		echo "<option value='{$i}'>{$i}</option>";
                        	}
                        }
                    ?>
                </select>
            </p>
            <p>도착지</p><input type="text" placeholder="도착지 입력" id="arrival_point" name="arrival_point" value="<?=$application['arrival_point']?>"/>
            <br><br><br>
            <p><h4>보안</h4></p>
            <p>비밀번호</p><input type="text" placeholder="비밀번호 입력(4글자)" id="app_password" name="app_password" value="<?=$application['app_password']?>"/>
            
            
			<h1><br></h1>
            <p align="center"><button class="button primary large" onclick="javascript:return validate();"><?=$mode?></button></p>

            <script>
                function validate() {
                	var dtime = Number(document.getElementById("departure_time").value);
                	var atime = Number(document.getElementById("arrival_time").value);
                	const today = new Date();
                	const allocation = new Date(document.getElementById("allocation_date").value);
                	/////////////////////////
                	/////////////////////////
                    if(document.getElementById("customer_name").value == "") {
                        alert ("신청자 성함을 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("customer_phone").value == "") {
                        alert ("신청자 연락처를 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("allocation_date").value == "") {
                        alert ("배차 신청 일자를 입력해 주십시오"); return false;
                    }
                    else if(today > allocation){
                    	alert ("배차 신청 일자를 올바르게 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("purpose").value == "-1") {
                        alert ("배차 목적을 선택해 주십시오"); return false;
                    }
                    else if(document.getElementById("vehicle_type").value == "-1") {
                        alert ("배차 차종을 선택해 주십시오"); return false;
                    }
                    else if(document.getElementById("weight_or_headcount").value == "") {
                        alert ("하중(인원 수)을 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("weight_or_headcount").value == "0") {
                        alert ("하중(인원 수)을 올바르게 입력해 주십시오"); return false;
                    }
                    else if(dtime == -1) {
                        alert ("출발 시각을 선택해 주십시오"); return false;
                    }
                    else if(document.getElementById("departure_point").value == "") {
                        alert ("출발지를 입력해 주십시오"); return false;
                    }
                    else if(atime == -1) {
                        alert ("도착 시각을 선택해 주십시오"); return false;
                    }
                    else if(dtime > atime) {
                    	alert ("출발/도착 시각을 올바르게 선택해 주십시오"); return false;
                    }
                    else if(document.getElementById("arrival_point").value == "") {
                        alert ("도착지를 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("app_password").value == "") {
                        alert ("신청 비밀번호를 입력해 주십시오"); return false;
                    }
                    return true;
                }
            </script>

        </form>
    </div>
<? include("footer.php") ?>