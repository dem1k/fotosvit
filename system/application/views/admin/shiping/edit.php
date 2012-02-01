<?=form_open('admin/shiping/edit/'.$id.'/');?>
<h1>Переименовать вид доставки</h1>

Имя вида доставки <input type="text" name="name" value="<?= set_value('name',(isset($name))?$name:'')?>"/>
<br/>
<?=form_hidden("action","save")?>
<input type="submit" value="Сохранить"/>
<?=form_close()?>