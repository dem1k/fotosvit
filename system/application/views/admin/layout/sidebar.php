<?php
//<?php $tab = ($res = get_controller()) ? $res : "main";
$tab = $res ? $res : "category";
//$tab = '';
?>

<div class="column-left">

    <ul class="sidebar">
        <li class="<?= ($tab == "category") ? "active" : "" ?>"><a href="/admin/category">Категории</a></li>
        <li class="<?= ($tab == "product") ? "active" : "" ?>"><a href="/admin/product">Товары</a></li>
        <li class="<?= ($tab == "article") ? "active " : "" ?>"><a href="/admin/article">Статьи</a></li>
        <li class="<?= ($tab == "shiping") ? "active " : "" ?>"><a href="/admin/shiping">Доставки</a></li>
        <li class="<?= ($tab == "payment") ? "active " : "" ?>"><a href="/admin/payment">Оплаты</a></li>
        <li class="<?= ($tab == "news") ? "active " : "" ?>"><a href="/admin/news">Новости</a></li>
        <li class="<?= ($tab == "seo") ? "active" : "" ?>"><a href="/admin/seo">Настройки</a></li>
        <!--li class="<?= ($tab == "personal") ? "active" : "" ?>"><a href="/admin/personal">Персональные данные</a></li-->
        <!--li class="<?= ($tab == "impressum") ? "active" : "" ?>"><a href="/admin/impressum">Impressum</a></li-->
        <!--li class="<?= ($tab == "messages") ? "active" : "" ?>"><a href="/admin/messages">Сообщения</a></li-->
        <li class="last"><a href="/admin/auth/logout">Выход</a></li>
    </ul>

</div>



