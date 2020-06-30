<?php
header('Content-Type: application/json');

include_once('../common/common.php');
include_once('../common/simple_html_dom.php');
include_once('../common/Snoopy.class.php');
$_Domain = "https://www.apt2you.com/";

//const debug = 1;


//-----------------------------------------------------
// function for parse detail page 
//-----------------------------------------------------
function get_Detail($id){
	global $_Domain;
	$doc = get_html_doc($_Domain."houseSaleDetailInfo.do", array('manageNo' => $id));
	
	$nodeList = array();
	$addr = "";
	$date = "";

	$temp = $doc->find('.layer_content');
	foreach ( $temp as $ul){
		foreach ( $ul->find('li') as $li )  {
			$tmp = iconv("euckr", "utf8", $li->plaintext);

			if(strpos($tmp, "- 공급위치 : ") !== false){
				$addr = toHalfChar(str_replace("- 공급위치 : ", "", $tmp));
			}
			else if(strpos($tmp, "입주예정월 : ") !== false){
				$date = str_replace("입주예정월 : ", "", $tmp);
			}
		}
	}

	$List = $doc->find('table', 1);

	$index = 0;
	$gubun;

	foreach($List->find('tr') as $row){
		if($row->parent->tag == 'tbody') { 
		$node = array();
			if($index == 0){
				$gubun = iconv("euckr", "utf8", $row->children(0)->plaintext);

				$node['addr'] = trim($addr);
				$node['date'] = trim($date);
				$node['gubun'] = trim($gubun);
				$node['num'] = trim($row->children(1)->plaintext);
				$node['type'] = trim($row->children(2)->plaintext);
				$node['square'] = trim($row->children(3)->plaintext);
				$node['special'] = trim($row->children(4)->plaintext);
				$node['normal'] = trim($row->children(5)->plaintext);

			}
			else {				
				$node['addr'] = trim($addr);
				$node['date'] = trim($date);
				$node['gubun'] = trim($gubun);
				$node['num'] = trim($row->children(0)->plaintext);
				$node['type'] = trim($row->children(1)->plaintext);
				$node['square'] = trim($row->children(2)->plaintext);
				$node['special'] = trim($row->children(3)->plaintext);
				$node['normal'] = trim($row->children(4)->plaintext);				
			}
		
			$nodeList[$index] = $node;
			$index ++;
		}

	}
	return $nodeList;
}

//-----------------------------------------------------
// function for parse simple page 
//-----------------------------------------------------

function Search($month){
	global $_Domain;
	$doc = get_html_doc($_Domain."houseSaleSimpleInfo.do", array('compareMonthStart'=> $month, 'compareMonthEnd'=> $month));

	$aptList = $doc->find('table tr');

	$node = array();
	$nodeList = array();


	foreach($aptList as $key=>$item)
	{	
		if($item->children(0)->plaintext == '지역')
			continue;

		$id = SubString($item->children(3)->outertext, "showSaleDetailInfo('", "')");
		$node['location'] = trim(toHalfChar($item->children(0)->plaintext));
		$node['name'] = trim(toHalfChar(str_replace("NEW", "", $item->children(3)->plaintext)));
		$node['imdae'] = trim(toHalfChar($item->children(2)->plaintext));
		$node['period'] = trim(toHalfChar($item->children(5)->plaintext));
		$node['company'] = trim(toHalfChar($item->children(4)->plaintext));
		$node['link'] = get_Detail($id);

		$nodeList[$key] = $node;
	}

	return $nodeList;

}





//echo trim(toHalfChar('서울특별시　송파구　거여동　２３４번지　일원'));


if (defined('debug')) {
//	$data = Search('201712');
//	print_r($data);
}
else if(defined('excel')){
}
else {
	$month = $_GET['month'];
	$data = Search($month);
	echo json_encode($data);
}

?>