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
    <?php foreach ($products as $product): ?>
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
                <div  class="title"><?= $product['name'] ?></div>
                <a  rel="lightbox-mygallery" target="_blank" title="<?= $product['name'] ?>"  href="/uploads/products/<?= $product['image'] ?>">
                    <img width="150" src="/uploads/products/<?= $product['image'] ?>"/>
                    <br/>
                </a>
                <div  style="max-width:150px" class="description "><?= $product['description'] ?></div>
            </div>
        </td>

            <?php if ($tr): ?></tr><? endif; ?>
    <?php endforeach; ?>

</tr>

</table>
<?php if($page>1) echo $totalpages;?>
<div class="clearer"></div>
<script type="text/javascript">
$().ready(function(){DaGallery.init();})
</script>
