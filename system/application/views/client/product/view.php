<style type="text/css">
    #wrapper{
        padding:0 0 0 0;
        width:981px;
    }
    #product_list {
        overflow:hidden;
        padding:0;
        width:100%;
    }
    #product_list .block {
        -moz-background-clip:border;
        -moz-background-inline-policy:continuous;
        -moz-background-origin:padding;
        background:transparent url(/assets/images/bg-sidebar.gif) scroll 0 0;
        overflow:hidden;
        padding-bottom:20px;
        position:relative;
        height: 330px;
    }
    #product_list .block-2 {
        float:right;
        padding-right:10px;
        padding-top:10px;
        width:735px;
    }
    #product_list .block .img {
        height:180px;
        padding-top:15px;
        text-align:center;
        width:140px;
    }
    #product_list .block a {
        -moz-background-clip:border;
        -moz-background-inline-policy:continuous;
        -moz-background-origin:padding;
        background:transparent url(/assets/images/bg-a.gif) no-repeat scroll 0 0;
        bottom:10px;
        float:left;
        font-size:10px;
        font-weight:bold;
        height:37px;
        left:18px;
        padding:11px 0 0 31px;
        position:absolute;
        text-decoration:underline;
        width:90px;
    }
    .car {
        position: relative;
        top:25px;
    }
    .price{
        position: relative;
        left: 10px;
        top:-50px;
    }
    .cart {
        position: relative;
        top:65px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('.id').bind('click', function(event) {
            return false;
        });
        $(".id").click(function(event) {
            ids = event.target.id;
            $.post("/cart", { id: ids },
            function(data){
                ids = '#'+ids;
                if(data == 'done'){
                    $(ids).html("Добавлено");
                }else if(data == 'alredy exist'){
                    $(ids).html("В корзине");
                }
                else{
                    $(ids).html("Ошибка");
                }
            });
        });
        $(function() {
            $('.permits a').lightBox();
        });
    });
</script>
<div id="product_list">
    <?php foreach ($product as $item) { ?>
    <div class="block">
        <h4><?php echo $item->name ?></h4>
        <div class="block-2">
                <?php echo $item->description ?>
        </div>
        <div class="img">
                <?php foreach ($imgs as $img) {
                    if ($img->product_id == $item->id) {
                        echo '<img src="/uploads/product/thumb_' . $img->path . '" alt=" "/>';
                        break;
                    }
                }
                ?>
        </div>
        <div class="price">
                <?php if (isset($auth)) {
                    switch ($exchange) {
                        case 'ua':
                            echo '<p>&nbsp;&nbsp;&nbsp;Цена: ' . $product[0]->price2 . '&nbsp;грн.</p>';
                            break;
                        case 'ru':
                            echo '<p>&nbsp;&nbsp;&nbsp;Цена: ' . $product[0]->price2_ru . '&nbsp;р.</p>';
                            break;
                        case 'usa':
                            echo '<p>&nbsp;&nbsp;&nbsp;Цена: ' . $product[0]->price2_usa . '&nbsp;usd</p>';
                            break;
                        default:
                            $exchange = 'ua';
                    }
                }
                else {
                    switch ($exchange) {
                        case 'ua':
                            echo '<p>&nbsp;&nbsp;&nbsp;Цена: ' . $product[0]->price1 . '&nbsp;грн.</p>';
                            break;
                        case 'ru':
                            echo '<p>&nbsp;&nbsp;&nbsp;Цена: ' . $product[0]->price1_ru . '&nbsp;р.</p>';
                            break;
                        case 'usa':
                            echo '<p>&nbsp;&nbsp;&nbsp;Цена: ' . $product[0]->price1_usa . '&nbsp;usd</p>';
                            break;
                        default:
                            $exchange = 'ua';
                    }
                }
                ?>
        </div>
        <div class="car"><a href="#" class="id"<?php echo 'id="' . $item->id . '"';?>>В корзину</a></div>
        <div class="carts">    <?php echo anchor('product/view/' . $item->id, 'подробнее');?></div>
        <div class="cart"><a href="/cart/buy" class="buy">Оформить</a></div>
    </div>
        <?php } ?>
</div>
