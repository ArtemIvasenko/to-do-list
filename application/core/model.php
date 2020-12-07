<?php


class Model
{
    public $data_base;
    function __construct()
    {
        $this->data_base = new mysqli("a305802.mysql.mchost.ru", "a305802_a", "0ySYMXvhz9", "a305802_a");
        $this->data_base->set_charset("utf8");
    }

	public function get_data()
	{

	}
}