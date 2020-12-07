<div class="container">
    <h1>Добро пожаловать!</h1>
    <a href="/task/create">Создать задачу</a><br>
    <?php
    if($login == true) { ?>
    <a href="/user/admin">Управление задачами</a><br>
    <a href="/user/logout">Выйти</a><br>
    <?php } else { ?>
        <a href="/user/signin">Авторизоваться</a><br>
    <?php }?>

    <?php if($count_task > 0) { ?>
    <table class="task_list">
        <tr class="title">
            <th><a href="/task/index/<?=$page?>/name/<?php if (($order_field == 'name') && ($sort == 'ASC')) { echo 'DESC'; } else { echo 'ASC'; } ?>">Имя</a></th>
            <th><a href="/task/index/<?=$page?>/email/<?php if (($order_field == 'email') && ($sort == 'ASC')) { echo 'DESC'; } else { echo 'ASC'; } ?>">Email</a></th>
            <th>Описание</th>
            <th><a href="/task/index/<?=$page?>/completed/<?php if (($order_field == 'completed') && ($sort == 'ASC')) { echo 'DESC'; } else { echo 'ASC'; } ?>">Статус</th>

        </tr>
        <?php
           foreach($data as $row)
           {
               echo '<tr>'.
                        '<td>'.$row['name'].'</td>'.
                        '<td>'.$row['email'].'</td>'.
                        '<td>'.$row['description'].'</td>';


               if ($row['completed'] == 1) {
                   echo '<td>Выполнено<br>';
               } else {
                   echo '<td>Не выполнено<br>';
               }

               if ($row['admin_edit'] == 1) {
                   echo 'Отредактировано Администратором<br>';
               }

               echo '</td>';
           }
        ?>
    </table>

    <?php if($count_page > 1) { ?>
    <ul class="pagination">
      <li><a href="/task/index/1/<?=$order_field?>/<?=$sort?>">&laquo;</a></li>
      <?php for ($i=1; $i <= $count_page; $i++) { ?>
                <li><a href="/task/index/<?=$i?>/<?=$order_field?>/<?=$sort?>"><?=$i?></a></li>
          <?php if ($i == $count_page) { ?>
            <li><a href="/task/index/<?=$i?>/<?=$order_field?>/<?=$sort?>">&raquo;</a></li>
          <?php } ?>
      <?php } ?>
    </ul>
    <?php } ?>
    <?php } else { ?>
        <p>Нет задач! Создайте новую!</p>
    <?php } ?>
</div>