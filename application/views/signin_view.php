<?php
?>
<div class="container">
    <h1>Авторизация</h1>
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
            <label>Login</label>
            <input name="login" type="text" class="form-control" value="<?=$data['login']?>">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input name="password" type="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>

</div>

