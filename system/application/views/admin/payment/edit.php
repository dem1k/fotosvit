<?=form_open('admin/payment/edit/'.$id.'/');?>
<h1>Переименовать вид оплаты</h1>

Имя вида оплаты <input type="text" name="name" value="<?= set_value('name',(isset($name))?$name:'')?>"/>
<br/>
<?=form_hidden("action","save")?>
<input type="submit" value="Сохранить"/>
<?=form_close()?>