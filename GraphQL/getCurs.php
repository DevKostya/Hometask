<?php
function getCurs($moneyCode){
    // создаем объект для работы с XML
    $xml = new DOMDocument();
    // ссылка на сайт банка
    $url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d.m.Y');
    // получаем xml с курсами всех валют
    if ($xml->load($url)){
        // массив для хранения курсов валют
        $result = array(); 
        // разбираем xml
        $root = $xml->documentElement;
        // берем все теги 'Valute' и их содержимое
        $items = $root->getElementsByTagName('Valute');
        // переберем теги 'Valute' по одному
        foreach ($items as $item){
            // получаем код валюты
            $code = $item->getElementsByTagName('CharCode')->item(0)->nodeValue;
            // получаем значение курса валюты, относительно рубля
            $value = $item->getElementsByTagName('Value')->item(0)->nodeValue;
            // записываем в массив, предварительно заменив запятую на точку
            $result[$code] = str_replace(',', '.', $value);
        }
        // возвращаем значение курса, для запрошенной валюты
        return $result[$moneyCode];
    }else{
        // если не получили xml возвращаем false
        return false;
    }
}
// пример использования
echo getCurs($_POST['Curs']);

?>
