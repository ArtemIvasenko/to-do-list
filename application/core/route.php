<?php
class Route
{


	public  function start()
	{
		// контроллер и действие по умолчанию
		$controller_name = 'Task';
		$action_name = 'index';

		$routes = explode('/', $_SERVER['REQUEST_URI']);



		// получаем имя контроллера
		if ( !empty($routes[1]) )
		{
			$controller_name = $routes[1];

            // получаем имя экшена
            if ( !empty($routes[2]) )
            {
                $action_name = $routes[2];
            }

            if(($action_name == 'index') or ($action_name == 'admin')) {
                // получаем номер страницы выборки записей
                if ( !empty($routes[3]) )
                {
                    $page = $routes[3];
                    $page = (int)$page;
                }

                // получаем имя поля выборки записей
                if ( !empty($routes[4]) )
                {
                    $order_field = $routes[4];
                    $order_field = addslashes($order_field);
                }

                // получаем формат сортировки выборки записей
                if ( !empty($routes[5]) )
                {
                    $sort = $routes[5];
                    $sort = addslashes($sort);

                }
            }

            if( ($action_name == 'deleteTask') or ($action_name == 'completed') or ($action_name == 'editTask')) {
                if( !empty($routes[3]) )
                {
                    $id = $routes[3];
                    $id = (int)$id;
                }
            }
		}

		// добавляем префиксы
		$model_name = 'Model_'.$controller_name;
		$controller_name = 'Controller_'.$controller_name;
		$action_name = 'action_'.$action_name;

		// подцепляем файл с классом модели (файла модели может и не быть)

		$model_file = strtolower($model_name).'.php';
		$model_path = "application/models/".$model_file;
		if(file_exists($model_path))
		{
			include "application/models/".$model_file;
		}

		// подцепляем файл с классом контроллера
		$controller_file = strtolower($controller_name).'.php';
		$controller_path = "application/controllers/".$controller_file;
		if(file_exists($controller_path))
		{
			include "application/controllers/".$controller_file;
		}
		else
		{
			/*
			правильно было бы кинуть здесь исключение,
			но для упрощения сразу сделаем редирект на страницу 404
			*/
			Route::ErrorPage404();
		}

		// создаем контроллер
		$controller = new $controller_name;
		$action = $action_name;

		if(method_exists($controller, $action))
		{

		    if (!empty($page)) {
		        $_GET['page'] = $page;
            } else {
		        $_GET['page'] = 1;
            }

		    if (!empty($order_field)) {
		        $_GET['order_field'] = $order_field;
            } else {
		        $_GET['order_field'] = 'id';
            }

		    if (!empty($sort)) {
		        $_GET['sort'] = $sort;
            } else {
		        $_GET['sort'] = 'ASC';
            }

		    if (!empty($id)) {
		        $_GET['id'] = $id;
            }

			// вызываем действие контроллера
			$controller->$action();
		}
		else
		{
			// здесь также разумнее было бы кинуть исключение
			Route::ErrorPage404();
		}

	}

	function ErrorPage404()
	{
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'404');
    }
}