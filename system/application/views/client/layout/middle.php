<?php
$this->load->view('client/layout/menu');
if (!empty($template)) :?>
<div class="content">
        <?php    $this->load->view($template);?>
<?php else : ?>
<div class="content">
	Нет материаллов на данный момент. Попробуйте позже.
<?php endif;
?>
<?php if (isset($popular) ) if ($popular)
    $this->load->view('client/popular');
?>
</div>

</div> <!-- end MAIN-->

<?php if (isset($topMenu) ) if ($topMenu)
    $this->load->view('client/layout/topmenu');
?>
