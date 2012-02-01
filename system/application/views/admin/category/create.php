<?=form_open('admin/category/create/');?>
<h1>Создать категорию</h1>

Имя категории <input type="text" name="name" value="<?= set_value('name')?>"/>
<br/>
<?=form_hidden("action","save")?>
<input type="submit" value="Сохранить"/>
<?=form_close()?>