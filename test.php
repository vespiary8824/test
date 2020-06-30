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
    <script src="/js/docs.js"></script>
    <script src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
    <script src="/js/ga.js"></script>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

	<script type="text/javascript">
	</script>

  </head>
  <body>
    <div class="container">
        <h1 class="text-light">apt2you 청약 목록</h1>

		<div class="example">
            <div class="grid">
                <div class="row cells2">
                    <div class="cell">
						<div class="input-control text" data-role="datepicker" data-preset="2015-05-01">
							<input type="text">
							<button class="button"><span class="mif-calendar"></span></button>
						</div>
                    </div>
                    <div class="cell">
						<div class="margin10">
							<button class="button rounded" onclick="">청약목록 조회</button>
							<button class="button rounded">청약목록 엑셀 다운로드</button>
						</div>
                    </div>
                </div>
			</div>
        </div>
		<div class="example">
	<?php
		include_once('../common/simple_html_dom.php');
		include_once('../common/Snoopy.class.php');
		$_Domain = "https://www.apt2you.com/";


		//-----------------------------------------------------
		// function for get htmlDocument
		//-----------------------------------------------------

		function get_html_doc($url, $params=array())
		{
			$url = $url.'?'.http_build_query($params, '', '&');
			
			$snoopy = new Snoopy();
			$snoopy->fetch($url);
			$content = $snoopy->results;
			$html = new simple_html_dom();
			$html->load($content);

			return $html;
		}

		function SubString($origin, $start, $end){
			$startPos = stripos($origin, $start) + strlen($start);
			$endPos = strpos($origin, $end, $startPos);

			return substr($origin, $startPos, $endPos - $startPos);
		}

		function get_Detail($id){
			global $_Domain;
			$doc = get_html_doc($_Domain."houseSaleDetailInfo.do", array('manageNo' => $id));
			$node = array();
			$nodeList = array();
			$addr;

			$temp = $doc->find('div.pop_conts .red_dot');
			foreach ( $temp as $ul){
				foreach ( $ul->find('li') as $li )  {
					$addr = str_replace("공급위치 : ", "", $li->plaintext);
					break;
				}
			}

			$temp = $doc->find('div.footnote_tbl');
			$date = SubString($temp[2]->plaintext, "입주예정월 :", "*");

			$List = $doc->find('.tbl_default', 1);

			$index = 0;
			$gubun;

			foreach($List->find('tr') as $row){
				if($row->parent->tag == 'tbody') { 
	  				if($index == 0){
						$gubun = $row->children(0);

						$node['addr'] = $addr;
						$node['date'] = $date;
						$node['gubun'] = $gubun;
						$node['num'] = $row->children(1);
						$node['type'] = $row->children(2);
						$node['square'] = $row->children(3);
						$node['special'] = $row->children(4);
						$node['normal'] = $row->children(5);
					}
					else {
						$node['addr'] = $addr;
						$node['date'] = $date;
						$node['gubun'] = $gubun;
						$node['num'] = $row->children(0);
						$node['type'] = $row->children(1);
						$node['square'] = $row->children(2);
						$node['special'] = $row->children(3);
						$node['normal'] = $row->children(4);
					}

					$index ++;
					$nodeList[] = $node;
				}

			}

			return $nodeList;
		}

		function Search($month){
			global $_Domain;
			$doc = get_html_doc($_Domain."houseSaleSimpleInfo.do", array('compareDate' => $month));

			$aptList = $doc->find('.tbl_default .line tr');

			$node = array();
			$nodeList = array();


			foreach($aptList as $item)
			{	
				$id = SubString($item->children(1), "onclick=\"showDetailInfo('", "')");
				$node['location'] = $item->children(0);
				$node['link'] = get_Detail($id);
				$node['company'] = $item->children(2);
				$node['period'] = $item->children(3);

				$nodeList[] = $node;

			}

			foreach($nodeList as $list){
				foreach($list['link'] as $item){
					echo $list['location'] . ' ';
					echo $item['addr'] . ' ';
					echo $item['date'] . ' ';
					echo $item['gubun'] . ' ';
					echo $item['num'] . ' ';
					echo $item['type'] . ' ';
					echo $item['square'] . ' ';
					echo $item['special'] . ' ';
					echo $item['normal'] . '<br>';
				}
			}

		
		}

		Search('201712');

	?>
		</div>
	</div>
  </body>
</html>

