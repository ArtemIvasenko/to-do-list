<div class="container">
    <h1>Создание новой задачи!</h1>
    <a href="task/index">Вернуться к списку задач</a><br><br>
    <?php if ($error != null) { ?>
        <div class="alert alert-danger" role="alert">
          <strong>О нет!</strong> <?=$error ?>
        </div>
    <?php } ?>

    <?php if ($alert != null) { ?>
        <div class="alert alert-success" role="alert">
          <?=$alert ?>
        </div>
    <?php } ?>
    <form action="" method="POST">
        <div class="form-group">
            <label>Email адрес</label>
            <input name="email" type="text" class="form-control" value="<?=$data['email']?>">
        </div>
        <div class="form-group">
            <label>Имя пользователя</label>
            <input name="name" type="text" class="form-control" value="<?=$data['name']?>">
        </div>
        <div class="form-group">
            <label for="exampleTextarea">Описание задачи</label>
            <textarea class="form-control" name="description" rows="3" name="description"><?=$data['description']?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
</div>