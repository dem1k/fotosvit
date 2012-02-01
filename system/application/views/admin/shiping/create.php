<?=form_open('admin/shiping/create/');?>
<h1>Создать вид доставки</h1>

Имя вида доставки <input type="text" name="name" value="<?= set_value('name')?>"/>
<br/>
<?=form_hidden("action","save")?>
<input type="submit" value="Сохранить"/>
<?=form_close()?>