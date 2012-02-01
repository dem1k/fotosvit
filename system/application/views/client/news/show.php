<div class="intro">
    <?php if($news):?>
    <?php foreach($news as $content):?>
    <h2><?=$content['name']?></h2>
    <?=$content['description']?>
    <?=$content['date']?>
    <div class="rule"></div>
<?php endforeach;?>
    <?php else:?>
    <?php show_404()?>
    <?php endif;?>
</div>

