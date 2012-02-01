 <div class="left">
    <div class="search">
      <input type="text" class="search" value="Поиск по товарам" />
    </div>
    <div class="list_t"></div>
    <div class="list">
        <ul>
            <?php
            if(isset($sidebar))
                foreach($sidebar as $val):
                ?>
      <li><a href="/category/<?=$val['slug']?>/"><?=$val['name']?></a></li>
      <?php endforeach;?>
	<!--li><a href="#">фотоальбомы</a></li>
	<li><a href="#">фоторамки</a></li>
	<li><a href="#">элементы питания</a></li>
	<li><a href="#">зарядные устройства</a></li>
	<li><a href="#">карты памяти</a></li>
	<li><a href="#">сумки и чехлы</a></li>
	<li><a href="#">носители информации</a></li>
	<li><a href="#">фотобумага</a></li>
	<li><a href="#">фотопленка</a></li></ul-->
    </div> <!--end list-->
     <div class="list_b"></div>
    <div class="pricebutton">
        <a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('price','','/assets/img/price_hover.png',1)">
            <img src="/assets/img/price.png" alt="price button" name="price" width="169" height="42" border="0" id="price" />
        </a>
    </div>
    <div class="banner"><img src="/assets/img/banner.png" width="169" height="88" /></div>
    <div class="banner"><img src="/assets/img/banner.png" width="169" height="88" /></div>
</div> <!--end left-->