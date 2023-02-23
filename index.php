<?php


function getPartsFromFullname($solidFullName){
$arrFullName=array_combine(['surname','name','patronymic'], explode(' ',$solidFullName));
return $arrFullName;
}

function getFullnameFromParts($surname='Сергеев',$name='Сергей',$patronymic='Сергеевич'){
$fullName=$surname.' '.$name.' '.$patronymic;
return $fullName;
}

function getShortName($solidFullName='Сергеев Сергей Сергеевич'){
$shortName=getPartsFromFullname($solidFullName)['name'] .' '. mb_substr(getPartsFromFullname($solidFullName)['surname'], 0, 1) . '.'; 
return $shortName;
}

function getGenderFromName($solidFullName='Петров Пётр Петрович'){
$indexGender=0; 
$arrFullName=getPartsFromFullname($solidFullName);

// проверка на предмет женской гендерной идентичности
if (mb_substr($arrFullName['patronymic'], -3) == 'вна'){
$indexGender--;
}
if (mb_substr($arrFullName['name'], -1) == 'а'){
$indexGender--;
}
if (mb_substr($arrFullName['surname'], -2) == 'ва'){
$indexGender--;
}

// проверка на предмет мужской гендерной идентичности
if (mb_substr($arrFullName['patronymic'], -2) == 'ич'){
$indexGender++;
}
if ((mb_substr($arrFullName['name'], -1) == 'й') || (mb_substr($arrFullName['name'], -1) == 'н')){
$indexGender++;
}
if (mb_substr($arrFullName['surname'], -1) == 'в'){
$indexGender++;
}

return $indexGender <=> 0; // -1 - девушка, 1 - юноша, 0 - пока не определился
}

//Определение возрастно-полового состава
function getGenderDescription($arrPersons){
$arrMales = array_filter($arrPersons, function($arrPersons) {
    return getGenderFromName($arrPersons['fullname']) == 1;
});
$strMalePercentage=round( (count($arrMales) / (count($arrPersons)/100)), 2);
$arrFemales = array_filter($arrPersons, function($arrPersons) {
    return getGenderFromName($arrPersons['fullname']) == -1;
});
$strFemalePercentage=round( (count($arrFemales) / (count($arrPersons)/100)), 2);
$arrUndefined = array_filter($arrPersons, function($arrPersons) {
    return getGenderFromName($arrPersons['fullname']) == 0;
});
$strUndefinedPercentage=round( (count($arrUndefined ) / (count($arrPersons)/100)), 2);

return 'Гендерный состав аудитории:'.PHP_EOL.'- - - - - - - - - - - - - - - - - - - - - - - - - - -'.PHP_EOL.'Мужчины - '.$strMalePercentage.'%'.PHP_EOL.'Женщины - '.$strFemalePercentage.'%'.PHP_EOL.'Не удалось определить - '.$strUndefinedPercentage.'%';
}

function getPerfectPartner($surname,$name,$patronymic,$arrPersons){
$solidFullName=mb_convert_case(getFullnameFromParts($surname,$name,$patronymic), MB_CASE_TITLE);
$strGender=getGenderFromName($solidFullName) * (-1);
do {
  $randomSelection=$arrPersons[rand(1,count($arrPersons)-1)]; // fetch a random person from the array
  $randomMatch=$randomSelection['fullname'];
} while ( getGenderFromName( $randomMatch) !== $strGender); // check for opposite sex
$result=(rand(5000,10000))/100;
return getShortName($solidFullName).' + '.getShortName($randomMatch).' ='.PHP_EOL.'&#10084; Идеально на '.$result.'% &#10084;';
}


echo '<!DOCTYPE html>
<html lang="ru">
	<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="styles.css">
		<title>Практическая работа Модуль 12 </title>
	</head>
	<body>
 
 <p>Функция getPartsFromFullname принимает как аргумент одну строку — склеенное ФИО. Возвращает как результат массив из трёх элементов с ключами name, surname и patronomyc</p>
<form class ="form-group" action="'. htmlspecialchars($_SERVER['PHP_SELF']) .'" method="POST" target="_self" >
<div class="card bg-yellow">
<input class ="form-group" type="text" name="fullname" title="Введите Фамилию Имя Отчество через пробел" value="Андреев Андрей Андреевич" onfocus="this.select();" required> &nbsp; <input id="btn" type="submit" value="Запустить функцию разбиения ФИО: &laquo getPartsFromFullname &raquo" name="getPartsFromFullname"/>
<textarea class ="form-group" placeholder="Поле вывода результата работы функции getPartsFromFullname" title="Поле вывода результата" cols="40" rows="8" />';
if ( !empty ( $_POST['getPartsFromFullname'] )) {
var_dump(getPartsFromFullname($_POST['fullname']));
};
echo '</textarea>
</div>

