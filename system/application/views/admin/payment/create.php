<?=form_open('admin/payment/create/');?>
<h1>Создать вид оплаты</h1>

Имя вида оплаты <input type="text" name="name" value="<?= set_value('name')?>"/>
<br/>
<?=form_hidden("action","save")?>
<input type="submit" value="Сохранить"/>
<?=form_close()?>