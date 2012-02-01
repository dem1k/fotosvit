<div class="goods">
    <div class="grid_detail">

        <div class="description">
            <div class="title"><a href="#"><?=$product->name?>Зеркальный фотоаппарат
                    Nikon D5000 Body</a></div>
            <div class="price1"><?=$product->price_uah?> грн.</div>
            <div class="price2"><?=$product->price_usd?>$</div><br />

            <div class="add_basket">
                <a href="#">
                    <img src="/assets/img/add_basket.png" width="102" height="34" style="border: none"/>
                </a>
            <p class="product_id" style="display: none"><?=$product->id?></p>
            </div>
        </div>
        <img src="/uploads/products/<?=$product->image_big?>" width="197" height="189" />

    </div>
    <div class="text">
        <h2>Характеристика товара</h2>
    <?=$product->description?>
    </div>
</div> <!--end goods-->