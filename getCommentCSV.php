<?
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


$obIblock = CIBlock::GetList(Array(), Array('TYPE'=>'Content', "CODE"=>'novosti'), true);

$arCommentsToCSV = array();

if ($arIblock = $obIblock->fetch()) {

    $obSection = CIBlockSection::GetList(Array($by=>$order), ['IBLOCK_ID' => $arIblock['ID'], 'CODE' => 'fotokonkurs'], true);

    if ($arSection = $obSection->fetch()) {

        $obElements = CIBlockElement::GetList(Array(), ['IBLOCK_ID' => $arIblock['ID'], 'SECTION_ID' => $arSection['ID']], false, false, Array());

        $arElements = [];

        while ($arElement = $obElements->fetch()) {
            $arElements[] = $arElement;
        }

        Loader::includeModule("highloadblock");

        $arHbl = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('TABLE_NAME' => 'comments')))->fetch();

        $hlblock = HL\HighloadBlockTable::getById($arHbl['ID'])->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $obComments = $entity_data_class::getList([
            'filter' => [
                'UF_ID_NEWS' => array_column($arElements, 'ID')
            ]
        ]);

        $arComments = [];
        while($arComment = $obComments->fetch()) {
            $arComments[$arComment['UF_ID_NEWS']][] = $arComment;
        }

        $protocol = (CMain::IsHTTPS()) ? "https://" : "http://";

        foreach ($arElements AS $key => $arElement) {
            $linkNews = $protocol . $_SERVER["HTTP_HOST"] . '/novosti/fotokonkurs/' . $arElement['CODE'] . '/';

            if (!$arComments[$arElement['ID']]) {
                continue;
            }
			echo '<b><a href="' . $linkNews . '" target="_blank">Публикация</a>:</b> ' . $arElement['NAME'] . '<br>';

            $arCommentsToCSV[$key]['NAME'] = $arElement['NAME'];
            $arCommentsToCSV[$key]['LINK'] = $linkNews;

            foreach ($arComments[$arElement['ID']] AS $k => $arComment) {
            $arCommentsToCSV[$key]['COMMENTS'][$k]['COMMENTATOR_ID'] = $arComment['UF_ID_COMMENTATOR'];
            $arCommentsToCSV[$key]['COMMENTS'][$k]['TEXT'] = $arComment['UF_TEXT'];
				echo $arComment['UF_ID_COMMENTATOR'] . 'Комментарий: ' . $arComment['UF_TEXT'] . '<br>';
            }
        }

    }

class CSV {
 
    private $_csv_file = null;

    public function __construct($csv_file) {
        if (file_exists($csv_file)) { //Если файл существует
            $this->_csv_file = $csv_file; //Записываем путь к файлу в переменную
        }
        else { //Если файл не найден то вызываем исключение
            throw new Exception("Файл ".$csv_file." не найден"); 
        }
    }
 
    public function setCSV(Array $csv) {
        //Открываем csv для до-записи, 
        //если указать w, то  ифнормация которая была в csv будет затерта
        $handle = fopen($this->_csv_file, "a"); 
 
        foreach ($csv as $value) { //Проходим массив
            //Записываем, 3-ий параметр - разделитель поля
            fputcsv($handle, explode(";", $value), ";"); 
        }
        fclose($handle); //Закрываем
    }
 
    /**
     * Метод для чтения из csv-файла. Возвращает массив с данными из csv
     * @return array;
     */
    public function getCSV() {
        $handle = fopen($this->_csv_file, "r"); //Открываем csv для чтения
 
        $array_line_full = array(); //Массив будет хранить данные из csv
        //Проходим весь csv-файл, и читаем построчно. 3-ий параметр разделитель поля
        while (($line = fgetcsv($handle, 0, ";")) !== FALSE) { 
            $array_line_full[] = $line; //Записываем строчки в массив
        }
        fclose($handle); //Закрываем файл
        return $array_line_full; //Возвращаем прочтенные данные
    }
 
}

     
    try {
        $csv = new CSV("comments.csv"); //Открываем наш csv
        /**
         * Чтение из CSV  (и вывод на экран в красивом виде)
         */
/*        echo "<h2>CSV до записи:</h2>";
        $get_csv = $csv->getCSV();
        foreach ($get_csv as $value) { //Проходим по строкам
            echo "Имя: " . $value[0] . "<br/>";
            echo "Должность: " . $value[1] . "<br/>";
            echo "Телефон: " . $value[2] . "<br/>";
            echo "--------<br/>";
        }*/
     
        /**
         * Запись новой информации в CSV
         */
        foreach($arCommentsToCSV as $key => $news) {
            foreach($news['COMMENTS'] as $k => $comment) {
                $arr = array($news['NAME'] . ";" . $news['LINK'] . ";" . $comment['TEXT']);
                $csv->setCSV($arr);
            }
        }
    }
    catch (Exception $e) { //Если csv файл не существует, выводим сообщение
        echo "Ошибка: " . $e->getMessage();
    }
}

?>