<p>Функция getFullnameFromParts принимает как аргумент три строки — фамилию, имя и отчество. Возвращает как результат их же, но склеенные через пробел</p>
<form class ="form-group" action="'. htmlspecialchars($_SERVER['PHP_SELF']) .'" method="POST" target="_self" >
<div class="card bg-green">
<input class ="form-group" type="text" name="surname" title="Введите Фамилию" value="Петров" onfocus="this.select();" required><br>
<input class ="form-group" type="text" name="name" title="Введите Имя" value="Сергей" onfocus="this.select();" required><br>
<input class ="form-group" type="text" name="patronymic" title="Введите Отчество" value="Николаевич" onfocus="this.select();" required><br>
 &nbsp; <input id="btn" type="submit" value="Запустить функцию склеивания ФИО &quot;getFullnameFromParts&quot;" name="getFullnameFromParts" />
<textarea class ="form-group"  placeholder="Поле вывода результата работы функции getFullnameFromParts" title="Поле вывода результата" cols="40" rows="2" >';
if ( !empty ( $_POST['getFullnameFromParts'] )) {
var_dump(getFullnameFromParts($_POST['surname'],$_POST['name'],$_POST['patronymic']));
}
echo '</textarea>

</div>
<p>Функция getShortName принимает как аргумент три строки — фамилию, имя и отчество. Возвращает как результат их же, но склеенные через пробел</p>
<form class ="form-group" class ="form-group action="'. htmlspecialchars($_SERVER['PHP_SELF']) .'" method="POST" target="_self" >
<div class="card bg-red">
<input class ="form-group"  placeholder="Поле вывода результата работы функции getFullnameFromParts" type="text" name="fullname" title="Введите Фамилию Имя Отчество через пробел" value="Васильев Василий Васильевич" onfocus="this.select();" required> &nbsp; <input id="btn" type="submit" value="Запустить функцию &quot;getShortName&quot;" name="getShortName" />
<textarea class ="form-group" placeholder="Поле вывода результата работы функции getShortName" title="Поле вывода результата" cols="40" rows="2" >';
if ( !empty ( $_POST['getShortName'] )) {
var_dump(getShortName($_POST['fullname']));
}
echo '</textarea>

</div>
<p>Функция getGenderFromName, принимающую как аргумент строку, содержащую ФИО (вида «Иванов Иван Иванович») и возвращающее значение для определения пола (если значение -1 то это девушка если 0 то пол не определен и если 1 то это мужчина)/p>
<form class ="form-group" action="'. htmlspecialchars($_SERVER['PHP_SELF']) .'" method="POST" target="_self" >
<div class="card bg-orange">
<input class ="form-group"  type="text" name="fullname" placeholder="Фамилия Имя Отчество" value="Иванов Иван Иванович" onfocus="this.select();" required> &nbsp; <input id="btn" type="submit" value="Запустить функцию &quot;getGenderFromName&quot;" name="getGenderFromName" />
<textarea ="form-group" placeholder="Поле вывода результата работы функции getGenderFromName" title="Поле вывода результата" cols="40" rows="2" >';
if ( !empty ( $_POST['getGenderFromName'] )) {
var_dump(getGenderFromName($_POST['fullname']));
}
echo '</textarea>


</div>
<p>Функция getGenderDescription для определения полового состава аудитории. Как аргумент в функцию передается массив, схожий по структуре с массивом, взятым из файла arrPersons.php</p>
<form class ="form-group" action="'. htmlspecialchars($_SERVER['PHP_SELF']) .'" method="POST" target="_self" >
<div class="card bg-blue">
<textarea class ="form-group" placeholder="$example_persons_array" cols="40" rows="12" name="persons_array" >';
require('arrPersons.php');
echo'</textarea>
<input class ="form-group" type="submit" id="btn"  value="Запустить функцию &quot;getGenderDescription&quot;" name="getGenderDescription" />
<textarea class ="form-group" placeholder="Поле вывода результата работы функции getGenderDescription" title="Поле вывода результата" cols="1" rows="5" >';
if ( !empty ( $_POST['getGenderDescription'] )) {
echo getGenderDescription(json_decode($_POST['persons_array'], true));
}
echo '</textarea>

</div>
<p>Функция getPerfectPartner для определения «идеальной» пары</p>
<form class ="form-group" action="'. htmlspecialchars($_SERVER['PHP_SELF']) .'" method="POST" target="_self" >
<div class="card bg-purple">
<input class ="form-group" type="text" name="surname" placeholder="Фамилия" title="Введите Фамилию" value="ИВАНОВ" onfocus="this.select();" required><br>
<input class ="form-group" type="text" name="name" placeholder="Имя" title="Введите Имя" value="ИВАН" onfocus="this.select();" required>
<input class ="form-group" type="text" name="patronymic" placeholder="Отчество" title="Введите Отчество" value="ИВАНОВИЧ" onfocus="this.select();" required>
<textarea class ="form-group" placeholder="$example_persons_array" cols="40" rows="12" name="persons_array" >';
require('arrPersons.php');
echo'</textarea>
<input class ="form-group" type="submit" id="btn" value="Запустить функцию &quot;getPerfectPartner&quot;" name="getPerfectPartner" />
<textarea class ="form-group" placeholder="Поле вывода результата работы функции getPerfectPartner" title="Поле вывода результата"cols="1" rows="2" >';
if ( !empty ( $_POST['getPerfectPartner'] )) {
echo getPerfectPartner($_POST['surname'],$_POST['name'],$_POST['patronymic'],json_decode($_POST['persons_array'], true));
}
echo '</textarea>
</div>
</body>
</html>';
