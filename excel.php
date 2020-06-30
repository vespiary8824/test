<?
header( "Content-type: application/vnd.ms-excel; charset=utf-8"); 

$fname = date('Y-m-d').'_apt2you.xls';

header( "Content-Disposition: attachment; filename=$fname" ); 
header( "Content-Description: PHP4 Generated Data" ); 
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">"); 

const excel = 1;

include_once('query.php');
$data = Search($month);

echo "
            <table border='1'>
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
    ";


foreach ($data as $obj) {
	foreach ($obj['link'] as $item) {
		echo "<tr>".
				"<td>" . $obj['location'] . "</td>"
				. "<td>" . $obj['name'] . "</td>"
				. "<td>" . $item['addr'] . "</td>"
				. "<td>" . $item['date'] . "</td>"
				. "<td>" . $item['gubun'] . "</td>"
				. "<td>" . $item['num'] . "</td>"
				. "<td>" . $item['square'] . "</td>"
				. "<td>" . $item['type'] . "</td>"
				. "<td>" . $item['normal'] . "</td>"
				. "<td>" . $item['special'] . "</td>"	
				. "<td>" . $obj['imdae'] . "</td>"
				. "<td>" . $obj['period'] . "</td>"
				. "<td>" . $obj['company'] . "</td>"
				. "</tr>";
	}
}


echo "
		</tbody>
    </table>
    ";


?>