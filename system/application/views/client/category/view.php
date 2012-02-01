<div class="main_title"><h1><?= $category->name?></h1></div>
<div class="onpage">Товаров на странице: <select name="" size="1">
        <option selected="selected">25</option>
        <option>40</option>
        <option>50</option>
    </select></div>
<div class="clear"></div>
<div class="goods"><?php $counter=0;?>
    <?php foreach ($products as $key => $product) :$counter++;?>
    <div class="grid">
        <div class="title"><a href="/product/details/<?=$product['slug']?>"><?=$product['name']?></a></div>
        <div align="center"><img src="/uploads/products/<?=$product['image_small']?>" width="197" height="189" /></div>
        <div class="goodstext"><p><?=$product['description']?></p></div>
        <div class="price"><div class="price1"><?=$product['price_uah']?> грн.</div>
            <div class="price2"><?=$product['price_usd']?>$</div></div>
    </div>
        <?php if($counter%3==0):?><div class="rule"></div><?php endif?>
    <?php endforeach;?>
<div class="clear"></div>
<!--div class="pagination"><a href="#">НАЗАД</a><a href="#">1</a> <a href="#">2</a> <a href="#">3</a> <a href="#">4</a> <a href="#">5</a> <a href="#">6</a> <a href="#">7</a> .. <a href="#">12</a><a href="#">ВПЕРЕД</a></div-->
<div class="pagination"><?=$pagination?></div>
</div> <!--end goods-->

