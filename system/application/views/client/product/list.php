<h2>Продукция</h2>
<?php foreach ($product as $item) { ?>
<div class="text">
    <div class="mini_img">
            <?php echo '<img src="/uploads/product/mini_images/' . $item->thumb . '" alt=""/><br> '; ?>
    </div>
                            <?php
                            //Вывод цены
                            if (isset($auth)) {
                                echo '<p>Цена:'.$price['price_for_partner'][$item->id].' '.$key_currency[$exchange].'</p>';
                            }
                            else {
                                echo '<p>Цена:'.$price['price'][$item->id].' '.$key_currency[$exchange].'</p>';
                            }
                            ?>
    <br>
        <?php echo  $item->text;  ?>
        <?php
        if (!empty($item->long_text)) {
            echo '<br>';
            echo anchor('/product/display/' . $item->id, 'Еще более подробное описание', array('title' => 'Более подробное описание!', 'target'=>'_blank'));
        }
        echo '</div>';
        ?>
        <?php } ?>
    <style type="text/css">
        .text{
            padding: 10px;
        }
    </style>
    <script type="text/javascript">
        var flashvars = {"comment":"Тест","st":"/assets/css/video48-1252.txt","file":"/uploads/diagnostika_mini.flv"};
        var params = {wmode:"transparent", allowFullScreen:"true", allowScriptAccess:"always"};
        new swfobject.embedSWF("/assets/swf/uppod.swf", "videoplayer", "560", "340", "9.0.115.0", false, flashvars, params);
        $(function() {
            $('.permits a').lightBox();
        });
    </script>
