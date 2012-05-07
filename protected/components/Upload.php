<?php

class Upload {

    private $dir = "/";
    private $name;
    private $FILES;
    private $allowedType = array("jpg", "gif", "bmp", "jpeg", "png", "pps","doc","docx","xls","pdf","txt","rar","zip");
    private $errors;
    private $errorsMessage = array(1 => "Размер загружаемого файла превышает допустимый размер.",
                                   2 => "Размер загружаемого файла превышает допустимый размер.",
                                   3 => "Файл был загружен лишь частично.",
                                   4 => "Файл не был загружен.",
                                   6 => "Файл не был загружен.",
                                   7 => "Файл не был загружен.",
                                   8 => "Файл не был загружен.");

    function __construct($dir="/") {
        $this->dir = $dir;
    }

    /**
     * устанавливаем дирректорию загрузки файла
     */
    function setDir($dir) {
        $this->dir = $dir;
    }

    /**
     * Устанавлиаем доступные расширения
     * @param <type> $type
     */
    function setAllowedType($type) {
        if (is_array($type)) {
            $this->allowedType = $type;
        } else {
            $this->allowedType = explode(",", $type);
        }
    }

    /**
     * загрузка файла
     * @param $tmpName
     * @param $name
     * @param $replacement
     */
    private function upload($tmpName, $name) {
        $name = $this->substitute(self::translit($name));

        if ($this->typeChecking($name))
            if (move_uploaded_file($tmpName, $this->dir . $name)) {
                return $name;
            } else {
                return false;
            }
        return false;
    }

    function uploads($FILES) {
        $this->FILES = $FILES;

        if (!is_array($this->FILES['name'])) {
            return $this->uploadsOneFile();
        } else {
            return $this->uploadsManyFiles();
        }
    }

    /**
     * загрузка одного файла
     */
    function uploadsOneFile() {

        if ($this->FILES['error'] != 0) {
            $this->errors[] = $this->errorsMessage[$this->FILES['error']];
            return false;
        }

        $result = $this->upload($this->FILES['tmp_name'], $this->FILES['name']);
        if ($result != false) {
            $this->FILES['nameTranslit'] = $result;
            return true;
        }
        return false;
    }

    /**
     * загрузка нескольких файлов
     */
    function uploadsManyFiles() {
        $coutFiles = count($this->FILES['name']);
        for ($i = 0; $i < $coutFiles; $i++) {
            if ($this->FILES['error'][$i] == 0) {
                $result = $this->upload($this->FILES['tmp_name'][$i], $this->FILES['name'][$i]);

                if ($result != false) {
                    $this->FILES['nameTranslit'][$i] = $result;
                } else {
                    $this->errors[] = $this->FILES['name'];
                }
            } else {
                $this->errors[] = $this->errorsMessage[$this->FILES['error']];
            }
        }

        return true;
    }

    /**
     * проверяем, разрешен ли данный файл к загрузке
     */
    function typeChecking($fileName) {
        preg_match("#([\w()-_]+)\.([\w]{1,4})$#i", $fileName, $arrayNameFiles);
        $nameEnd = strtolower($arrayNameFiles[2]);
        if (in_array($nameEnd, $this->allowedType)) {
            return true;
        } else {
            $this->errors[] = "Файлы с расширением (<b>{$fileName}</b>) не разрешенны к загрузке.";
        }
        return false;
    }

    /**
     * ищет в каталоге файлы с таким же названием дописывает номер(равный количеству файлов с таким названием) в конец
     * @param $name
     */
    function substitute($name) {

        $files = scandir($this->dir);
        unset($files[0]);
        unset($files[1]);

        $i = 0;
        $newName = $name;

        preg_match("#([\w()-_]+)\.([\w]{1,4})#i", $name, $arrayNameFiles);
        $nameStart = $arrayNameFiles[1];
        $nameEnd = $arrayNameFiles[2];

        while (in_array($newName, $files)) {
            $newName = "{$nameStart}({$i}).{$nameEnd}";
            $i++;
        }
        return $newName;
    }

    /**
     * возвращаем информацию о файле
     */
    function getFilesInfo() {
        return $this->FILES;
    }

    /**
     * возвращаем ошибки
     */
    public function errors() {
        return $this->errors;
    }

    /**
     * переводим текст в транслит
     * @param $text
     */
    public static function translit($text) {
        $rus = array("а", "б", "в",
                     "г", "ґ", "д", "е", "ё", "ж",
                     "з", "и", "й", "к", "л", "м",
                     "н", "о", "п", "р", "с", "т",
                     "у", "ф", "х", "ц", "ч", "ш",
                     "щ", "ы", "э", "ю", "я", "ь",
                     "ъ", "і", "ї", "є", "А", "Б",
                     "В", "Г", "ґ", "Д", "Е", "Ё",
                     "Ж", "З", "И", "Й", "К", "Л",
                     "М", "Н", "О", "П", "Р", "С",
                     "Т", "У", "Ф", "Х", "Ц", "Ч",
                     "Ш", "Щ", "Ы", "Э", "Ю", "Я",
                     "Ь", "Ъ", "І", "Ї", "Є", " ");
        $lat = array("a", "b", "v",
                     "g", "g", "d", "e", "e", "zh", "z", "i",
                     "j", "k", "l", "m", "n", "o", "p", "r",
                     "s", "t", "u", "f", "h", "c", "ch", "sh",
                     "sh'", "y", "e", "yu", "ya", "_", "_", "i",
                     "i", "e", "A", "B", "V", "G", "G", "D",
                     "E", "E", "Zh", "Z", "I", "J", "K", "L",
                     "M", "N", "O", "P", "R", "S", "T", "U",
                     "F", "H", "C", "Ch", "Sh", "Sh'", "Y", "E",
                     "Yu", "Ya", "_", "_", "I", "I", "E", "_");
        $text = str_replace($rus, $lat, $text);
        return(preg_replace("#[^a-z0-9._-]#i", "", $text));
    }

}