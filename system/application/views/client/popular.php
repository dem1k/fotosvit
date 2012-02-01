<h1>Популярные товары</h1>
<div class="goods">
     <?php $counter=0;?>
    <?php foreach ($popular as $object):$counter++;?>
    <div  class="grid">
        <div class="title"  style=" min-height: 48px"><a href="/product/details/<?=$object->slug?>/"><?=substr($object->name,0,54)?></a></div>
        <div align="center"><img src="/uploads/products/<?=$object->image_small?>" width="197" height="189" /></div>
        <div style=" height: 80px" class="goodstext"><p ><?=substr($object->description,0,80)?></p></div>
        <div class="price"><div class="price1"><?=$object->price_uah?> грн.</div>
            <div class="price2"><?=$object->price_usd?>$</div></div>
    </div>
    <?php if($counter%3==0):?><div class="rule"></div><?php endif?>
    <?php endforeach;?>
    
    <div class="rule"></div>
</div> <!--end goods-->