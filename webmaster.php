<?php
error_reporting(E_ERROR | E_PARSE);
header('Content-Type: text/html; charset=utf-8');
set_time_limit(0);//
    function rudate($format, $timestamp = 0, $nominative_month = false)
    {
    if(!$timestamp) $timestamp = time();
    elseif(!preg_match("/^[0-9]+$/", $timestamp)) $timestamp = strtotime($timestamp);
    $F = $nominative_month ? array(1=>"Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря") : array(1=>"Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
    $M = array(1=>"Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек");
    $l = array("Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота");
    $D = array("Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб");
     
    $format = str_replace("F", $F[date("n", $timestamp)], $format);
    $format = str_replace("M", $M[date("n", $timestamp)], $format);
    $format = str_replace("l", $l[date("w", $timestamp)], $format);
    $format = str_replace("D", $D[date("w", $timestamp)], $format);
    return date($format, $timestamp);
    }
function razmer($v)
{
$giga=1024*1024*1024;
$mega=1024*1024;
$kilo=1024;
if($v>$giga)
{
return round($v/$giga,2).' Gb';
}
elseif($v>$mega)
{
return round($v/$mega,2).' Mb';
}
elseif($v>$kilo)
{
return round($v/$kilo,2).' Kb';
}
else
{
return $v.' b';
}
}
//Создание соединения с БД
$filename='config_search_kuz.php';//Название файла с конфигурацией
if(file_exists($filename)){
include $filename;
if(strlen($_POST['password'])>0 or strlen($_COOKIE['password_search_kuz'])>0)
{
if(strlen($password)>0 and ($_POST['password']==$password or $_COOKIE['password_search_kuz']==$password))
{
$var=setcookie("password_search_kuz",$password,time()+30000000,'/');
if($var===true)
{

}
else
{
echo 'Мы не смогли установить  cookies в вашем бруаузере ,или наличие пробелов или переводов строк в конфигурационном файле скрипта';
};
}
else
{
echo '
<meta charset="utf-8">
Пароль не подходит.<br>
Если вы забыли пароль отредактируйте конфигурационный файл в папке с этим скриптом.<br>
Пароль желателен в виде строки от 32 символов,все что меньше можно быстро перебрать<br>
';
exit;
}
}
else
{
echo '
<meta charset="utf-8"> <form action="" method="POST">
<b>Авторизация</b><br>
Введите пароль на скрипт:
<input type="password" name="password" size="40">
<input type="submit" value="Ввести пароль">
</form>
';
exit;
}

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/dbconn.php'))
{
	include $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/dbconn.php';
	$mysql_host=$DBHost;
	$mysql_login=$DBLogin;
	$mysql_password=$DBPassword;
	$mysql_db=$DBName;
}

$link = mysql_connect($mysql_host,$mysql_login,$mysql_password);//Подключение к базе данных
$res_link=mysql_select_db($mysql_db,$link);
mysql_query("set names utf8");

}
else
{
if($_POST['parol'])
{
$file=
'<?php
define("Ogon","Ogon");
$password="'.trim($_POST['password']).'";
?>';
$fp = fopen($filename, 'w');
fwrite($fp,$file);
fclose($fp);
}
else
{
function generate_password($number)
{
$arr = array('a','b','c','d','e','f',
'g','h','i','j','k','l',
'm','n','o','p','r','s',
't','u','v','x','y','z',
'A','B','C','D','E','F',
'G','H','I','J','K','L',
'M','N','O','P','R','S',
'T','U','V','X','Y','Z',
'1','2','3','4','5','6',
'7','8','9','0');
// Генерируем пароль
$pass = "";
for($i = 0; $i < $number; $i++)
{
// Вычисляем случайный индекс массива
$index = rand(0, count($arr) - 1);
$pass .= $arr[$index];
}
return $pass;
}

?>
<meta charset="utf-8">


<table>
<tr><td>Скрипт быстрого поиска и редактирования не сайте</td></tr>
<tr>
<td>Пароль на этот скрипт:</td>
<td>
<?php $password=generate_password(32);?>
<form action="" method="POST">
<input type="hidden" name="password" value="<?php echo $password?>">
<i><?php echo htmlspecialchars($password);?></i>&nbsp;<input type="checkbox" name="parol" value="1">&nbsp;Я сохранил этот пароль
<br>
<input type="submit" value="Установить пароль">
</form>
</td>
</tr>
<tr>
<td>Желательно поставить на этот скрипт через FTP-сервер CHMOD <b>777</b></td>
</tr>
</table>
<?php
exit;
}
}


if($_POST['ajax']=='y')
{
//Удаление файла
if(strlen($_POST['del'])>0)
{
echo '<meta charset="utf-8">';
$path=$_POST['del'];
$res=unlink($path);	
if($res===true)
{
echo 'Файл '.$path.' удален.';
}
else
{
echo 'По какой-то причине у этого скрипта нет прав на удаление этого файла '.$path.'.Раcширьте права скрипта ';
};
exit;
}
if(strlen($_POST['path_zamen'])>0 and strlen($_POST['chto'])>0)
{

$filename=$_POST['path_zamen'];
$obj=$filename;
$search_string=$_POST['chto'];
$search_string_zam=$_POST['nachto'];
$handle = fopen($filename,"r");
$text=fread($handle,filesize($filename));
fclose($handle);
$kol_vx=substr_count($text,$search_string);
$text2=str_replace($_POST['chto'],$_POST['nachto'],$text);	
@mkdir('search_backups_47');
if (!copy($obj,'search_backups_47/'.basename($obj))) {}
$fp = fopen($obj, 'w');
fwrite($fp,$text2);
fclose($fp);
$vtext='В файле '.$obj.' все строки '.$search_string.' заменены на '.$search_string_zam.'.
Количество замен '.$kol_vx.' .Копия измененного файла сохраненна в папке search_backups_47';
echo $vtext;
$ios=true;
}
//data: {ajax:'y',path_zamen:path,chto:chto2,nachto:nachto2}
//Переименование файла
if(strlen($_POST['rename_old'])>0 && strlen($_POST['rename_new'])>0)
{
$path=$_POST['rename_old'];
if(is_file($path)===true)
{
$res=rename($path,$_POST['rename_new']);
if($res===true)
{
echo 'Файл '.$path.' переименован в '.$_POST['rename_new'];
}
else
{
echo 'По какой-то причине у этого скрипта нет прав на переименование других файлов этого файла.
Раcширьте права этого скрипта
';
};
exit;
}
else
{
echo 'Такого файла не существует - <small><b>'.$path.'</b></small>';
}
}
//Изменение CHMOD
if(strlen($_POST['path3'])>0 && strlen($_POST['chmod'])>0)
{
$res=chmod($_POST['path3'],'0'.$_POST['chmod']);
if($res===true)
{
echo 'CHMOD файла был успешно изменен';
}
else
{
echo 'По каким-то причинам скрипт не смог изменить chmod на значение '.$_POST['chmod'].' для файла '.$_POST['path3'].'
.Проверьте chmod самого скрипта и папки в которой он находиться.
';
}
}
//Изменение времени создания
//Изменение текста файла
//fastred
if(strlen($_POST['path2'])>0 && strlen($_POST['pos'])>0 && strlen($_POST['old_raz'])>0 && strlen($_POST['text'])>0)
{
//data: {ajax:'y',path2:path2_a,pos:pos_a,old_raz:old_raz_a,text:text_a}
$filename=$_POST['path2'];
$text_zam=$_POST['text'];
$pos=$_POST['pos'];
$old_raz=$_POST['old_raz'];

$handle = fopen($filename,"rb");
$text=fread($handle,filesize($filename));
fclose($handle);
if($text!==false)
{
if($pos>0)
{
$nach=mb_substr($text,0,$pos,'utf-8');
}
else
{
$nach='';
};
$kon=mb_substr($text,$pos+$old_raz,10000000000000000,'utf-8');
$ftext=$nach.$text_zam.$kon;

//
$obj=$filename;
@mkdir('search_backups_47');
if (!copy($obj,'search_backups_47/'.basename($obj))) {}

$fp = fopen($filename,'w');
fwrite($fp,$ftext);
fclose($fp);

$vtext='Файл '.$obj.' отредактирован.
Копия измененного файла сохранена в папке /search_backups_47/';
echo $vtext;
$ios=true;
}
else
{
echo 'Извините ,по неизвестным причнам файл не может быть прочтен или файл пустой.';
};
}
//Изменение текста поля
if(strlen($_POST['table'])>0 and strlen($_POST['row'])>0
and strlen($_POST['okr'])>0 and (strlen($_POST['znach'])>0 or strlen($_POST['zamen'])>0))
{
$table=$_POST['table'];
$row=$_POST['row'];
$okr=unserialize(base64_decode($_POST['okr']));
$string='';
foreach ($okr as $k=>$v)
{
if($k!=$row && is_numeric($k)===false && strlen($v)>0)
{
$string.=' AND '.$k.'="'.mysql_real_escape_string($v).'"';
}
}
$string=substr($string,5);
if(strlen($_POST['zamen'])>0)
{
$q1=mysql_query('SELECT '.$row.' FROM '.$table.' WHERE '.$string);
$r1=mysql_fetch_array($q1);
$valuer=$r1[$row];
$_POST['znach']=str_replace($_POST['zamen'],$_POST['zamen2'],$valuer);
}
//echo 'UPDATE '.$table.' SET '.$row.'="'.mysql_real_escape_string($_POST['znach']).'" WHERE '.$string;
$query=mysql_query('UPDATE '.$table.' SET '.$row.'="'.mysql_real_escape_string($_POST['znach']).'" WHERE '.$string);
$number=mysql_affected_rows();
if($query===true)
{
echo 'Изменения вступили в силу .Изменения затронули '.$number.' строк.';
}
else
{
echo 'UPDATE '.$table.' SET '.$row.'="'.mysql_real_escape_string($_POST['znach']).'" WHERE '.$string;
echo 'По какой то причине строки не были отредактированы .Проверьте правильность mysql-подключения
или напишите мне на e-mail kuzminruslan@mail.ru
';
}
}
exit;
}
//Переименование
//Изменение chmod



//Баг с папками privet и privet3
if(count($_POST)>0)
{
$Def_arr=$_POST;
unset($Def_arr['parol']);
unset($Def_arr['file_text']);
$value=serialize($Def_arr);

if(isset($_POST['sbros']))
{
$_POST=array('');
$value='';
$flag_neotpr=true;
};
setcookie("SearchCookieDefaultValues", $value, time()+3600000);
}
elseif($_COOKIE['SearchCookieDefaultValues'])
{
$_POST=unserialize($_COOKIE['SearchCookieDefaultValues']);
$flag_neotpr=true;
}

if(isset($_GET['path']))$_POST['path']=$_GET['path'];
if(isset($_POST['file_text']) && isset($_POST['path']))
{
$fp = fopen($_POST['path'], 'w');
fwrite($fp,$_POST['file_text']);
fclose($fp);
$_GET['fopen']=$_POST['path'];
}

if(isset($_GET['fopen']))
{
if($_GET['kod']=='utf-8')echo '<meta charset="utf-8">';
if($_GET['kod']=='windows-1251')echo '<meta charset="windows-1251">';
$obj=$_GET['fopen'];
$handle = fopen($obj,"r");
$text=fread($handle,filesize($obj));
fclose($handle);
$array_ext=explode(".",basename($obj));
if(count($array_ext)>1)
{
$ext_open=end($array_ext);
}
if($ext_open=='html' or $ext_open=='htm')
{
$mode='text/html';
}
elseif($ext_open=='php' or $ext_open=='php4' or $ext_open=='php5' or $ext_open=='php6')
{
$mode='text/x-php';
}
elseif($ext_open=='css')
{
$mode='css';
}
elseif($ext_open=='xml')
{
$mode='xml';
}
else
{
$mode=$ext_open;
}

echo '
<!doctype html>

<title>Редактор файлов</title>
<link href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=" rel="icon" type="image/x-icon" />

<meta charset="utf-8"/>
<!--Подсветка синтаксиса -->
<script src="http://juliabot.com/hosted_library/codemirror/lib/codemirror.js"></script>
<link rel="stylesheet" href="http://juliabot.com/hosted_library/codemirror/lib/codemirror.css">
<script src="http://juliabot.com/hosted_library/codemirror/mode/php/php.js"></script>
<link rel=stylesheet href="http://juliabot.com/hosted_library/codemirror/doc/docs.css">
<script src="http://juliabot.com/hosted_library/codemirror/lib/codemirror.js"></script>
<script src="http://juliabot.com/hosted_library/codemirror/mode/xml/xml.js"></script>
<script src="http://juliabot.com/hosted_library/codemirror/mode/javascript/javascript.js"></script>
<script src="http://juliabot.com/hosted_library/codemirror/mode/css/css.js"></script>
<script src="http://juliabot.com/hosted_library/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="http://juliabot.com/hosted_library/codemirror/addon/edit/matchbrackets.js"></script>


<style>
.CodeMirror { height: 95%; border: 1px solid #ddd; }
</style>
<b>Строка поиска</b>:'.htmlspecialchars($_GET['search_string']).' &nbsp;&nbsp;&nbsp;&nbsp; Файл открыт в кодировке '.htmlspecialchars($_GET['kod']).'
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="text" size="30" name="" value="'.addslashes(htmlspecialchars($_GET['search_string'])).'">
<input type="submit" value="Найти далее"><input type="submit" value="Найти выше">&nbsp;&nbsp;
<small>Перейти на <a  target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'">'.$_SERVER['HTTP_HOST'].'</a>&nbsp;
<style>
a
{
    color: #A21313;
    text-decoration: none;
}
td,a {
 word-wrap: break-word !important;
}
</style>
&nbsp;&nbsp;&nbsp;&nbsp;

</small>
<br>
<small>
<b>Файл:</b>'.htmlspecialchars($_GET['fopen']).' <br>Перекодировать в <input type="radio" name="1"> utf-8 <input type="radio" name="1"> windows-1251 &nbsp;&nbsp;<input type="submit" value="Сделать backup">
</small>
<br>
<form action="" method="POST">
<input type="hidden" name="path" value="'.$_GET['fopen'].'">
<textarea id="file_text" name="file_text" style="width:100%;height:80%;">'.htmlspecialchars($text).'</textarea>
<script>
  window.onload = function(){
var myTextarea=document.getElementById("file_text");
var editor = CodeMirror.fromTextArea(myTextarea, {
    mode: "'.$mode.'",
  });
  
   }
</script>
<br>
<input type="submit" value="Сохранить изменения">
</form>
';
exit;
}

?>

<meta charset="utf-8">
<link href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAwElEQVQ4jc3SMUoDURSF4W+GgKiQQgjaWJglKJIuS7DJDuyskl6rKawsLALaWDhhAmlszFayHMHiTVK992bAxlPe/5zD5XJJq8ywpI5RYY1PNFjhtk94jO+I+QSvWOTCJb5wnvG8YJKCM9x3bHiETa79oqOAcJdBDNQ9wvCMyxiocNWjoEERA1M8doRH+MgZ3nCdYAPhN6Lr7zUUbjHHaTsrcIMtdnjo2BLc4V34wBpPOGvDP31LUvpfJcu/FBz0C4cNG5riwh3/AAAAAElFTkSuQmCC" rel="icon" type="image/x-icon" />

<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>



<!--Table sorter-->
<script>
(function($){$.extend({tablesorter:new function(){var parsers=[],widgets=[];this.defaults={cssHeader:"header",cssAsc:"headerSortUp",cssDesc:"headerSortDown",sortInitialOrder:"asc",sortMultiSortKey:"shiftKey",sortForce:null,sortAppend:null,textExtraction:"simple",parsers:{},widgets:[],widgetZebra:{css:["even","odd"]},headers:{},widthFixed:false,cancelSelection:true,sortList:[],headerList:[],dateFormat:"us",decimal:'.',debug:false};function benchmark(s,d){log(s+","+(new Date().getTime()-d.getTime())+"ms");}this.benchmark=benchmark;function log(s){if(typeof console!="undefined"&&typeof console.debug!="undefined"){console.log(s);}else{alert(s);}}function buildParserCache(table,$headers){if(table.config.debug){var parsersDebug="";}var rows=table.tBodies[0].rows;if(table.tBodies[0].rows[0]){var list=[],cells=rows[0].cells,l=cells.length;for(var i=0;i<l;i++){var p=false;if($.metadata&&($($headers[i]).metadata()&&$($headers[i]).metadata().sorter)){p=getParserById($($headers[i]).metadata().sorter);}else if((table.config.headers[i]&&table.config.headers[i].sorter)){p=getParserById(table.config.headers[i].sorter);}if(!p){p=detectParserForColumn(table,cells[i]);}if(table.config.debug){parsersDebug+="column:"+i+" parser:"+p.id+"\n";}list.push(p);}}if(table.config.debug){log(parsersDebug);}return list;};function detectParserForColumn(table,node){var l=parsers.length;for(var i=1;i<l;i++){if(parsers[i].is($.trim(getElementText(table.config,node)),table,node)){return parsers[i];}}return parsers[0];}function getParserById(name){var l=parsers.length;for(var i=0;i<l;i++){if(parsers[i].id.toLowerCase()==name.toLowerCase()){return parsers[i];}}return false;}function buildCache(table){if(table.config.debug){var cacheTime=new Date();}var totalRows=(table.tBodies[0]&&table.tBodies[0].rows.length)||0,totalCells=(table.tBodies[0].rows[0]&&table.tBodies[0].rows[0].cells.length)||0,parsers=table.config.parsers,cache={row:[],normalized:[]};for(var i=0;i<totalRows;++i){var c=table.tBodies[0].rows[i],cols=[];cache.row.push($(c));for(var j=0;j<totalCells;++j){cols.push(parsers[j].format(getElementText(table.config,c.cells[j]),table,c.cells[j]));}cols.push(i);cache.normalized.push(cols);cols=null;};if(table.config.debug){benchmark("Building cache for "+totalRows+" rows:",cacheTime);}return cache;};function getElementText(config,node){if(!node)return"";var t="";if(config.textExtraction=="simple"){if(node.childNodes[0]&&node.childNodes[0].hasChildNodes()){t=node.childNodes[0].innerHTML;}else{t=node.innerHTML;}}else{if(typeof(config.textExtraction)=="function"){t=config.textExtraction(node);}else{t=$(node).text();}}return t;}function appendToTable(table,cache){if(table.config.debug){var appendTime=new Date()}var c=cache,r=c.row,n=c.normalized,totalRows=n.length,checkCell=(n[0].length-1),tableBody=$(table.tBodies[0]),rows=[];for(var i=0;i<totalRows;i++){rows.push(r[n[i][checkCell]]);if(!table.config.appender){var o=r[n[i][checkCell]];var l=o.length;for(var j=0;j<l;j++){tableBody[0].appendChild(o[j]);}}}if(table.config.appender){table.config.appender(table,rows);}rows=null;if(table.config.debug){benchmark("Rebuilt table:",appendTime);}applyWidget(table);setTimeout(function(){$(table).trigger("sortEnd");},0);};function buildHeaders(table){if(table.config.debug){var time=new Date();}var meta=($.metadata)?true:false,tableHeadersRows=[];for(var i=0;i<table.tHead.rows.length;i++){tableHeadersRows[i]=0;};$tableHeaders=$("thead th",table);$tableHeaders.each(function(index){this.count=0;this.column=index;this.order=formatSortingOrder(table.config.sortInitialOrder);if(checkHeaderMetadata(this)||checkHeaderOptions(table,index))this.sortDisabled=true;if(!this.sortDisabled){$(this).addClass(table.config.cssHeader);}table.config.headerList[index]=this;});if(table.config.debug){benchmark("Built headers:",time);log($tableHeaders);}return $tableHeaders;};function checkCellColSpan(table,rows,row){var arr=[],r=table.tHead.rows,c=r[row].cells;for(var i=0;i<c.length;i++){var cell=c[i];if(cell.colSpan>1){arr=arr.concat(checkCellColSpan(table,headerArr,row++));}else{if(table.tHead.length==1||(cell.rowSpan>1||!r[row+1])){arr.push(cell);}}}return arr;};function checkHeaderMetadata(cell){if(($.metadata)&&($(cell).metadata().sorter===false)){return true;};return false;}function checkHeaderOptions(table,i){if((table.config.headers[i])&&(table.config.headers[i].sorter===false)){return true;};return false;}function applyWidget(table){var c=table.config.widgets;var l=c.length;for(var i=0;i<l;i++){getWidgetById(c[i]).format(table);}}function getWidgetById(name){var l=widgets.length;for(var i=0;i<l;i++){if(widgets[i].id.toLowerCase()==name.toLowerCase()){return widgets[i];}}};function formatSortingOrder(v){if(typeof(v)!="Number"){i=(v.toLowerCase()=="desc")?1:0;}else{i=(v==(0||1))?v:0;}return i;}function isValueInArray(v,a){var l=a.length;for(var i=0;i<l;i++){if(a[i][0]==v){return true;}}return false;}function setHeadersCss(table,$headers,list,css){$headers.removeClass(css[0]).removeClass(css[1]);var h=[];$headers.each(function(offset){if(!this.sortDisabled){h[this.column]=$(this);}});var l=list.length;for(var i=0;i<l;i++){h[list[i][0]].addClass(css[list[i][1]]);}}function fixColumnWidth(table,$headers){var c=table.config;if(c.widthFixed){var colgroup=$('<colgroup>');$("tr:first td",table.tBodies[0]).each(function(){colgroup.append($('<col>').css('width',$(this).width()));});$(table).prepend(colgroup);};}function updateHeaderSortCount(table,sortList){var c=table.config,l=sortList.length;for(var i=0;i<l;i++){var s=sortList[i],o=c.headerList[s[0]];o.count=s[1];o.count++;}}function multisort(table,sortList,cache){if(table.config.debug){var sortTime=new Date();}var dynamicExp="var sortWrapper = function(a,b) {",l=sortList.length;for(var i=0;i<l;i++){var c=sortList[i][0];var order=sortList[i][1];var s=(getCachedSortType(table.config.parsers,c)=="text")?((order==0)?"sortText":"sortTextDesc"):((order==0)?"sortNumeric":"sortNumericDesc");var e="e"+i;dynamicExp+="var "+e+" = "+s+"(a["+c+"],b["+c+"]); ";dynamicExp+="if("+e+") { return "+e+"; } ";dynamicExp+="else { ";}var orgOrderCol=cache.normalized[0].length-1;dynamicExp+="return a["+orgOrderCol+"]-b["+orgOrderCol+"];";for(var i=0;i<l;i++){dynamicExp+="}; ";}dynamicExp+="return 0; ";dynamicExp+="}; ";eval(dynamicExp);cache.normalized.sort(sortWrapper);if(table.config.debug){benchmark("Sorting on "+sortList.toString()+" and dir "+order+" time:",sortTime);}return cache;};function sortText(a,b){return((a<b)?-1:((a>b)?1:0));};function sortTextDesc(a,b){return((b<a)?-1:((b>a)?1:0));};function sortNumeric(a,b){return a-b;};function sortNumericDesc(a,b){return b-a;};function getCachedSortType(parsers,i){return parsers[i].type;};this.construct=function(settings){return this.each(function(){if(!this.tHead||!this.tBodies)return;var $this,$document,$headers,cache,config,shiftDown=0,sortOrder;this.config={};config=$.extend(this.config,$.tablesorter.defaults,settings);$this=$(this);$headers=buildHeaders(this);this.config.parsers=buildParserCache(this,$headers);cache=buildCache(this);var sortCSS=[config.cssDesc,config.cssAsc];fixColumnWidth(this);$headers.click(function(e){$this.trigger("sortStart");var totalRows=($this[0].tBodies[0]&&$this[0].tBodies[0].rows.length)||0;if(!this.sortDisabled&&totalRows>0){var $cell=$(this);var i=this.column;this.order=this.count++%2;if(!e[config.sortMultiSortKey]){config.sortList=[];if(config.sortForce!=null){var a=config.sortForce;for(var j=0;j<a.length;j++){if(a[j][0]!=i){config.sortList.push(a[j]);}}}config.sortList.push([i,this.order]);}else{if(isValueInArray(i,config.sortList)){for(var j=0;j<config.sortList.length;j++){var s=config.sortList[j],o=config.headerList[s[0]];if(s[0]==i){o.count=s[1];o.count++;s[1]=o.count%2;}}}else{config.sortList.push([i,this.order]);}};setTimeout(function(){setHeadersCss($this[0],$headers,config.sortList,sortCSS);appendToTable($this[0],multisort($this[0],config.sortList,cache));},1);return false;}}).mousedown(function(){if(config.cancelSelection){this.onselectstart=function(){return false};return false;}});$this.bind("update",function(){this.config.parsers=buildParserCache(this,$headers);cache=buildCache(this);}).bind("sorton",function(e,list){$(this).trigger("sortStart");config.sortList=list;var sortList=config.sortList;updateHeaderSortCount(this,sortList);setHeadersCss(this,$headers,sortList,sortCSS);appendToTable(this,multisort(this,sortList,cache));}).bind("appendCache",function(){appendToTable(this,cache);}).bind("applyWidgetId",function(e,id){getWidgetById(id).format(this);}).bind("applyWidgets",function(){applyWidget(this);});if($.metadata&&($(this).metadata()&&$(this).metadata().sortlist)){config.sortList=$(this).metadata().sortlist;}if(config.sortList.length>0){$this.trigger("sorton",[config.sortList]);}applyWidget(this);});};this.addParser=function(parser){var l=parsers.length,a=true;for(var i=0;i<l;i++){if(parsers[i].id.toLowerCase()==parser.id.toLowerCase()){a=false;}}if(a){parsers.push(parser);};};this.addWidget=function(widget){widgets.push(widget);};this.formatFloat=function(s){var i=parseFloat(s);return(isNaN(i))?0:i;};this.formatInt=function(s){var i=parseInt(s);return(isNaN(i))?0:i;};this.isDigit=function(s,config){var DECIMAL='\\'+config.decimal;var exp='/(^[+]?0('+DECIMAL+'0+)?$)|(^([-+]?[1-9][0-9]*)$)|(^([-+]?((0?|[1-9][0-9]*)'+DECIMAL+'(0*[1-9][0-9]*)))$)|(^[-+]?[1-9]+[0-9]*'+DECIMAL+'0+$)/';return RegExp(exp).test($.trim(s));};this.clearTableBody=function(table){if($.browser.msie){function empty(){while(this.firstChild)this.removeChild(this.firstChild);}empty.apply(table.tBodies[0]);}else{table.tBodies[0].innerHTML="";}};}});$.fn.extend({tablesorter:$.tablesorter.construct});var ts=$.tablesorter;ts.addParser({id:"text",is:function(s){return true;},format:function(s){return $.trim(s.toLowerCase());},type:"text"});ts.addParser({id:"digit",is:function(s,table){var c=table.config;return $.tablesorter.isDigit(s,c);},format:function(s){return $.tablesorter.formatFloat(s);},type:"numeric"});ts.addParser({id:"currency",is:function(s){return/^[A?$aВм?.]/.test(s);},format:function(s){return $.tablesorter.formatFloat(s.replace(new RegExp(/[^0-9.]/g),""));},type:"numeric"});ts.addParser({id:"ipAddress",is:function(s){return/^\d{2,3}[\.]\d{2,3}[\.]\d{2,3}[\.]\d{2,3}$/.test(s);},format:function(s){var a=s.split("."),r="",l=a.length;for(var i=0;i<l;i++){var item=a[i];if(item.length==2){r+="0"+item;}else{r+=item;}}return $.tablesorter.formatFloat(r);},type:"numeric"});ts.addParser({id:"url",is:function(s){return/^(https?|ftp|file):\/\/$/.test(s);},format:function(s){return jQuery.trim(s.replace(new RegExp(/(https?|ftp|file):\/\//),''));},type:"text"});ts.addParser({id:"isoDate",is:function(s){return/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(s);},format:function(s){return $.tablesorter.formatFloat((s!="")?new Date(s.replace(new RegExp(/-/g),"/")).getTime():"0");},type:"numeric"});ts.addParser({id:"percent",is:function(s){return/\%$/.test($.trim(s));},format:function(s){return $.tablesorter.formatFloat(s.replace(new RegExp(/%/g),""));},type:"numeric"});ts.addParser({id:"usLongDate",is:function(s){return s.match(new RegExp(/^[A-Za-z]{3,10}\.? [0-9]{1,2}, ([0-9]{4}|'?[0-9]{2}) (([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(AM|PM)))$/));},format:function(s){return $.tablesorter.formatFloat(new Date(s).getTime());},type:"numeric"});ts.addParser({id:"shortDate",is:function(s){return/\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}/.test(s);},format:function(s,table){var c=table.config;s=s.replace(/\-/g,"/");if(c.dateFormat=="us"){s=s.replace(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})/,"$3/$1/$2");}else if(c.dateFormat=="uk"){s=s.replace(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})/,"$3/$2/$1");}else if(c.dateFormat=="dd/mm/yy"||c.dateFormat=="dd-mm-yy"){s=s.replace(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2})/,"$1/$2/$3");}return $.tablesorter.formatFloat(new Date(s).getTime());},type:"numeric"});ts.addParser({id:"time",is:function(s){return/^(([0-2]?[0-9]:[0-5][0-9])|([0-1]?[0-9]:[0-5][0-9]\s(am|pm)))$/.test(s);},format:function(s){return $.tablesorter.formatFloat(new Date("2000/01/01 "+s).getTime());},type:"numeric"});ts.addParser({id:"metadata",is:function(s){return false;},format:function(s,table,cell){var c=table.config,p=(!c.parserMetadataName)?'sortValue':c.parserMetadataName;return $(cell).metadata()[p];},type:"numeric"});ts.addWidget({id:"zebra",format:function(table){if(table.config.debug){var time=new Date();}$("tr:visible",table.tBodies[0]).filter(':even').removeClass(table.config.widgetZebra.css[1]).addClass(table.config.widgetZebra.css[0]).end().filter(':odd').removeClass(table.config.widgetZebra.css[0]).addClass(table.config.widgetZebra.css[1]);if(table.config.debug){$.tablesorter.benchmark("Applying Zebra widget",time);}}});})(jQuery);
</script>

<script type="text/javascript">
function FocusOnInput()
{
document.getElementById("input").focus();
}
</script>
<script>
$(document).ready(function()
{
$("#myTable").tablesorter( {sortList: [[0,0], [1,0]]} );
$("#mytable2").tablesorter( {sortList: [[0,0], [1,0]]} );
$(".datepicker").datepicker();
$(".datepicker").datepicker("option","dateFormat","d.m.y");
}
);
</script>
<script>
//ajax']=='y') 'path3'])>0 && strlen($_POST['chmod']
function changechmod(path3_a,chmod_a)
{
var chmod=document.getElementById('chmod_'+chmod_a+'').value;
$.ajax({
type: "POST",
url: "webmaster.php",
data: {ajax:'y',path3:path3_a,chmod:chmod}
})
.done(function( msg ) {
alert( "Ответ: " + msg );
});
}
function udalit(path)
{
$.ajax({
type: "POST",
url: "webmaster.php",
data: {ajax:'y',del:path}
})
.done(function( msg ) {
alert( "Ответ: " + msg );
});
}
function fastred(path2_a,pos_a,old_raz_a,text_a,k)
{
$.ajax({
type: "POST",
url: "webmaster.php",
data: {ajax:'y',path2:path2_a,pos:pos_a,old_raz:old_raz_a,text:text_a}
})
.done(function( msg ) {
$("[ssil_"+k+"]").remove();
alert( "Ответ: " + msg );
});
}
function zamenred(path,chto2,nachto2,k)
{
$.ajax({
type: "POST",
url: "webmaster.php",
data: {ajax:'y',path_zamen:path,chto:chto2,nachto:nachto2}
})
.done(function( msg ) {
$("[ssil2_"+k+"]").remove();
alert( "Ответ: " + msg );
});
}
function zamenred_m(table_2,row_2,okr_2,zamen_2,zamen_3)
{
$.ajax({
type: "POST",
url: "webmaster.php",
data: {ajax:'y',table:table_2,row:row_2,okr:okr_2,zamen:zamen_2,zamen2:zamen_3}
})
.done(function( msg ) {
alert( "Ответ: " + msg );
});
}
function fastred_m(table_2,row_2,okr_2,znach_2)
{
$.ajax({
type: "POST",
url: "webmaster.php",
data: {ajax:'y',table:table_2,row:row_2,okr:okr_2,znach:znach_2}
})
.done(function( msg ) {
alert( "Ответ: " + msg );
});
}

// Simple Set Clipboard System
// Author: Joseph Huckaby

var ZeroClipboard = {
	
	version: "1.0.7",
	clients: {}, // registered upload clients on page, indexed by id
	moviePath: 'ZeroClipboard.swf', // URL to movie
	nextId: 1, // ID of next movie
	
	$: function(thingy) {
		// simple DOM lookup utility function
		if (typeof(thingy) == 'string') thingy = document.getElementById(thingy);
		if (!thingy.addClass) {
			// extend element with a few useful methods
			thingy.hide = function() { this.style.display = 'none'; };
			thingy.show = function() { this.style.display = ''; };
			thingy.addClass = function(name) { this.removeClass(name); this.className += ' ' + name; };
			thingy.removeClass = function(name) {
				var classes = this.className.split(/\s+/);
				var idx = -1;
				for (var k = 0; k < classes.length; k++) {
					if (classes[k] == name) { idx = k; k = classes.length; }
				}
				if (idx > -1) {
					classes.splice( idx, 1 );
					this.className = classes.join(' ');
				}
				return this;
			};
			thingy.hasClass = function(name) {
				return !!this.className.match( new RegExp("\\s*" + name + "\\s*") );
			};
		}
		return thingy;
	},
	
	setMoviePath: function(path) {
		// set path to ZeroClipboard.swf
		this.moviePath = path;
	},
	
	dispatch: function(id, eventName, args) {
		// receive event from flash movie, send to client		
		var client = this.clients[id];
		if (client) {
			client.receiveEvent(eventName, args);
		}
	},
	
	register: function(id, client) {
		// register new client to receive events
		this.clients[id] = client;
	},
	
	getDOMObjectPosition: function(obj, stopObj) {
		// get absolute coordinates for dom element
		var info = {
			left: 0, 
			top: 0, 
			width: obj.width ? obj.width : obj.offsetWidth, 
			height: obj.height ? obj.height : obj.offsetHeight
		};

		while (obj && (obj != stopObj)) {
			info.left += obj.offsetLeft;
			info.top += obj.offsetTop;
			obj = obj.offsetParent;
		}

		return info;
	},
	
	Client: function(elem) {
		// constructor for new simple upload client
		this.handlers = {};
		
		// unique ID
		this.id = ZeroClipboard.nextId++;
		this.movieId = 'ZeroClipboardMovie_' + this.id;
		
		// register client with singleton to receive flash events
		ZeroClipboard.register(this.id, this);
		
		// create movie
		if (elem) this.glue(elem);
	}
};


ZeroClipboard.Client.prototype = {
	
	id: 0, // unique ID for us
	ready: false, // whether movie is ready to receive events or not
	movie: null, // reference to movie object
	clipText: '', // text to copy to clipboard
	handCursorEnabled: true, // whether to show hand cursor, or default pointer cursor
	cssEffects: true, // enable CSS mouse effects on dom container
	handlers: null, // user event handlers
	
	glue: function(elem, appendElem, stylesToAdd) {
		// glue to DOM element
		// elem can be ID or actual DOM element object
		this.domElement = ZeroClipboard.$(elem);
		
		// float just above object, or zIndex 99 if dom element isn't set
		var zIndex = 99;
		if (this.domElement.style.zIndex) {
			zIndex = parseInt(this.domElement.style.zIndex, 10) + 1;
		}
		
		if (typeof(appendElem) == 'string') {
			appendElem = ZeroClipboard.$(appendElem);
		}
		else if (typeof(appendElem) == 'undefined') {
			appendElem = document.getElementsByTagName('body')[0];
		}
		
		// find X/Y position of domElement
		var box = ZeroClipboard.getDOMObjectPosition(this.domElement, appendElem);
		
		// create floating DIV above element
		this.div = document.createElement('div');
		var style = this.div.style;
		style.position = 'absolute';
		style.left = '' + box.left + 'px';
		style.top = '' + box.top + 'px';
		style.width = '' + box.width + 'px';
		style.height = '' + box.height + 'px';
		style.zIndex = zIndex;
		
		if (typeof(stylesToAdd) == 'object') {
			for (addedStyle in stylesToAdd) {
				style[addedStyle] = stylesToAdd[addedStyle];
			}
		}
		
		// style.backgroundColor = '#f00'; // debug
		
		appendElem.appendChild(this.div);
		
		this.div.innerHTML = this.getHTML( box.width, box.height );
	},
	
	getHTML: function(width, height) {
		// return HTML for movie
		var html = '';
		var flashvars = 'id=' + this.id + 
			'&width=' + width + 
			'&height=' + height;
			
		if (navigator.userAgent.match(/MSIE/)) {
			// IE gets an OBJECT tag
			var protocol = location.href.match(/^https/i) ? 'https://' : 'http://';
			html += '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="'+protocol+'download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="'+width+'" height="'+height+'" id="'+this.movieId+'" align="middle"><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="false" /><param name="movie" value="'+ZeroClipboard.moviePath+'" /><param name="loop" value="false" /><param name="menu" value="false" /><param name="quality" value="best" /><param name="bgcolor" value="#ffffff" /><param name="flashvars" value="'+flashvars+'"/><param name="wmode" value="transparent"/></object>';
		}
		else {
			// all other browsers get an EMBED tag
			html += '<embed id="'+this.movieId+'" src="'+ZeroClipboard.moviePath+'" loop="false" menu="false" quality="best" bgcolor="#ffffff" width="'+width+'" height="'+height+'" name="'+this.movieId+'" align="middle" allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="'+flashvars+'" wmode="transparent" />';
		}
		return html;
	},
	
	hide: function() {
		// temporarily hide floater offscreen
		if (this.div) {
			this.div.style.left = '-2000px';
		}
	},
	
	show: function() {
		// show ourselves after a call to hide()
		this.reposition();
	},
	
	destroy: function() {
		// destroy control and floater
		if (this.domElement && this.div) {
			this.hide();
			this.div.innerHTML = '';
			
			var body = document.getElementsByTagName('body')[0];
			try { body.removeChild( this.div ); } catch(e) {;}
			
			this.domElement = null;
			this.div = null;
		}
	},
	
	reposition: function(elem) {
		// reposition our floating div, optionally to new container
		// warning: container CANNOT change size, only position
		if (elem) {
			this.domElement = ZeroClipboard.$(elem);
			if (!this.domElement) this.hide();
		}
		
		if (this.domElement && this.div) {
			var box = ZeroClipboard.getDOMObjectPosition(this.domElement);
			var style = this.div.style;
			style.left = '' + box.left + 'px';
			style.top = '' + box.top + 'px';
		}
	},
	
	setText: function(newText) {
		// set text to be copied to clipboard
		this.clipText = newText;
		if (this.ready) this.movie.setText(newText);
	},
	
	addEventListener: function(eventName, func) {
		// add user event listener for event
		// event types: load, queueStart, fileStart, fileComplete, queueComplete, progress, error, cancel
		eventName = eventName.toString().toLowerCase().replace(/^on/, '');
		if (!this.handlers[eventName]) this.handlers[eventName] = [];
		this.handlers[eventName].push(func);
	},
	
	setHandCursor: function(enabled) {
		// enable hand cursor (true), or default arrow cursor (false)
		this.handCursorEnabled = enabled;
		if (this.ready) this.movie.setHandCursor(enabled);
	},
	
	setCSSEffects: function(enabled) {
		// enable or disable CSS effects on DOM container
		this.cssEffects = !!enabled;
	},
	
	receiveEvent: function(eventName, args) {
		// receive event from flash
		eventName = eventName.toString().toLowerCase().replace(/^on/, '');
				
		// special behavior for certain events
		switch (eventName) {
			case 'load':
				// movie claims it is ready, but in IE this isn't always the case...
				// bug fix: Cannot extend EMBED DOM elements in Firefox, must use traditional function
				this.movie = document.getElementById(this.movieId);
				if (!this.movie) {
					var self = this;
					setTimeout( function() { self.receiveEvent('load', null); }, 1 );
					return;
				}
				
				// firefox on pc needs a "kick" in order to set these in certain cases
				if (!this.ready && navigator.userAgent.match(/Firefox/) && navigator.userAgent.match(/Windows/)) {
					var self = this;
					setTimeout( function() { self.receiveEvent('load', null); }, 100 );
					this.ready = true;
					return;
				}
				
				this.ready = true;
				this.movie.setText( this.clipText );
				this.movie.setHandCursor( this.handCursorEnabled );
				break;
			
			case 'mouseover':
				if (this.domElement && this.cssEffects) {
					this.domElement.addClass('hover');
					if (this.recoverActive) this.domElement.addClass('active');
				}
				break;
			
			case 'mouseout':
				if (this.domElement && this.cssEffects) {
					this.recoverActive = false;
					if (this.domElement.hasClass('active')) {
						this.domElement.removeClass('active');
						this.recoverActive = true;
					}
					this.domElement.removeClass('hover');
				}
				break;
			
			case 'mousedown':
				if (this.domElement && this.cssEffects) {
					this.domElement.addClass('active');
				}
				break;
			
			case 'mouseup':
				if (this.domElement && this.cssEffects) {
					this.domElement.removeClass('active');
					this.recoverActive = false;
				}
				break;
		} // switch eventName
		
		if (this.handlers[eventName]) {
			for (var idx = 0, len = this.handlers[eventName].length; idx < len; idx++) {
				var func = this.handlers[eventName][idx];
			
				if (typeof(func) == 'function') {
					// actual function reference
					func(this, args);
				}
				else if ((typeof(func) == 'object') && (func.length == 2)) {
					// PHP style object + method, i.e. [myObject, 'myMethod']
					func[0][ func[1] ](this, args);
				}
				else if (typeof(func) == 'string') {
					// name of function
					window[func](this, args);
				}
			} // foreach event handler defined
		} // user defined handler for event
	}
	
};

//if(strlen($_POST['table']) and strlen($_POST['row'])>0 and strlen($_POST['okr'])>0 and (strlen($_POST['znach'])>0 or strlen($_POST['zamen'])>0))
</script>
<!--
 <link rel="stylesheet" type="text/css" href="http://juliabot.com/hosted_library/semantic/packaged/css/semantic.css">
 <script src="http://juliabot.com/hosted_library/semantic/packaged/javascript/semantic.js"></script>
--> 

	
	
    <link href="http://juliabot.com/hosted_library/metro/css/metro-bootstrap.css" rel="stylesheet">
    <link href="http://juliabot.com/hosted_library/metro/css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="http://juliabot.com/hosted_library/metro/docs/css/iconFont.css" rel="stylesheet">
    <link href="http://juliabot.com/hosted_library/metro/docs/css/docs.css" rel="stylesheet">
	<script src="http://juliabot.com/hosted_library/metro/min/metro.min.js"></script>
	<!-- Календарь -->
	<script src="http://juliabot.com/hosted_library/metro/js/metro-calendar.js"></script>
	<script src="http://juliabot.com/hosted_library/metro/js/metro-datepicker.js"></script>
	<!--Слайдер-->
	<script src="http://juliabot.com/hosted_library/metro/js/metro-slider.js"></script>
 
<body  class="metro" onload="FocusOnInput()">

<link href='http://fonts.googleapis.com/css?family=Istok+Web&subset=cyrillic-ext,latin,latin-ext' rel='stylesheet' type='text/css'>
<style>
body,h1,h2,h3,div,input,a
{
font-family: 'Istok Web', sans-serif;
}
body
{
background:rgb(255,197,7);
}
input
{
width:auto !important;
}
</style>

<title>Поисковик</title>
<h2 style="margin:0;"><span style="font-size:35px;">П</span>оисковик на сайте вместе с редактором</h2><!-- -->
<small >Глубокий поиск и редактирование в Mysql таблицах и файлах</small><br>
<!--
<small><a href="">Удалить этот скрипт</a> или <a href="">задать пароль </a> </small>
<div style="position:absolute;top:12px;right:12px;">

<form action="" method="POST"><input type="text" name="password" size="16"><input type="submit" value="Отправить"></form>
</div>
-->

<form class="ui fluid form" method="POST" action="">
<input type="hidden" name="search" value="y">
<table>
<tr><td style="width:244px;">Строка поиска :</td><td>


<input id="input" style="font-size:44px;width:640px;height:62px;" type="text" value="<?php echo htmlspecialchars($_POST['search_string']);?>" name="search_string">

<br><font color="grey">Например:<i>igrushki.ru</i></font></td></tr>
<tr><td>Предполагаемая строка <br>замены:</td><td>
<!--<div class="ui input" style="display: inline-block;">-->

<input style="width:320px;" type="text" value="<?php echo htmlspecialchars($_POST['search_string_zam']);?>" name="search_string_zam">
<br>
<!--<div class="ui blue inverted segment"></div>-->
<font color="grey">Например:<i>detskii_mir.ru</i></font>


<font style="font-size:12px;">
<table>
<?php if($_POST['zamen_srazy_f']=='y' and 1==2 ){ $checked='checked';}else{ $checked='';}?>
<tr><td style="font-size:12px;">Заменить сразу не предлагая варианты в файлах</td><td style="font-size:12px;">

													<input value="y" <?php echo $checked;?> type="checkbox" name="zamen_srazy_f">

</td>
</tr>
<?php if($_POST['zamen_srazy_m']=='y' and 1==2 ){ $checked='checked';}else{ $checked='';}?>
<tr>
<td style="font-size:12px;">Заменить сразу не предлагая варианты в Mysql</td><td style="font-size:12px;"><input <?php echo $checked;?> value="y" type="checkbox" name="zamen_srazy_m"></td>
</tr>
</table>
</font>


</td></tr>
<tr><td>Папка поиска:</td><td>

<?php
if($_POST['path'])
{

$path=$_POST["path"].'/';
}
else
{
$path=$_SERVER['DOCUMENT_ROOT'].'/';
}
$path=preg_replace("$/+$","/",$path);

//Вычисление папки верхнего каталога
$new_path_ar=split('/',$path);
$a=count($new_path_ar)-1;//Ключ последнего элемента массива
$b=count($new_path_ar)-2;//Ключ предпоследнего элемента массива
if(strlen($new_path_ar[$a])==0)
{
unset($new_path_ar[$a]);
unset($new_path_ar[$b]);
}
else
{
unset($new_path_ar[$a]);
}
$vverx_path=implode('/',$new_path_ar);



echo '
<div class="ui input" style="display: inline-block;">
<input style="font-weight:bold;" type="text" size="80" name="path" value="'. htmlspecialchars($path).'">
</div>
<br><!--style="font-weight:bold;"-->

<small><a href="?path='.$vverx_path.'">Перейти в папку выше</a>&nbsp; <br>
';
?>
<table>
<tr>
<td valign="top">
<?
echo '
Подпапки поиска:</small>
<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA/ElEQVQ4jaXTSUoDURAG4G9hdCEuvIXEgSgJesQGwQHidBKHayR6Al25UyFRI3HR9eKz070I+aEWNb5XVX8xjx2cYojXkAFO0K6Jn2EVfUwwbZAJztGqS36IoE9coYvNkB6u8RUxd5Ezw004nrGf2dPLCQd4CdtFMu7iByPsVX5WLQAdjKOdtuhpqhxSFXUF4CzPeQqlu0CBw7AP4D2U9QUKbIT9DT6WLZBa6IWz0MyDImKOQh/yN8R+9kJdkSLzX8qGuK1cydj/NRYNyR0l2b6xlYxpLVUiFZXknEjH+VBauDdP5bWQKpVvsaKCpY4pRzrnRyW9R8pp157zL2etcxPU3FAiAAAAAElFTkSuQmCC">

';
$files=scandir($path);
$cheked=array_flip($_POST['files']);

if(strlen($_COOKIE['sizedir'])>0)
{
$size_dir=unserialize($_COOKIE['sizedir']);

}

foreach($files as $k=>$v)
{
	if($v=='.' or  $v=='..' or strlen($v)==0)
	{
	}
	elseif(is_dir($path.'/'.$v.'/'))
	{
		echo ' <br>';
			if(isset($cheked[$v]))
			{
			$checked='checked';
			}
			else
			{
			$checked='';
			}
			if(count($_POST['files'])==0)
			{
			$checked='checked';
			}
		$name=iconv('windows-1251','utf-8',$v);
		echo '<input '.$checked.' type="checkbox" name="files[]" value="'.htmlspecialchars($v).'"><a href="?path='.$path.'/'.$v.'/'.'"&ext='.$_POST['ext'].'>'.htmlspecialchars($name).'</a>&nbsp;';
		//echo $path.'/'.$v.'<br>';
		if(isset($size_dir[$path.$v]))
		{
		echo '<small>('.razmer($size_dir[$path.$v]).')</small>';
		}
	}
	else
	{
		$files_and[]=array('filename'=>$v,'path'=>$path.'/'.$v);
	}
}
?>
</td><td valign="top">
Файлы в папке:
<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA/ElEQVQ4jaXTSUoDURAG4G9hdCEuvIXEgSgJesQGwQHidBKHayR6Al25UyFRI3HR9eKz070I+aEWNb5XVX8xjx2cYojXkAFO0K6Jn2EVfUwwbZAJztGqS36IoE9coYvNkB6u8RUxd5Ezw004nrGf2dPLCQd4CdtFMu7iByPsVX5WLQAdjKOdtuhpqhxSFXUF4CzPeQqlu0CBw7AP4D2U9QUKbIT9DT6WLZBa6IWz0MyDImKOQh/yN8R+9kJdkSLzX8qGuK1cydj/NRYNyR0l2b6xlYxpLVUiFZXknEjH+VBauDdP5bWQKpVvsaKCpY4pRzrnRyW9R8pp157zL2etcxPU3FAiAAAAAElFTkSuQmCC">
<br>
<?
foreach ($files_and as $k=>$v)
{
echo '<a target="_blank" href="?fopen='.$v['path'].'">'.$v['filename'].'</a><br>';
}
?>
</td></tr>
</table>
</td>

</tr>
<tr><td>Расширения файлов :</td><td>

<br>
<?php
$cheked=array_flip($_POST['ras']);
$ras=array('php','html','js','css','sql','xml');
if(isset($cheked['*']))
{
$checked2='checked';
}
echo '<input type="checkbox" name="ras[]" '.$checked2.' value="*"><b>Все</b>&nbsp;';
foreach($ras as $k=>$v)
{
if(isset($cheked[$v]))
{
$checked='checked';
}
else
{
$checked='';
}
if(count($_POST['ras'])==0)
{
$checked='checked';
}
echo '<input '.$checked.' type="checkbox" name="ras[]" value="'.htmlspecialchars($v).'">.'.htmlspecialchars($v).'&nbsp;';
}
echo '<br><input type="text" name="ext" value="'.$_POST['ext'].'">(дополнительно через запятую ,например <b>swf,gif,jpg</b>)';


?></td></tr>
<tr>
<td>Название файла (папки):</td><td>
<input type="text" value="<?php echo htmlspecialchars($_POST['filename']);?>" name="filename" style="width:320px;">
&nbsp;<br>
<?php if($_POST['filename_o']=='y'){ $checked='checked';}else{ $checked='';}?>
<input <?php echo $checked;?> type="checkbox" name="filename_o" value="y"> <small>Не искать в названиях папок</small>&nbsp;
<?php if($_POST['filename_c']=='y'){ $checked='checked';}else{ $checked='';}?>
<input <?php echo $checked;?> type="checkbox" name="filename_c" value="y">
<small>Введена только часть названия <!--,например если ввести 'my' ,будет находить '3my_photo.jpg'--></small></td>
</tr>
<tr>
<td>Размеры файлов :</td><td>от

<div class="slider" id="slider2" data-role="slider" data-position="0" data-accuracy="0" data-colors="blue, red, yellow, green"></div>
<script>
    $(function(){
    var slider2 = $("#slider2").slider({
    position: 10,
    accuracy: 1
    });
	 slider2.slider('value', 100);
    });
    // Short method to set position

</script>
<input type="text" size="7" value="<?php echo htmlspecialchars($_POST['size_sort_ot']);?>" name="size_sort_ot">&nbsp;<b>Kb</b> до
<input type="text" size="7" value="<?php echo htmlspecialchars($_POST['size_sort_do']);?>" name="size_sort_do">&nbsp;<b>Kb</b>
</td>
</tr>
<tr>
<?php
$date[1]=date("w");

switch ($date[1]){
case 1: $m='Понедельник'; break;
case 2: $m='Вторник'; break;
case 3: $m='Среда'; break;
case 4: $m='Четверг'; break;
case 5: $m='Пятница'; break;
case 6: $m='Суббота'; break;
case 7: $m='Воскресенье'; break;
}
?>
<td>Дата изменения файла:
<br>
<small><b>(сегодня <?php echo date('d.m.y').','.$m.')<br>'.date('H:i:s');?></b></small>
</td><td>
<?php if($_POST['segodnya']=='y'){ $checked='checked';}else{ $checked='';}?>
от 
   <!-- <div class="input-control text"> -->
    <input type="text" class="datepicker" value="<?php echo htmlspecialchars($_POST['dat_ot']);?>" size="7" name="dat_ot"><!--  class="datepicker"-->
	
   <!--
   <button class="btn-date"></button>
	</div>-->
&nbsp; до
<input class="datepicker" value="<?php echo htmlspecialchars($_POST['dat_do']);?>" type="text" size="7" name="dat_do">&nbsp;</td>
</tr>
<tr>
<td>CHMOD:</td><td>
<input type="text" size="3" name="chmod" value="<?php echo htmlspecialchars($_POST['chmod']);?>" maxlength="3">&nbsp;<input type="radio" value=1 name="chmod_typ">&nbsp;равно<input type="radio" name="chmod_typ" value=2>
&nbsp;более свободные права (>=)<input type="radio" name="chmod_typ" value=3>&nbsp;более жесткие права (<=)
</td>
</tr>

<tr><td>Поиск в Mysql :</td>
<td>
<script>
function mysql_check()
{
if(document.getElementById('mysql_login').style.display!='block')
{
document.getElementById('mysql_login').style.display='block';
}
else
{
document.getElementById('mysql_login').style.display='none';
}
}
</script>
<?php
if($_POST['mysql']=='y')
{
 $checked='checked';
}
else
{
 $checked='';
}
?>
<input <?php echo $checked;?> type="checkbox" name="mysql" value="y" id="mysql" onclick="mysql_check();">&nbsp;Да
<?php
if($_POST['only_mysql']=='y')
{
 $checked='checked';
}
else
{
 $checked='';
}
?>
<input <?php echo $checked;?> type="checkbox" name="only_mysql" value="y" id="only_mysql" onclick="mysql_check();">&nbsp;Искать только в Mysql ,без поиска в файлах
<div id="mysql_login" style="display:none;">
<?php

if($_POST['install']==1 and !file_exists($filename))
{
$text='<?php
$mysql_login="'.$_POST['mysql_login'].'";
$mysql_password="'.$_POST['mysql_password'].'";
$mysql_host="'.$_POST['mysql_host'].'";
$mysql_db="'.$_POST['mysql_db'].'";
?>';
// Открыть файл
$f = fopen($filename, "a+");
// Записать строку
fwrite($f,$text);
// Закрыть файл
fclose($f);
}

if($res_link===false or $res_link===NULL)//Проверяем подключился ли с этими данными скрипт к базе данных
{
?>
К сожалению мы не смогли определить скрипт вашего сайта,введите Mysql-доступы <br>
или отредактируйте файл <b><?php echo $filename;?></b><br>

<input type="hidden" name="install" value="1">
<table>
<tr><td>Mysql-логин:</td><td><input type="text" name="mysql_login"></td></tr>
<tr><td>Mysql-пароль:</td><td><input type="text" name="mysql_password"></td></tr>
<tr><td>Mysql хост:</td><td><input type="text" name="mysql_host"></td></tr>
<tr><td>Имя базы данных:</td><td><input type="text" name="mysql_db"></td></tr>
</table>
<?php
}
?>

</div>
<?php
$res = mysql_query("SHOW DATABASES");

while ($row = mysql_fetch_assoc($res)) {
    $databases[]=$row['Database'];
}
//print_r($databases);
echo '<br>Базы данных в которых будет проходить поиск:';
foreach($databases as $k=>$v)
{
$checked='checked';
echo '<br><input '.$checked.' type="checkbox" name="databases[]" value="'.htmlspecialchars($v).'">&nbsp;'.htmlspecialchars($v).'';
}
?>
</td>

</tr>
<tr>
<?php if($_POST['evristika']=='y'){ $checked='checked';}else{ $checked='';}?>
<td>Эвристический поиск:</td><td style="width:400px;display:block;">

<input type="checkbox" name="evristika" value="y" <?php echo $checked;?>>

 Очищать  все спецсимволы
<!--<input type="checkbox" name="evristika_o" value="y" <?php echo $checked;?>> Очень глубокий-->
<br>
<small>Например для строки '8 (+495) 718-39-43' найдет '8(495)718-3-943'</small>
</td>

<?php if($_POST['html']=='y'){ $checked='checked';}else{ $checked='';}?>
<td style="display:block;">

<input type="checkbox" name="html" value="y" <?php echo $checked;?>>
 Очищать от HTML-тегов
<!--<input type="checkbox" name="evristika_o" value="y" <?php echo $checked;?>> Очень глубокий-->
<br>
<small>Например для строки '8 
<font color="grey">
<?php echo htmlspecialchars('<span>');?>
</font>
(+495)
<font color="grey">
<?php echo htmlspecialchars('</span>');?>
</font>
 718-39-43' найдет '8(495)718-39-43'</small>
</td>
</tr>
<tr><td>Количество результатов:</td><td>
<input type="text" name="kolich" value="<?php
if(isset($_POST['kolich']))
{
 echo htmlspecialchars($_POST['kolich']);
}
else
{
echo 200;
}
 ?>" size="6">
</td>
</tr>
<?php if($_POST['statistika']=='y'){ $checked='checked';}else{ $checked='';}?>
<tr><td>Собрать статистику:</td><td>
<input type="checkbox" name="statistika" value="y" <?php echo $checked;?> >
<br>
<small>Размеры папок,количество файлов,время выполнения,управление папками</small>
</td>
</tr>
<tr><td><h3>Работа с изображениями</h3></td></tr>
<tr>
<td>
Высота,ширина изображения:
</td>

<td>
<br>
<small></small>
</td>
</tr>
<tr>
<td>
Время и дата съёмки:
</td>
<td>
<br>
<small>Если в файлах jpeg или tiff прописан exif</small>
</td>
</tr>
<tr>
<td>
Модель камеры съёмки:
</td>
<td>
<br>
<small>Если в файлах jpeg или tiff прописан exif</small>
</td>
</tr>
<tr>
<td>
Копирайт фото:
</td>
<td>
<br>
<small>Если в файлах jpeg или tiff прописан exif</small>
</td>
</tr>
<tr><td><h3>Блок исследования CMS</h3></td></tr>
<tr>
<td>
Посмотреть список файлов которые были прочтены на сервере за последние 30 секунд 
</td>
<?php if($_POST['cms_rfiles']=='y'){ $checked='checked';}else{ $checked='';}?>
<td>
<input type="checkbox" name="cms_rfiles" value="y" <?php echo $checked;?> >

<br>
<small>Опция работает в Windows и может работать или не работать на Linux .Все зависит от настроек свервера на Linux</small>
</td>
</tr>
<!--
<?php if($_POST['poshagovo']=='y'){ $checked='checked';}else{ $checked='';}?>
<tr><td>Искать пошагово:</td><td><input type="checkbox" name="poshagovo" value="y" <?php echo $checked;?> > Да
<br>
<small>В случае если set_time_limit маленький а сайт очень большой .Значение вашего сервера <b><?php
$set_time=ini_get('max_execution_time');
if($set_time==0)
{
echo 'бесконечность';
}
else
{
echo $set_time;
}
?></b> секунд.</small>
</td>
</tr>
-->
<tr><td>
<button name="sbros" class="command-button inverse">
                                    <!--<i class="icon-share-3 on-right"></i>-->
                                    Сбросить все настройки
                                    <small>Сбросить настройки поиска</small>
</button>
<!--<input  class="button large primary" type="submit" name="sbros" value="Сбросить все настройки"><br>-->
<input  class="button large primary" type="submit" value="Начать поиск">
</td>
</tr>
</table>
</form>
<?php
if($flag_neotpr===true)exit;
$schetchik=0;//Подсчет найденных результатов
$exit=false;$tek_dir=0;$obs_raz=0;$kol_file=0;
$time=time();
if($_POST['html']=='y')$html='y';
function search_string($dir)
    {
global
$search_string,$ext2,$result,$chmod,$chmod_typ,
$req_dir,$tek_dir,$obs_raz,$size_dir,$kol_file,$ras_array,$pods_dir,$kol_dir,
$size_sort_ot,$size_sort_do,$size_filter_1,$size_filter_2,//Фильтр размеров
$date_sort_ot,$date_sort_do,$date_filter_1,$date_filter_2,//Сортировка по дате
$filename,$filename_c,$filename_filtr,//Сортировка по имени файла
$schetchik,$kolich,$exit,//Количество результатов
$evristika,$statistika,$html,
$zamen_srazu_f,$search_string_zam,
$cms_rfiles,$time //Поиск недавно прочтенных файлов
;
if($exit===true and $statistika!='y')return 0;
        $objs = glob($dir."/*");
        if ($objs)
        {
            foreach($objs as $obj)
            {
                if(is_dir($obj))
                {
if(count($req_dir)>0)
{
foreach($req_dir as $k=>$v)
{
$objm=preg_replace("$/+$","/",$obj);
if(substr($objm,0,strlen($v))==$v)
{
$pods_dir=$v;
search_string($objm.'/');

}
}
}
else
{
search_string($obj);
}
                }
                else
                {
$array_ext=explode(".",basename($obj));
if(count($array_ext)>1)
{
$ext=end($array_ext);
$ext=mb_strtolower($ext,'utf-8');
}
else
{
$ext='undefined';
};
$statictics=stat($obj);
$perms = fileperms($obj);
//$cut = $octal ? 2 : 3;
$chmod=substr(decoct($perms), 3);
$chmod=str_split($chmod);
$ch1=$statictics[4];
$ch2=$statictics[5];
$size=$statictics[7];
//
$tek_dir=$tek_dir+$size;
$size_dir[$pods_dir]=$size_dir[$pods_dir]+$size;
$kol_dir[$pods_dir]++;
$obs_raz=$obs_raz+$size;//Общий размер
$kol_file=$kol_file+1;//Количество файлов
$ras_array[$ext]++;
//
$date=$statictics[9];

##########################Фильтр##################
$sort=true;
//Фильтр по названию файла
if($filename_filtr===true)
{
$fname=mb_strtolower(basename($obj),'utf-8');
if($filename_c===true)
{
if(strpos($fname,$filename)!==false)
{
}
else
{
$sort=false;
}
}
else
{
if($fname==$filename)
{
}
else
{
$sort=false;
}
}
}
//Фильтр по ЧМОД

if($chmod_typ==1)
{
if($ch1==$chmod[0] and $ch1==$chmod[1] and $ch2==$chmod[2])
{
}
else
{
//Файл фильтр прошел
$sort=false;
}
};
if($chmod_typ==2)
{
if($ch1>=$chmod[0] and $ch1>=$chmod[1] and $ch2>=$chmod[2])
{
}
else
{
//Файл фильтр прошел
$sort=false;
}
};
if($chmod_typ==3)
{
if($ch1<=$chmod[0] and $ch1<=$chmod[1] and $ch2<=$chmod[2])
{
}
else
{
//Файл фильтр прошел
$sort=false;
}
};
//Фильтр по размеру от
if($size_filter_1==1)
{	
if($size_sort_ot<=$size)
{
}
else
{
$sort=false;
}
}
//Фильтр по размеру до
if($size_filter_2==1)
{	
if($size_sort_do>=$size)
{
}
else
{
$sort=false;
}
}
//Фильтр по дате от
if($date_filter_1==1)
{
if($date_sort_ot<=$date)
{
//echo $date_sort_ot.'|'.$date.'<br>';
}
else
{
$sort=false;
}
}
//Фильтр по дате до
if($date_filter_2==1)
{	
if($date_sort_do>=$date)
{
//echo $date_sort_do.'|'.$date.'<br>';
}
else
{
$sort=false;
}
}
//Фильтр по расширению файла
if(isset($ext2[$ext]) or (count($ext2)==0))
{

}
else
{
$sort=false;
}


if($statistika=='y')$sort=false;
if($sort===true and strlen($search_string)>0 and $size<4000000)
{
$handle = fopen($obj,"r");
$text=fread($handle,filesize($obj));
fclose($handle);
if($zamen_srazu_f=='y' and mb_stripos($text,$search_string,0,'utf-8')!==false)
{
$kol_vx=substr_count($text,$search_string);
$text2=str_replace($search_string,$search_string_zam,$text);	
@mkdir('search_backups_47');
if (!copy($obj,'search_backups_47/'.basename($obj))) {}
$fp = fopen($obj, 'w');
fwrite($fp,$text2);
fclose($fp);
$vtext='В файле '.$obj.' все строки '.$search_string.' заменены на '.$search_string_zam.'.
Количество замен '.$kol_vx.' .Копия измененного файла сохранена в папке search_backups_47';
$ios=true;
}
else
{
if($html=='y')
{
$text=strip_tags($text);
$search_string=strip_tags($search_string);
};
if($evristika===true)
{
$text=preg_replace("/[^0123456789a-zA-ZабвгдеёжзийклмнопрстуфхцчшщьыъэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯ]/",'',$text);
}
//echo $text.'<br>';

$search_string_win=iconv('utf-8','windows-1251',$search_string);

if(mb_stripos($text,$search_string,0,'utf-8')!==false)
{
$kol_vx=substr_count($text,$search_string);
$kod='utf-8';
if($kol_vx==1)
{
$pos=mb_stripos($text,$search_string,0,'utf-8');
$nlenght=100;
$klenght=100;
$npos=$pos-$nlenght;
if($npos<0){$npos=0;$nlenght=$pos;};
$ntext=mb_substr($text,$npos,$nlenght,'utf-8');
$ktext=mb_substr($text,$pos+mb_strlen($search_string,'utf-8'),$klenght,'utf-8');
$vtext=$ntext.$search_string.$ktext;
}
else
{
$ios=true;
$vtext='Количество вхождений данного запроса в этом файле превышает 1.Лучше отредактируйте файл по ссылке поля "Имя файла"';
}
}
elseif(strpos($text,$search_string_win)!==false)
{
$kol_vx=substr_count($text,$search_string);
$pos=strpos($text,$search_string_win);
$nlenght=100;
$klenght=100;
$npos=$pos-$nlenght;
if($npos<0){$npos=0;$nlenght=$pos;};
$ntext=substr($text,$npos,$nlenght);
$ktext=substr($text,$pos+strlen($search_string_win),$klenght);
$vtext=iconv('windows-1251','utf-8',$ntext).$search_string.iconv('windows-1251','utf-8',$ktext);
$kod='windows-1251';
}
else
{
$sort=false;
}
}
}

if($cms_rfiles=='y')
{
//echo date('H:i:s',$time).rudate('d F Y',$time).'|'.date('H:i:s',$statictics['atime']).rudate('d F Y',$statictics['atime']);

	if($time-$statictics['atime']<30)
	{
	
	}
	else
	{
	$sort=false;
	};
}

if($sort!==false and $exit!==true)
{
//\''.htmlspecialchars($v['file2']).'\',pos_a,old_raz_a,text_a
$result[]=array('file'=>$obj,'text'=>$vtext,'kod'=>$kod,
'vre'=>$date,
'chmo'=>$chmod,
'razm'=>$size,
'pos'=>$pos,
'kol_vx'=>$kol_vx,
'i'=>$ios
);
$schetchik++;
}

if($schetchik>=$kolich){$exit=true;}
             }
            }
        }

}
if($_POST['cms_rfiles']=='y')
{
$cms_rfiles=$_POST['cms_rfiles'];
}
if(intval($_POST['kolich'])>0)
{
$kolich=intval($_POST['kolich']);
}
else
{
$kolich=200;
}
if(strlen($_POST['filename'])>0)
{

$filename=trim($_POST['filename']);
if(strlen($filename)>0)$filename_filtr=true;
if($_POST['filename_c']=='y')
{
$filename_c=true;
}
}


if($_POST['search']=='y')
{

if(isset($_POST['chmod_typ']))
{
$chmod=str_split(trim($_POST['chmod']));
$chmod_typ=trim($_POST['chmod_typ']);
}

if(isset($_POST['size_sort_ot']))
{
$size_sort_ot=trim($_POST['size_sort_ot']);
if(strlen($size_sort_ot)>0)$size_filter_1=1;
$size_sort_ot=$size_sort_ot*1024;
}
if(isset($_POST['size_sort_do']))
{
$size_sort_do=trim($_POST['size_sort_do']);
if(strlen($size_sort_do)>0)$size_filter_2=1;
$size_sort_do=$size_sort_do*1024;
}

if(isset($_POST['dat_ot']))
{
$dat_ot=trim($_POST['dat_ot']);
if(strlen($dat_ot)>0)$date_filter_1=1;
$date_sort_ot=strtotime($dat_ot);
}
if(isset($_POST['dat_do']))
{
$dat_do=trim($_POST['dat_do']);
if(strlen($dat_do)>0)$date_filter_2=1;
$date_sort_do=strtotime($dat_do);
}
if($_POST['statistika'])
{
$statistika='y';
};

echo '
<h3><font color="blue">Результаты поиска</font></h3><br>';





if($_POST['only_mysql']!='y')
{
if(strlen($_POST['search_string_zam'])>0 and $_POST['zamen_srazy_f']=='y')
{
//zamen_srazy_m
$zamen_srazu_f='y';
$search_string_zam=$_POST['search_string_zam'];
}


$ext=split(',',mb_strtolower(trim($_POST['ext']),'utf-8'));
foreach($_POST['ras'] as $k=>$v)
{
if($v=='*')
{
unset($ext);
break;
}
$ext[]=$v;
}
if(count($ext)>0)
{
$ext2=array_flip($ext);
//Дополнение синонимичных расширений
/*
if(isset($ext2['jpg']))
{
$ext2['jpeg']=1;
};
if(isset($ext2['jpeg']))
{
$ext2['jpg']=1;
};
*/

}
else
{
unset($ext2);
}

$search_string=trim($_POST['search_string']);
foreach($_POST['files'] as $k=>$v)
{
$path=preg_replace("$/+$","/",trim($_POST['path']).'/'.$v);
$req_dir[]=$path;
}
//print_r($req_dir);
$_POSTpath=preg_replace("$/+$","/",trim($_POST['path']));


//print_r($_POST);

if($_POST['evristika']=='y')
{
$evristika=true;
$search_string=preg_replace('/[^0-9a-zA-Zа-яА-ЯёЁ]/','',$search_string);

};
function get_sec()
{
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    return $mtime;
}
$start_time = get_sec();

search_string(trim($_POSTpath));

$exec_time = get_sec() - $start_time;

echo
'<style>
th
{
cursor: pointer;
font-weight:300;
color:blue;
word-wrap: break-word;
}
</style>';
if($_POST['statistika']=='y')
{
echo '
<h3><font color="#3366FF">Статистика</font></h3>
<table class="ui table segment" id="mytable2">
<thead>
<tr>
<th>Название папки</th><th>Размер</th><th>Количество файлов</th><th>CHMOD</th>
</tr>
</thead>
<tbody>

';
foreach($size_dir as $k=>$v)
{
$vv=razmer($v);
if($k=='')
{
$kname='<small><b>папки текущей папки</b></small>';
}
else
{
$kname=basename($k);
}
$perms = fileperms($_POST['path'].'/'.$v.'/');
$chmod=substr(decoct($perms),3);
echo '<tr><td>'.$kname.'</td><td><small><b>'.$vv.'</b></small></td><td><small>'.$kol_dir[$k].'</small></td>
<td>'.$chmod.'</td></tr>';
}
echo '
</tbody>
</table>
';
$sizedir=serialize($size_dir);

echo 'Общий размер файлов:&nbsp;<small><b>'.razmer($obs_raz).'</b></small><br>';
echo 'Количество файлов:&nbsp;<small><b>'.$kol_file.'</b></small><br>';
echo 'Найденные во время поиска расширения файлов :&nbsp;<small>';
foreach($ras_array as $k=>$v)
{
if($v=='undefined')$v='без расширения';
echo '<a href="http://fileext.ru/'.$k.'" target="_blank">'.$k.'</a> ('.$v.') ,';
}
echo '</small><br>';
echo 'Время выполнения скрипта: '.round($exec_time,2).' секунд<br>';
}
$rasarray=serialize($ras_array);
?>
<script>
function setcookie(name, value, expires, path, domain, secure)
{
document.cookie = name + "=" + escape(value) +
((expires) ? "; expires=" + (new Date(expires)) : "") +
((path) ? "; path=" + path : "") +
((domain) ? "; domain=" + domain : "") +
((secure) ? "; secure" : "");
}
var sizedir='<?php echo $sizedir;?>';
setcookie('sizedir',sizedir);
var rasarray='<?php echo $rasarray;?>';
setcookie('rasarray',rasarray);
</script>
<?php


echo '
<h3><font color="#3366FF">Поиск внутри файлов</font></h3>
<table class="table" id="myTable">
<thead>
<tr>
<th style="width:200px;"><font color="blue">Имя файла</font></th>
<th style="width:30px;"><font color="blue">Удалить файл</font></th>
<th style="width:600px;">
<font color="blue">Часть содержимого файла</font>
</td>
<th style="width:30px;"><font color="blue">Кодировка поиска</font></th>
<th style="width:30px;"><font color="blue">Время изменения</font></th>
<th style="width:30px;"><font color="blue">CHMOD</font></th>
<th style="width:30px;"><font color="blue">Размер (kb)</font></th>
</tr>
</thead>

<tbody>
';
//http://www.aleksandr.ru
foreach($result as $k=>$v)
{
$v['file']=preg_replace("$/+$","/",iconv('windows-1251','utf-8',$v['file']));
$vremya=date('H:i:s',$v['vre']).'<br>'.rudate('d F Y',$v['vre']);
echo '<tr><td style="word-wrap: break-word;width:200px;">
<!--<button id="copyButton">-->
<img width="16" height="16" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGhlaWdodD0iNTEycHgiIGlkPSJMYXllcl8xIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MTIgNTEyOyIgdmVyc2lvbj0iMS4xIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiIgd2lkdGg9IjUxMnB4IiB4bWw6c3BhY2U9InByZXNlcnZlIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj48Zz48Zz48cGF0aCBkPSJNMTYwLDE2MGgxOTJjLTEuNy0yMC05LjctMzUuMi0yNy45LTQwLjFjLTAuNC0wLjEtMC45LTAuMy0xLjMtMC40Yy0xMi0zLjQtMjAuOC03LjUtMjAuOC0yMC43Vjc4LjIgICAgYzAtMjUuNS0yMC41LTQ2LjMtNDYtNDYuM2MtMjUuNSwwLTQ2LDIwLjctNDYsNDYuM3YyMC42YzAsMTMuMS04LjgsMTcuMi0yMC44LDIwLjZjLTAuNCwwLjEtMC45LDAuNC0xLjQsMC41ICAgIEMxNjkuNiwxMjQuOCwxNjEuOSwxNDAsMTYwLDE2MHogTTI1Niw2NC40YzcuNiwwLDEzLjgsNi4yLDEzLjgsMTMuOGMwLDcuNy02LjIsMTMuOC0xMy44LDEzLjhjLTcuNiwwLTEzLjgtNi4yLTEzLjgtMTMuOCAgICBDMjQyLjIsNzAuNiwyNDguNCw2NC40LDI1Niw2NC40eiIvPjxwYXRoIGQ9Ik00MDQuNiw2M0gzMzF2MTQuNWMwLDEwLjYsOC43LDE4LjUsMTksMTguNWgzNy4yYzYuNywwLDEyLjEsNS43LDEyLjQsMTIuNWwwLjEsMzI3LjJjLTAuMyw2LjQtNS4zLDExLjYtMTEuNSwxMi4xICAgIGwtMjY0LjQsMC4xYy02LjItMC41LTExLjEtNS43LTExLjUtMTIuMWwtMC4xLTMyNy4zYzAuMy02LjgsNS45LTEyLjUsMTIuNS0xMi41SDE2MmMxMC4zLDAsMTktNy45LDE5LTE4LjVWNjNoLTczLjYgICAgQzkyLjMsNjMsODAsNzYuMSw4MCw5MS42VjQ1MmMwLDE1LjUsMTIuMywyOCwyNy40LDI4SDI1NmgxNDguNmMxNS4xLDAsMjcuNC0xMi41LDI3LjQtMjhWOTEuNkM0MzIsNzYuMSw0MTkuNyw2Myw0MDQuNiw2M3oiLz48L2c+PHJlY3QgaGVpZ2h0PSIxNiIgd2lkdGg9IjExMiIgeD0iMTQ0IiB5PSIxOTIiLz48cmVjdCBoZWlnaHQ9IjE2IiB3aWR0aD0iMTYwIiB4PSIxNDQiIHk9IjI4OCIvPjxyZWN0IGhlaWdodD0iMTYiIHdpZHRoPSIxMjkiIHg9IjE0NCIgeT0iMzg0Ii8+PHJlY3QgaGVpZ2h0PSIxNiIgd2lkdGg9IjE3NiIgeD0iMTQ0IiB5PSIzMzYiLz48cmVjdCBoZWlnaHQ9IjE2IiB3aWR0aD0iMjA4IiB4PSIxNDQiIHk9IjI0MCIvPjwvZz48L3N2Zz4=">
<!--</button>-->
<a target="_blank" href="?fopen='.$v['file'].'&kod='.$v['kod'].'&search_string='.htmlspecialchars($search_string).'">
'.substr($v['file'],strlen($_POSTpath)).'

</a>
<script type="text/javascript"> 
    var clip = new ZeroClipboard.Client(); 
    clip.setText("Ух ты! Получилось!"); 
    clip.glue("copyButton"); 
</script> 
</td>
<td>
<a target="_blank" href="#" onclick="udalit(\''.htmlspecialchars($v['file']).'\');return false;">
<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAC90lEQVQ4jW2SXUhTcRjGX7adds7//M/+Z67lzOhKQ7woJQpiVBCFIhukXXYRdRFBWdFFqOg8S5nNuexrln2YedZxudpotVxGX4ZCQlF000WUdFEQUcTu6uLpIhqYPncPvL+HB96HaLFsD9as2f8yGLz9KhicexMMzr5sarqWr6wMLHG7UM9qava8bm7GtN+PbFkZJr1ePPB6MVlRgbnGRrxtbv583+erXRJ+Ul/f82jzZpicI718OXLl5bhfUYHCypXI+3zIejxI6Trmdu7E1OrV/gVwZtWqxnsbN+IK50jqOky7HbeFQM7nw93ycmQ0DeOShJTHg1FVxfT27UgQ8VJAfv36rwnOcdXlQrK2FsVfv3AnEMCEomDcZsN0KISvX75gtKwM13UdIx4PpurqRoiIqI2xDWZVFYaEwJDdjvfPn+Of0lu24PHx4yU/E43ikizjohC4WV0NIiI6s2LFsUFNwzkhcEHXkWAMP79/x//6MDODBBGG3W4MCYGRykq0LltWS/2a1jegaTgjBM4LgQTniBOhWCyW4PkXLzBAhGFdx5DbjbNC4JzbjVZZ3kq9jHVENQ1xlwunhUA/ER52dCxqkGpowCmbDWd1HYNCIO5y4YAk1dMRp3NHhHP0aRoiDgem2ttL0Lt8Ht/m50v+RkMD+p1ORDlHjHOUvtDL2O8exhAmwtOTJwEAH2dnYRAhYrOh+OPH3xYtLeiVJEQ4R4yxQilgQFGOGKqKMGPoIsJoUxMMIvSpKiKKggjnuLxpE7qJcIIxhFUVPU5n9YIxRWV5ylAUGIyhy+FAWFEQZgxhxmDIMkKSBIMxGIqCuNN5cMk5h+32iyEidEsSDEWBoap/IVlGt8OBEBF6ZXn3IrBQKFRN5HLbTMtqHO7qGoz5/UVDVdFJhE4i9Hi9iAcCn67GYkeT6XRgIpfbVigUqkoBlmWtHRsbazFNc6+ZSh1KptOtVjbbbmUybVYm02Zls+03bt06PDY+fjiZTO4zTXNXKpVaR0T0B3OGtphyMh8BAAAAAElFTkSuQmCC">
</a>
</td>
<td style="width:600px;height:160px;">';
if(strlen($v['text'])>0)
{
if($v['i']!=true)echo'<textarea id="text_'.$k.'" style="width:100%;height:100%;">';
echo str_replace(htmlspecialchars($search_string),htmlspecialchars($search_string),htmlspecialchars($v['text'])).'';
if($v['i']!=true)echo '</textarea>';
echo '<br>';
if(strlen($_POST['search_string_zam'])>0)
{
echo '
<a href="#"
id="ssil2_'.$k.'"
onclick="zamenred(\''.htmlspecialchars($v['file']).'\',\''.htmlspecialchars($search_string).'\',\''.htmlspecialchars($_POST['search_string_zam']).'\','.$k.');return false;">Заменить <b>"'.htmlspecialchars($search_string).'"</b> на <b>"'.htmlspecialchars($_POST['search_string_zam']).'"</b></a>&nbsp;&nbsp;';
}
$pos=$v['pos']-100;
if($pos<0)$pos=0;
echo '<a id="ssil_'.$k.'" href="#"
onclick="fastred(\''.htmlspecialchars($v['file']).'\','.$pos.','.mb_strlen($v['text'],'utf-8').',document.getElementById(\'text_'.$k.'\').value,'.$k.');return false;">Сохранить</a>';
};
echo '
</td>
<td>'.$v['kod'].'</td>
<td><small>'.$vremya.'</small></td>
<td>
<small>
<!--style="border:solid 1px green;-->
<input id="chmod_'.$k.'" type="text" size="3" value="'.implode('',$v['chmo']).'"><br>
<input value="Изменить" type="submit" style="width:40px;font-size:8px;" onclick="changechmod(\''.htmlspecialchars($v['file']).'\','.$k.');">
</small>
</td>
<td><small>'.round($v['razm']/1024,2).'</small></td>
</tr>';
}
echo '</tbody></table>';
}
if($_POST['mysql']=='y' or $_POST['only_mysql']=='y')
{
$result=array();


// Written by Mark Jackson @ MJDIGITAL
// http://www.mjdigital.co.uk/blog
// Ищем...
$search_string=trim($_POST['search_string']);
$search = $search_string; // Например: 'www.old-site.ru'
// Меняем на... Используется при $queryType = 'replace'
$replace = 'НА_ЧТО_ЗАМЕНЯЕМ'; // Например: 'www.new-site.ru'
// Настройки базы данных
$databases=array();
foreach($_POST['databases'] as $k=>$v)
{
$databases[]=$v;
$req_dir[]=$path;
}
if(count($databases)==0)
{
$databases[]=$mysql_db;
}

foreach($databases as $k=>$v)
{
	$database = $v;

	mysql_select_db($database);
	// Варианты значения переменной $queryType 'search' (вывод результатов поиска) или 'replace' (поиск с заменой)
	$queryType = 'search';
	$table_sql = 'SHOW TABLES FROM '.$database ;
	$table_q = mysql_query($table_sql) or die("Cannot Query DB: ".mysql_error());
	$tables_r = mysql_fetch_assoc($table_q);
	$tables = array();
		do{
		//print_r($tables_r);
		//var_dump('Tables_in_'.strtolower($database));
		//var_dump($tables_r);
		//var_dump($tables_r['Tables_in_'.strtolower($database)]);
		$tables[] = $tables_r['Tables_in_'.strtolower($database)];
		
		}
		while($tables_r = mysql_fetch_assoc($table_q));
	
	$use_sql = array();
	$summary = '';
		foreach($tables as $table) {
		$field_sql = 'SHOW FIELDS FROM '.$table;
		$field_q = mysql_query($field_sql);
		$field_r = mysql_fetch_assoc($field_q);
			do {
			$field = $field_r['Field'];
			$type = $field_r['Type'];

				switch(true) {
				case stristr(strtolower($type),'char'): $typeOK = true; break;
				case stristr(strtolower($type),'text'): $typeOK = true; break;
				case stristr(strtolower($type),'blob'): $typeOK = true; break;
				case stristr(strtolower($field_r['Key']),'pri'): $typeOK = false; break;
				default: $typeOK = false; break;
				}
			if($typeOK) {
			$handle = $table.'_'.$field;
			$sql[$handle]['sql'] = 'SELECT * FROM '.$table.' WHERE '.$field.' LIKE "%'.$search.'%";';
			//echo $sql[$handle]['sql'].'<br>';
			$error = false;
			$query = @mysql_query($sql[$handle]['sql']) or $error = mysql_error();
			while($resu=mysql_fetch_array($query))
			{
			$nom=substr_count($resu[$field],$search);
			$result[]=array('database'=>$database,'file'=>$table,'file2'=>$field,'text'=>$resu[$field],'kod'=>'utf-8','kol_vx'=>$nom,'ser_ar'=>$resu);
			}
			//$row_count = @mysql_affected_rows() or $row_count = 0;
			}
			}while($field_r = mysql_fetch_assoc($field_q));
		}
}

echo '
<h3><font color="#3366FF">Поиск внутри базы данных</font></h3>
<table class="table" style="widht:100%;min-width:1000px;">
<tr>
<td><font color="blue">База данных</font></td>
<td><font color="blue">Имя таблицы</font></td>
<td><font color="blue">Имя поля</font></td>
<td><font color="blue">Содержимое поля</font></td>
<td><font color="blue">Кодировка поиска</font></td>
<td><font color="blue">Сколько раз входит</font></td>
</tr>
';
foreach($result as $k=>$v)
{
echo '<tr><td style="width:12%;">'.$v['database'].'</td><td style="width:12%;">'.$v['file'].'</td><td style="width:8%;">'.$v['file2'].'</td><td style="width:60%;height:120px;">
<textarea id="mysqlrow_'.$k.'" style="width:100%;height:100px;">'.htmlspecialchars($v['text']).'</textarea><br>';
if(strlen($_POST['search_string_zam'])>0)
{
//function fastred_m(table_2,row_2,okr_2,znach_2)
//function zamenred_m(table_2,row_2,okr_2,zamen_2,zamen_3)
$vser_ar=serialize($v['ser_ar']);
echo '<a href="#" onclick="zamenred_m(\''.$v['file'].'\',\''.$v['file2'].'\',\''.base64_encode($vser_ar).'\',\''. addslashes($search_string).'\',\''. addslashes($_POST['search_string_zam']).'\');return false;">Заменить <b>"'.htmlspecialchars($search_string).'"</b> на <b>"'.htmlspecialchars($_POST['search_string_zam']).'"</b></a>';
}
echo '
<a href="#" onclick="fastred_m(\''.$v['file'].'\',\''.$v['file2'].'\',\''.base64_encode($vser_ar).'\',document.getElementById(\'mysqlrow_'.$k.'\').value);return false;">Сохранить</a>
</td><td>'.$v['kod'].'</td><td>'.$v['kol_vx'].'</td></tr>';
}
echo '</table>';

}
}


?>
