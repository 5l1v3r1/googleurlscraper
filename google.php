<?php
function get()
{
	return trim(fgets(STDIN));
}

class GoogleDork
{
	private function getStr($a, $b, $c)
	{
		return @explode($b, @explode($a, $c)[1])[0];
	}
	
	private function parse($data)
	{
		$jum = 0;
		$data = @explode('<div class="KJDcUb"><a class="', $data);
		if((count($data)-1)>1)
		{
			for($a=1;$a<count($data);$a++){
				$datas = $data[$a];
				$url = $this->getStr('href="', '"', $datas);
				$title = $this->getStr('<div class="zlBHuf MUxGbd v0nnCb" aria-level="3" role="heading">', '</div>', $datas);
				$arr[] =  "[ $url ]";
			}
			$status = true;
			$ar = @implode("\n", $arr)."\n";
			$jum += count($data)-1;
		} else {
			$status = false;
			$ar = "";
			$jum += 0;
		}
		return array($status, $ar, $jum);
	}
	
	public function search($query, $start)
	{
		$q = @str_replace("%20", "+", urlencode($query));
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://www.google.com/search?ie=UTF-8&source=android-browser&q=$q&start=$start");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$headers = array();
		$headers[] = 'Host: www.google.com';
		$headers[] = 'Connection: close';
		$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
		$headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 5.1.1; SM-G935FD Build/LMY48Z) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/39.0.0.0 Safari/537.36';
		$headers[] = 'Accept-Language: en-US';
		$headers[] = 'X-Requested-With: com.android.browser';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		curl_close($ch);
		return $this->parse($result);
	}
}
echo "*Query\n\tInput	: ";
$q = get();
echo "*Maks Get Url (0 for get all & Minimum 10)\n\tInput	: ";
$maks = get();
$asw = new GoogleDork;
if($maks == 0){
	$a = 0; $jum = 0;
	while(true){
		$search = $asw->search($q, $a);
		$jum += $search[2];
		echo($search[1]);
		if(!$search[0]) break;
		$a += 10;
		continue;
	}
}else{
	$maks = floor($maks/10);
	$b = 0; $jum = 0;
	for($a=0;$a<$maks;$a++){
		$search = $asw->search($q, $b);
		$jum += $search[2];
		echo($search[1]);
		if(!$search[0]) break;
		$b += 10;
	}
}
echo "\n\n Found $jum Urls.\n";