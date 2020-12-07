<?php
session_start();

include "application/models/model_task.php";

class Controller_User extends Controller {


    public $error = null;
    public $alert = null;

    private $users = [
        ['admin', '123'],
        ['pisa', '123'],
    ];

    public function __construct()
	{
		$this->view = new View();
	}

	public function action_signin(){
        if ($this->isLogin() == true) {
             $this->view->redirect('/user/admin');
        }

        $this->view->title = 'Авторизация';

        $data = array();

        if (!empty($_POST)) {
	        $data['login'] = $_POST['login'];
            $data['password'] = $_POST['password'];

	        if($this->validate($data) == true) {
	            if ($this->verify_signin($data['login'], $data['password']) == true) {

	                $auth_key = 'auth_'.md5($data['login'].$data['password']);
	                $_SESSION["login"] = $data['login'];
	                $_SESSION["auth_key"] = $auth_key;
	                $this->view->redirect('/user/admin');

                }
            }

        } else {
	        $data['login'] = "";
            $data['password'] = "";
        }

        $this->view->generate('signin_view.php', 'template_view.php', ['data'=>$data, 'alert'=>$this->alert, 'error'=>$this->error]);

    }


    public function validate($data) {

        if($data['login'] == '') {
            $this->error = "Поле Login не заполенно!";
            return false;
        }

        if($data['password'] == '') {
            $this->error = "Поле password не заполенно!";
            return false;
        }

        return true;
    }

    public function verify_signin($login,$password){
       $found = false;
       $db_login = "";
       $db_password = "";

       foreach ($this->users as $user) {
           if ($user[0] == $login) {
               $found = true;
               $db_login = $user[0];
               $db_password = $user[1];
               break;
           }
       }

       if (($found == true) && ($db_password == $password)) {
           return true;

       } else {
           $this->error = "Пользователь с данным логином и паролем не найден!";
           return false;
       }

    }

    public function isLogin(){
        if(empty($_SESSION['auth_key']) || empty($_SESSION['login'])) {
            return false;
        }

        $found = false;
        $db_login = "";
        $db_password = "";

        foreach ($this->users as $user) {
           if ($user[0] == $_SESSION['login']) {
               $found = true;
               $db_login = $user[0];
               $db_password = $user[1];
               break;
           }
        }

        if ($found == true) {
            $auth_key = 'auth_'.md5($db_login.$db_password);
            if($_SESSION['auth_key'] == $auth_key) {
                return true;
            } else {
                return false;
            }
       } else {
            unset($_SESSION['auth_key']);
            unset($_SESSION['login']);

            return false;
        }
    }

    public function action_logout(){

        if($this->isLogin() == true) {
            unset($_SESSION['auth_key']);
            unset($_SESSION['login']);

        }
        $this->view->redirect('/task/index');

    }

    public function action_admin(){

        if ($this->isLogin() == false) {
             return $this->view->redirect('/user/signin');
        }

        $this->view->title = 'Управление задачами!';

        $task = new model_task();


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

	    $data = $task->get_tasks($page, $count_task_on_page, $order_field, $sort);
	    $count_task = $task->get_сount_task();
	    $count_page = ceil($count_task['COUNT(*)'] / $count_task_on_page);


	    if(!empty($_SESSION["auth_key"])) {
	        $login = true;
        } else {
	        $login = false;
        }

        $this->view->generate('admin_view.php', 'template_view.php', ['data' => $data, 'count_page' => $count_page, 'page'=>$page, 'order_field'=>$order_field, 'sort'=>$sort, 'login'=>$login, 'count_task'=>$count_task['COUNT(*)']]);
    }

    public function action_deleteTask()
    {
        if ($this->isLogin() == false) {
             return $this->view->redirect('/user/signin');
        }

        if(empty($_GET['id'])) {
             return $this->view->redirect('/user/admin');
        }

        $task = new model_task();

        $task->delete_task($_GET['id']);

        return $this->view->redirect('/user/admin');

    }

    public function action_editTask(){
        if ($this->isLogin() == false) {
             return $this->view->redirect('/user/signin');
        }

        if(empty($_GET['id'])) {
             return $this->view->redirect('/user/admin');
        }

        $task = new model_task();
        $data = $task->get_task($_GET['id']);


        if (!empty($_POST)) {
	        $data['name'] = $_POST['name'];
            $data['email'] = $_POST['email'];
            $data['description'] = $_POST['description'];

            $data = $this->defender_xss($data);

	        if($this->validateTask($data) == true) {
                if ($task->update_task($data) == true) {
                    $this->alert = "Задание успешно обновлено";
                } else {
                    $this->error = "Ошибка обновления";
                }
            }
        }

	    $this->view->title = 'Редактирование задачи!';

        $this->view->generate('edit_task_view.php', 'template_view.php', ['error' => $this->error, 'alert' => $this->alert, 'data'=>$data, 'login'=>$_SESSION["auth_key"]]);

    }


    public function action_completed(){

        if ($this->isLogin() == false) {
             return $this->view->redirect('/user/signin');
        }

        if(empty($_GET['id'])) {
             return $this->view->redirect('/user/admin');
        }

        $task = new model_task();

        if ($task->completed_task($_GET['id']) == true ) {
            $this->view->redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function validateTask($data) {

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
