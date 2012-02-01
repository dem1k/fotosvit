<h1>Оформление заказа</h1>
<div class="order_page">
    <div class="rule"></div>
    <strong>Контактные данные получателя</strong>
    <div class="order_table"> <table width="100%" border="0">
            <tr>
                <td width="180"><div class="field">Имя</div><div class="asterisk">*</div></td>
                <td><input type="text" size="40" /></td>
            </tr>
            <tr>
                <td><div class="field">Фамилия</div><div class="asterisk">*</div></td>
                <td><input type="text" size="40" /></td>
            </tr>
            <tr>
                <td><div class="field">Отчество</div><div class="asterisk">*</div></td>
                <td><input type="text" size="40" /></td>
            </tr>
            <tr>
                <td><div class="field">Телефон</div><div class="asterisk">*</div></td>
                <td><input type="text" size="40" /></td>
            </tr>
            <tr>
                <td><div class="field">Эл.почта</div><div class="asterisk">*</div></td>
                <td><input type="text" size="40" /></td>
            </tr>
            <tr>
                <td><div class="field">Город</div><div class="asterisk">*</div></td>
                <td><input type="text" size="40" /></td>
            </tr>
            <tr>
                <td><div class="field">Адрес</div></td>
                <td><input type="text" size="40" /></td>
            </tr>
        </table></div><!--order table-->
    <div class="delivery"><strong>Cпособ доставки</strong> <select name="delivery" class="delivery_list">
            <?php foreach($shipings as $shiping):?>
            <option><?=$shiping['name']?></option>
            <?php endforeach;?>
            <!--option>транспортное агентство</option>
            <option>почтовая посылка</option>
            <option>самовывоз</option-->
        </select></div><!--delivery-->
    <div class="pay"><strong>Способ оплаты</strong><select name="pay" class="pay_list">
            <?php foreach($payments as $payment):?>
            <option><?=$payment['name']?></option>
            <?php endforeach;?>
            <!--option>кредитная карта</option>
            <option>денежный перевод</option>
            <option>вебмани</option>
            <option>яндекс деньги</option-->
        </select></div>
    <div class="adres"><strong>Адрес склада</strong><input name="adres" type="text" class="adres" size="28" /></div>
    <div class="mailduplicate">Продублировать заказ на мою почту </div><div class="mailcheck"><input name="mailcheck" type="checkbox" class="mailcheck" value="" /></div>
    <div class="clear"></div>
    <div class="order_button"><a href="#"><img src="/assets/img/img/orderok.png" width="169" height="34" /></a></div>

</div><!--order_page-->