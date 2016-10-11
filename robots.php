<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//UK"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
	
</head>
<body>
<?php
if (isset($_POST['submit'])) {
	$url = 'http://www.'.trim($_POST['url']).'./robots.txt';
	
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	
	// проверка наличия файла и код ответа сервера
	if($httpCode == 200) {
		$status1 = 'OK';
		$state1 = 'Файл robots.txt присутствует';
		$recom1 = 'Доработки не требуются';
		$status12 = 'OK';
		$state12 = 'Файл robots.txt отдаёт код ответа сервера 200';
		$recom12 = 'Доработки не требуются';
	} 
	else {
		$status1 = 'Ошибка';
		$state1 = 'Файл robots.txt отсутствует';
		$recom1 = 'Программист: Создать файл robots.txt и разместить его на сайте.';
		$status12 = 'Ошибка';
		$state12 = 'При обращении к файлу robots.txt сервер возвращает код ответа '.$httpCode;
		$recom12 = 'Программист: Файл robots.txt должны отдавать код ответа 200, 
		иначе файл не будет обрабатываться. Необходимо настроить сайт таким образом, 
		чтобы при обращении к файлу robots.txt сервер возвращает код ответа 200';
	}
	
	//проверка наличия и количества директив Host и наличия директивы Sitemap	
	if(strpos($output, 'Host')){
		$status6 = 'OK';
		$state6 = 'Директива Host указана';
		$recom6 = 'Доработки не требуются';
	}	
	else {
		$status6 = 'Ошибка';
		$state6 = 'В файле robots.txt не указана директива Host';
		$recom6 = 'Программист: Для того, чтобы поисковые системы знали, какая версия сайта является основных зеркалом, 
		необходимо прописать адрес основного зеркала в директиве Host. В данный момент это не прописано. 
		Необходимо добавить в файл robots.txt директиву Host. Директива Host задаётся в файле 1 раз, после всех правил.';
	}
	if(strpos($output, 'Sitemap')){
		$status11 = 'OK';
		$state11 = 'Директива Sitemap указана';
		$recom11 = 'Доработки не требуются';
	}
	else {
		$status11 = 'Ошибка';
		$state11 = 'В файле robots.txt не указана директива Sitemap';
		$recom11 = 'Программист: Добавить в файл robots.txt директиву Sitemap.';
	}
	switch (substr_count($output, 'Host')){
		case '0': 
			$status8 = 'Ошибка';
			$state8 = 'Директива Host отсутствует';
			$recom8 = 'См.п.6';
		break;
		case '1': 
			$status8 = 'OK';
			$state8 = 'В файле прописана 1 директива Host';
			$recom8 = 'Доработки не требуются';
		break;
		case '>=2': 
			$status8 = 'Ошибка';
			$state8 = 'В файле прописано несколько директив Host';
			$recom8 = 'Программист: Директива Host должна быть указана в файле только 1 раз. 
			Необходимо удалить все дополнительные директивы Host и оставить только 1, 
			корректную и соответствующую основному зеркалу сайта';
		break;
	}
	//размер файла robots.txt
	//функции get_headers и curl_setopt (параметр CURLOPT_HEADER) работают не со всеми серверами, в ответе сервера может не быть заголовка Content-Length
	
	$size = strlen($output);	
	$size = ceil($size/1024);	
	
	if ($size<=32){
		$status10 = 'OK';
		$state10 = 'Размер файла robots.txt составляет '.$size.' Kb, что находится в пределах допустимой нормы';
		$recom10 = 'Доработки не требуются';
	}	
	else {		
		$status10 = 'OK';
		$state10 = 'Размер файла robots.txt составляет '.$size.' Kb, что превышает допустимую норму';
		$recom10 = 'Программист: Максимально допустимый размер файла robots.txt составляет 32 кб. 
		Необходимо отредактировать файл robots.txt таким образом, чтобы его размер не превышал 32 Кб';
	}
	
	$table_h = "<table><tr><th>№</th><th>Название проверки</th><th>Статус</th><th></th><th>Текущее состояние</th></tr>";
	$empty = "<tr><td colspan='5'></td></tr>";
	$row1 = "<tr><td rowspan='2'>1</td><td rowspan='2'>Проверка наличия файла robots.txt</td><td rowspan='2'>{$status1}</td>
			<td>Состояние</td><td>{$state1}</td></tr><tr><td>Рекомендации</td><td>{$recom1}</td></tr>";
	$row6 = "<tr><td rowspan='2'>6</td><td rowspan='2'>Проверка указания директивы Host</td><td rowspan='2'>{$status6}</td>
			<td>Состояние</td><td>{$state6}</td></tr><tr><td>Рекомендации</td><td>{$recom6}</td></tr>";
	$row8 = "<tr><td rowspan='2'>8</td><td rowspan='2'>Проверка количества директив Host, прописанных в файле</td><td rowspan='2'>{$status8}</td>
			<td>Состояние</td><td>{$state8}</td></tr><tr><td>Рекомендации</td><td>{$recom8}</td></tr>";
	$row10 = "<tr><td rowspan='2'>10</td><td rowspan='2'>Проверка размера файла robots.txt</td><td rowspan='2'>{$status10}</td>
			<td>Состояние</td><td>{$state10}</td></tr><tr><td>Рекомендации</td><td>{$recom10}</td></tr>";
	$row11 = "<tr><td rowspan='2'>11</td><td rowspan='2'>Проверка указания директивы Sitemap</td><td rowspan='2'>{$status11}</td>
			<td>Состояние</td><td>{$state11}</td></tr><tr><td>Рекомендации</td><td>{$recom11}</td></tr>";
	$row12 = "<tr><td rowspan='2'>12</td><td rowspan='2'>Проверка кода ответа сервера для файла robots.txt</td><td rowspan='2'>{$status12}</td>
			<td>Состояние</td><td>{$state12}</td></tr><tr><td>Рекомендации</td><td>{$recom12}</td></tr>";
	$close = "</table>";
	if($httpCode == 200) {
		$table = $table_h.$empty.$row1.$empty.$row6.$empty.$row8.$empty.$row10.$empty.$row11.$empty.$row12.$close; 
	}
	else {
		$table = $table_h.$empty.$row1.$empty.$row12.$close;
	}
	echo $table;
}
else {
?>
	<h3 class="center">Аудит файла robots.txt</h3>
	<form class="center" id="Form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<label for="url">Введите адрес сайта:</label>
		<input type="text" id="url" name="url" class="input"/><br />
		<h5>Формат адреса сайта: mysite.com</h5>
		<input class="button" type="submit" value="OK" name="submit" />
	</form>
<?php
}
?>
</body>