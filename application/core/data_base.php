<?php

Class data_base {


    public function connect(){
        $tdata_base = new mysqli("a305802.mysql.mchost.ru", "a305802_a", "0ySYMXvhz9", "a305802_a");

        // проверка на успешное подключение и вывод ошибки, если оно не выполнено
        if ($data_base->connect_error) {
            echo "Нет подключения к БД. Ошибка:".mysqli_connect_error();
            exit;
        }

        return $data_base;
    }



}