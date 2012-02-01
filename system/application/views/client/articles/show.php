<div class="intro">
    <?php if($content):?>
    <h1><?=$content->title?></h1>
    <?=$content->content?>
    <?php else:?>
    <?php show_404()?>
    <?php endif;?>
</div>

