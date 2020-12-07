<?php


class model_task extends Model
{

	public function get_tasks($page, $count_task_on_page, $order_field, $sort) {
	    $task_from = $page - 1;
	    $pagination = ' LIMIT '. $count_task_on_page*$task_from.','. $count_task_on_page;

        $order_by = ' ORDER BY '.$order_field.' '.$sort;


	    $result = $this->data_base->query('SELECT * FROM task'.$order_by.$pagination);
	    $result->fetch_assoc();
	    return $result;
	}

	public function get_task($id){
	    $result = $this->data_base->query('SELECT * FROM task WHERE id = '.$id);
        $result = mysqli_fetch_assoc($result);
	    return $result;
    }


	public function get_сount_task()
    {
        $result = $this->data_base->query('SELECT COUNT(*) FROM `task`');

        $result = mysqli_fetch_assoc($result);
        return $result;
    }


    public function add_task($data)
    {
        $result = $this->data_base->query("INSERT INTO `task` (`id`, `name`, `email`, `description`, `completed`, `admin_edit`) VALUES (NULL, '".$data['name']."', '".$data['email']."', '".$data['description']."', '', '')");
        if($result) {
            return true;
        }
    }

    public function update_task($data)
    {
        $result = $this->data_base->query('UPDATE `task` SET `name` = "'.$data['name'].'", `email` = "'.$data['email'].'", `description` = "'.$data['description'].'", `admin_edit` = "1" WHERE id = '.$data['id']);
        if($result) {
            return true;
        }
    }

    public function delete_task($id)
    {
        $result = $this->data_base->query("DELETE FROM `task` WHERE id = ".$id);
        if($result) {
            return true;
        }
    }

    public function completed_task($id)
    {
        $completed = $this->data_base->query('SELECT completed FROM task WHERE id = '.$id);
        $completed = mysqli_fetch_assoc($completed);
        $completed = !$completed['completed'];

        $result = $this->data_base->query('UPDATE `task` SET `completed` = "'.$completed.'" WHERE id = '.$id);

        if($result) {
            return true;
        }

    }
}