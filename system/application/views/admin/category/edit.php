<?=form_open('admin/category/edit/'.$id.'/');?>
<h1>Переименовать категорию</h1>

Имя категории <input type="text" name="name" value="<?= set_value('name',(isset($name))?$name:'')?>"/>
<br/>
<?=form_hidden("action","save")?>
<input type="submit" value="Сохранить"/>
<?=form_close()?>