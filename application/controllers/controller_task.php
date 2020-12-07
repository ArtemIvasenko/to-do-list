<?php

class Controller_Task extends Controller
{

    public $error = null;
    public $alert = null;

    public function __construct()
	{
		$this->model = new model_task();
		$this->view = new View();
	}

	public function action_index()
	{
        $this->view->title = 'Список задач!';

	    if(!empty($_GET['page'])) {
	        $page = $_GET['page'];
        }

	    if(!empty($_GET['order_field'])) {
	        $order_field = $_GET['order_field'];
        }

	     if(!empty($_GET['sort'])) {
	        $sort = $_GET['sort'];
	     }

	    $count_task_on_page = 3;

	    $data = $this->model->get_tasks($page, $count_task_on_page, $order_field, $sort);
	    $count_task = $this->model->get_сount_task();
	    $count_page = ceil($count_task['COUNT(*)'] / $count_task_on_page);

	    session_start();
	    if(!empty($_SESSION["auth_key"])) {
	        $login = true;
        } else {
	        $login = false;
        }

	    $this->view->generate('task_view.php', 'template_view.php', ['data' => $data, 'count_page' => $count_page, 'page'=>$page, 'order_field'=>$order_field, 'sort'=>$sort, 'login'=>$login, 'count_task'=>$count_task['COUNT(*)']]);
	}

	public function action_create()
	{
	    $data = array();

	    if (!empty($_POST)) {
	        $data['name'] = $_POST['name'];
            $data['email'] = $_POST['email'];
            $data['description'] = $_POST['description'];

            $data = $this->defender_xss($data);


	        if($this->validate($data) == true) {
                if ($this->model->add_task($data) == true) {
                    $this->alert = "Новое задание успешно добавлено!";
                    $data['name'] = "";
                    $data['email'] = "";
                    $data['description'] = "";
                }
            }
        } else {
	        $data['name'] = "";
	        $data['email'] = "";
	        $data['description'] = "";
        }

	    $this->view->title = 'Создание задачи!';

	    $this->view->generate('create_task_view.php', 'template_view.php', ['error' => $this->error, 'alert' => $this->alert, 'data'=>$data]);
    }


    public function validate($data) {

        if($data['email'] == '') {
            $this->error = "Поле email не заполенно!";
            return false;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error = "Поле email заполнено не корректно!";
            return false;
        }

        if($data['name'] == '') {
            $this->error = "Поле Имя не заполенно! Укажите исполнителя.";
            return false;
        }

        return true;
    }

    public function defender_xss($arr){
        $filter = array("<", ">");
        foreach($arr as $num=>$xss){
            $arr[$num]=str_replace ($filter, "|", $xss);
        }
        return $arr;
    }


}