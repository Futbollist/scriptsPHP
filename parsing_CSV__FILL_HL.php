<?
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

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
        $csv = new CSV("censore.csv"); //Открываем наш csv

/*        echo "<h2>CSV до записи:</h2>";
        $get_csv = $csv->getCSV();
        foreach ($get_csv as $value) { //Проходим по строкам
            echo $value[0] . '<br>';
        }*/

		$entity_data_class = \Spichka\General::GetEntityDataClass(ID_HIGHLOAD_CENSORE);

        $get_csv = $csv->getCSV();
        foreach ($get_csv as $value) {
			$data = array(
				"UF_WORD" => $value[0],
			);

			$result = $entity_data_class::add($data);
        }


    }
    catch (Exception $e) { //Если csv файл не существует, выводим сообщение
        echo "Ошибка: " . $e->getMessage();
    }


?>