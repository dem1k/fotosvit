<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?= isset($seo->title)?$seo->title:'Специализированный магазин фототоваров' ?></title>
        <meta name="title" content="<?= isset($seo->title)?$seo->title:'Специализированный магазин фототоваров' ?>" />
        <meta name="keywords" content="<?= isset($seo->keywords)?'Специализированный магазин фототоваров - '.$seo->keywords:'Специализированный магазин фототоваров' ?>" />
        <meta name="description" content="<?= isset($seo->description)?$seo->description:'Специализированный магазин фототоваров' ?>" />
        <link rel="stylesheet" href="/assets/css/style.css" type="text/css" media="screen, projection" />
        <script type="text/javascript" src="/assets/js/jquery.js"></script>
        <script type="text/javascript" src="/assets/js/basket.js"></script>
        <script type="text/javascript">
            function MM_swapImgRestore() { //v3.0
                var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
            }
            function MM_preloadImages() { //v3.0
                var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
                    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
                        if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
                }

                function MM_findObj(n, d) { //v4.01
                    var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
                        d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
                    if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
                    for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
                    if(!x && d.getElementById) x=d.getElementById(n); return x;
                }

                function MM_swapImage() { //v3.0
                    var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
                    if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
                }
        </script>
    </head>

    <body onload="MM_preloadImages('/assets/img/price_hover.png')">

        <div id="main">
            <div class="top">
                <div class="logo"></div>
                <div class="slogan"><?= isset($seo->slogan)?$seo->slogan:'Специализированный магазин фототоваров' ?></div>
                <?php if(isset($auth_enabled)):?><?php if(!isset($client)):?><div class="login"><a href="#">Войти</a>/<a href="#">Регистрация</a></div><?php endif;?><?php endif;?>
                <div class="basket">
                    <div class="basket_text">
                        <div class="bask">
                            В корзине:
                        </div>
                        <br />
                        <div class="bask" id="bask_qty">
                            <?= isset($basket['basket_total'])?$basket['basket_total']:'0'?>
                        </div>
                        товаров на сумму:
                        <div  class="bask bask_total_price" id="bask_total_price" style="color:black">
                            <?= isset($basket['basket_total_price'])?$basket['basket_total_price']:$basket['basket_total_price']=0?> грн.
                        </div>
                        <div class="curensy">
                            Курс: 1$ = <?=isset ($seo->kurs)?$seo->kurs:"0.00"?> грн.
                        </div>
                    </div>
                    
                    <div><a style="top: 40px;padding-left: 20px;color: red;position: relative; " href="/basket/">Показать корзину</a></div>
                </div>
            </div>
            <div class="clear"></div><!--end top-->