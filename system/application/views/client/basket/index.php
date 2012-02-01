<h1>Корзина</h1>
<div class="basket_page">

    <table width="100%" cellspacing="0" class="basket_page">
        <tr align="center">
            <td width="266" height="31" bgcolor="#2a5392">Наименование</td>
            <td width="88" height="31" bgcolor="#2a5392">Цена за ед.</td>
            <td width="87" height="31" bgcolor="#2a5392">Количество</td>
            <td width="101" height="31" bgcolor="#2a5392">Сумма</td>
            <td width="143" bgcolor="#2a5392">&nbsp;</td>
        </tr>

        <?php
        if(!empty($basket_prod)):
            foreach ($basket_prod as $prod): ?>

        <tr>
            <td height="112">
                <img src="/uploads/products/<?=$prod['image_small']?>" width="103" height="98" align="middle" />
                <div class="basket_page_title">
                    <a href="/product/details/<?=$prod['slug']?>">
                                <?=$prod['name']?>
                    </a>
                </div>
                <p class="product_id" style="display: none"><?=$prod['id']?></p>
            </td>
            <td height="112">
                <div class="price">
                    <strong>
                        <span class="price_uah"><?=$prod['price_uah']?></span> грн.
                    </strong>
                    <br />

                    <span class="price_usd">  <?=$prod['price_usd']?></span> $
                </div>
            </td>
            <td height="112">
                <div class="price">
                    X
                </div>
                <input type="text" size="7" value="<?=$prod['qty']?>"/>
            </td>
            <td height="112">
                <div class="price">
                    <strong>
                        <span class="sum_price_uah"> <?=$prod['total_price_uah']?> </span>грн.
                    </strong>
                    <br />

                    <span class="sum_price_usd"> <?=$prod['total_price_usd']?></span>  $
                </div>
            </td>
            <td>
                <a href="#">
                    <img class="remove_btn" prod_id="<?=$prod['id']?>" src="/assets/img/del.png" width="98" height="39" />
                </a>
            </td>
        </tr>
            <?php endforeach;
        else: echo ' Нет товаров в корзине';
        endif;
        ?>

        <tr>
            <td height="23" colspan="3"><div class="price"><strong>Итого:</strong></div></td>
            <td><div class="price">
                    <strong id="table_total_price"><?= isset($basket['basket_total_price'])?$basket['basket_total_price']:$basket['basket_total_price']=0?> грн.</strong>
                </div>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td height="110" colspan="5">
                <div class="bask_button"><a href="/"><img src="/assets/img/back.png" width="169" height="34" /></a>

                    <?php if($basket['basket_total_price']>1):?>
                    <a id="fwrd"  href="/basket/order"><img src="/assets/img/fwrd.png" width="169" height="34" /></a>
                    <?php endif;?>
                </div>

            </td>
        </tr>
    </table>

</div><!--basket_page-->