<script src="/assets/admin/ckeditor/ckeditor.js" type="text/javascript"></script>

<?php
echo form_open('/admin/users/edit/'.$users[0]['id']);
?>

<table id="tablevent">
    <tr>
        <td><input type="submit" value="Сохранить" name="submit" class="button"></td>
        <td><input type="hidden" name="action" value="save" /></td>
    </tr>
    <tr>
        <td>Имя пользователя:</td>
        <td>
            <input type="text" name="username" value="<?php echo set_value('username', $users[0]['username']); ?>" size="40"/>
            <?php echo form_error('username', '<div class="error">', '</div>'); ?>
        </td>
    </tr>
    <tr>
        <td>Логин:</td>
        <td>
            <input type="text" name="login" value="<?php echo set_value('login', $users[0]['login']); ?>" size="40"/>
            <?php echo form_error('login', '<div class="error">', '</div>'); ?>
        </td>
    </tr>
    <tr>
        <td>Пароль:</td>
        <td>
            <input type="text" name="password" value="<?php echo set_value('password', $users[0]['open_password']); ?>" size="40"/>
            <?php echo form_error('password', '<div class="error">', '</div>'); ?>
        </td>
    </tr>
    <tr>
        <td>Email:</td>
        <td>
            <input type="text" name="email" value="<?php echo set_value('email', $users[0]['email']); ?>" size="40"/>
            <?php echo form_error('email', '<div class="error">', '</div>'); ?>
        </td>
    </tr>
</table>
<h3>Выберите права для пользователя:</h3>
<table class="premission" CELLSPACING="20" BORDER="3">
    <?php
    foreach ($permission as $key=>$item) { //
        foreach ($item as $value) {
            if (gettype($value) != "array") {
                echo '<tr><td>' . $value . '</td><td><ul>';
            }
            else {
                foreach ($value as $method=>$description) {
                        ?>
    <li><input type="checkbox" name="<?php echo $method . '-' . $key; ?>"
     <?php echo $this->form_validation->set_checkbox($method . '-' . $key, '1'); ?> value="1"
     <?php if(array_key_exists( $key.'/'.$method , $permissions) || array_key_exists($method, $permission_status)){ echo 'checked="checked"';} ?>><?php echo $description ?></li>

                        <?php

                }
            }
        } ?>
    </ul></td>
    </tr>
        <?php }
    ?>

</table>
</form>