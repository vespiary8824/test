<?php
header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>

<html>
  <head>
	<meta charset='utf-8'/>
    <title>apt2you 청약목록</title>
	<link href="/css/metro.min.css" rel="stylesheet">
    <link href="/css/metro.css" rel="stylesheet">
    <link href="/css/metro-icons.css" rel="stylesheet">
    <link href="/css/metro-responsive.css" rel="stylesheet">
    <link href="/css/metro-schemes.css" rel="stylesheet">

    <script src="/js/jquery-2.1.3.min.js"></script>
    <script src="/js/metro.js"></script>
    <script src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
    <script src="/js/ga.js"></script>

	<script type="text/javascript">

	function onload(){
		var d = new Date();
		var month = d.getMonth()+1;
		if(month < 10)
		{
			month = '0' + month;
		}
		var today = d.getFullYear() + '' +month;

		$("#date").val(today);
	}

	function loadTable(obj){
		$('#aptList > tbody').empty();

//		console.log(obj);
		
		var row = "";
		
		$.each(obj, function(idx, obj) {

			row = "";

			$.each(obj['link'], function(i, item) {
				row = "<tr>"
						+ "<td>" + obj['location'] + "</td>"
						+ "<td>" + obj['name'] + "</td>"
						+ "<td>" + item['addr'] + "</td>"
						+ "<td>" + item['date'] + "</td>"
						+ "<td>" + item['gubun'] + "</td>"
						+ "<td>" + item['num'] + "</td>"
						+ "<td>" + item['square'] + "</td>"
						+ "<td>" + item['type'] + "</td>"
						+ "<td>" + item['normal'] + "</td>"
						+ "<td>" + item['special'] + "</td>"			
						+ "<td>" + obj['imdae'] + "</td>"
						+ "<td>" + obj['period'] + "</td>"
						+ "<td>" + obj['company'] + "</td>"
						+ "</tr>";

				$("#aptList").append(row);
			});
		});
	}

	function fn_Search(){
		var today = $("#date").val();
		console.log(today);

		$.ajax({
			dataType:"json", 
			type: 'get',
			url : "/apt2you/query.php?month=" + today,
			success : function(data, status, xhr) { 
						console.log("success" + today); 
						alert('조회완료');
						loadTable(data);
						$.each(data, function(idx, obj) {
							console.log(obj);
						});
					}, 
			error: function(jqXHR, textStatus, errorThrown) { 
						console.log("[" + jqXHR.status + "] " + jqXHR.responseText + "\n error : " + errorThrown); 
						alert('조회실패');
					} 
		});

	}

	function fn_excel(){
		var today = $("#date").val();
		window.location.href= "/apt2you/excel.php?month=" + today;
	}


	</script>

  </head>
  <body onload="javascript:onload();">
	<? include_once "layout/header.html"; ?>
    <div class="container page-content">
        <h1 class="text-light">apt2you 청약 목록</h1>

		<div class="example">
            <div class="grid">
                <div class="row cells2">
                    <div class="cell">
						<div class="input-control text" data-role="datepicker" data-format="yyyymm" data-scheme="darcula" >
							<input type="text" id="date">
							<button class="button"><span class="mif-calendar"></span></button>
						</div>
                    </div>
                    <div class="cell">
						<button class="button rounded" onclick="fn_Search();">청약목록 조회</button>
						<button class="button rounded" onclick="fn_excel();">청약목록 엑셀 다운로드</button>
                    </div>
                </div>
			</div>
        </div>

		<div class="example">
            <table class="table hovered cell-hovered border bordered striped" id="aptList">
				<colgroup>
					<col width="5%"/>
					<col width="*"/>
					<col width="*"/>
					<col width="5%"/>
					<col width="5%"/>
					<col width="5%"/>
					<col width="5%"/>
					<col width="5%"/>
					<col width="5%"/>
					<col width="5%"/>
					<col width="5%"/>
					<col width="5%"/>
					<col width="5%"/>
				</colgroup>
                <thead>
                <tr>
                    <th>지역</th>
                    <th>아파트 명</th>
                    <th>주소</th>
                    <th>입주예정일</th>
                    <th>구분</th>
                    <th>관리번호</th>
                    <th>면적</th>
                    <th>타입</th>
                    <th>일반</th>
                    <th>특별</th>
                    <th>분양/임대</th>
                    <th>청약기간</th>
                    <th>건설사</th>
                </tr>
                </thead>
                <tbody>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
                </tbody>
            </table>
        </div>
    </div>
  </body>
</html>

