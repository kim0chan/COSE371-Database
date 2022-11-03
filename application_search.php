﻿﻿<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$action = "application_list.php";
?>
    <div class="container">
        <form name="search_form" action="<?=$action?>" method="post" class="fullwidth">
            <h3>배차 신청서 조회<?=$mode?></h3>
            <br>
            <p><h4>신청 시 제출한 정보를 입력하세요.</h4></p>
            <p>
                <p>성명</p><input type="text" placeholder="신청자 성명" id = "search_name" name="search_name"/>
                <p>연락처</p><input type="text" placeholder="신청자 연락처('-' 없이 입력)" id = "search_phone"/>
                <p>비밀번호</p><input type="text" placeholder="신청 시 비밀번호" id = "search_pw" name="search_pw"/>
                <h6 style="color:lightgray;">비밀번호 분실 시 ☎○○○-○○○○로 연락 바랍니다.</h6>
            </p>
            <br>
            
            <p align="center"><button class="button primary large" onclick="javascript:return validate_s();">조회</button></p>

            <script>
                function validate_s() {
                	/////////////////////////
                	/*alert (document.getElementById("search_name").value); return false;*/
                	/////////////////////////
                    if(document.getElementById("search_name").value == "") {
                        alert ("신청자 성명을 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("search_phone").value == "") {
                        alert ("신청자 연락처를 입력해 주십시오"); return false;
                    }
                    else if(document.getElementById("search_pw").value == "") {
                        alert ("신청 비밀번호를 입력해 주십시오"); return false;
                    }
                    return true;
                }
            </script>
            
        </form>
    </div>

<? include("footer.php") ?>