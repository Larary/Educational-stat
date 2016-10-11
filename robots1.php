<?php
if (isset($_POST['submit'])) {
	$url = 'http://www.'.trim($_POST['url']).'./robots.txt';
	
/* // url файла для проверки на существование
$url = "http://site.com/image.jpg";
$urlHeaders = @get_headers($url);
// проверяем ответ сервера на наличие кода: 200 - ОК
if(strpos($urlHeaders[0], '200')) {
    echo "Файл существует";
} else {
    echo "Файл не найден";
}

// url файла для проверки на существование
$url = "http://site.com/image.jpg";
// открываем файл для чтения
if (@fopen($url, "r")) {
    echo "Файл существует";
} else {
    echo "Файл не найден";
} */

//размер файла robots.txt
//функции get_headers и CURLOPT_HEADER работают не со всеми серверами, в ответе сервера может не быть заголовка Content-Length
/* function remote_filesize($urlSize) {
		$ch = curl_init($urlSize);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		$ok = curl_exec($ch);
		curl_close($ch);
		$head = ob_get_contents();
		 ob_end_clean(); 
		$regex = '/Content-Length:\s([0-9].+?)\s/';
		$count = preg_match($regex, $head, $matches); */
		/* if (isset($matches[1])) {
			return ceil($matches[1]/1024);
		}
		else {
			$headers = get_headers($urlSize, 1);
			return ceil($headers['Content-Length']/1024);
		} */
//		return isset($matches[1]) ? $matches[1] : "unknown";
//	}
//$size = remote_filesize($url);	
/* echo $size; */	
	
	
	/* $headers = get_headers($url, 1);
	if(array_key_exists('Content-Length', $headers)){
		$size = ceil($headers['Content-Length']/1024);
	} */

	
$robots = file($url,FILE_IGNORE_NEW_LINES);
		
$table = "<table><tr><th>№</th><th>Название проверки</th><th>Статус</th><th></th><th>Текущее состояние</th></tr>";
$table .= "<tr><td colspan='5'></td></tr>";		
		
$checkbox_form = "<form action=\"urlencode.php\" name=\"UrlEncode\" method=\"post\">";
$checkbox_form .= "<div style=\"font-family: 'Courier New', Courier, monospace;\">";
$robots_text = array();
foreach ($robots as $key=>$val) {
  $str = explode(": ",$val);
  $robots_text[$key]= $str[0].": ".urldecode($str[1])."<br/>";
 
  if (preg_match("/^(User\-agent|Host|Sitemap)$/",$str[0])){
      $checkbox_form .= "<input type=\"checkbox\" name=\"row_del[]\" value=\"".$key."\" disabled=\"disabled\">".$robots_text[$key];
  } /* else{
      $checkbox_form .= "<input type=\"checkbox\" name=\"row_del[]\" value=\"".$key."\">".$robots_text[$key];
  } */
 
}
$checkbox_form .= "</div>";
$checkbox_form .= "<input type=\"submit\" value=\"Удалить указанные строки из Robots.txt\"/>";
$checkbox_form .= "</form>";
echo $checkbox_form;
	
}
else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//UK"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<form id="Form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<div>
		<div  class="wrapper">
			<span>Введите адрес сайта</span>
			<div class="bg"><input type="text" name="url" class="input"/></div>
		</div>
		<input class="button" type="submit" value="OK" name="submit" />
	</div>
</form>
<?php
}
?>
</body>