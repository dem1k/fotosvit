<script type="text/javascript">$().ready(function (){
    $(".hidder").hide();
    $('#cat<?=$catId?>').show();
})
</script>


<table width="100%" border="0" align="center">
    <tr>
    <div class="lupa"></div>
    <?php $tr = false;
    $i = 0 ?>
    <?php foreach ($topProducts as $topProduct): ?>
        <?php $i++;
        if ($i >= 5) {
            $tr = true;
            $i = 0;
        } else {
            $tr = false;
        } ?>
        <?php if ($tr): ?><tr><? endif; ?>
    
        <td width="25%">
            <div style="display: inline-block"class="img_desc">
            <a class="gPic"  rel="lightbox-mygallery" target="_blank" title="<?= $topProduct['product_name'] ?>" href="/uploads/products/<?= $topProduct['product_image'] ?>">
                <img class="cat_img" src="/uploads/products/<?= $topProduct['product_image'] ?>"/>
                <br/>
            </a>
            <div  class="title"><?= $topProduct['product_name'] ?>
            </div>
            </div>
        </td>
    
        <?php if ($tr): ?></tr><? endif; ?>
<?php endforeach; ?>

</tr>

</table>

<div class="clearer"></div>
