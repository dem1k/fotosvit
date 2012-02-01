<?php header('Content-Type: text/html; charset=UTF-8');
$this->load->view('admin/layout/header'); ?>

<?php if(!empty($message)) : ?>
	<div class="message"><?php echo $message ?></div>
<?php elseif(!empty($errors)) : ?>
	<div class="errors"><?php echo $errors ?></div>
<?php endif; ?>

<?php $this->load->view('admin/layout/sidebar'); ?>

<div class="main">
<?php if(!empty($headline)) : ?>
	<h2 class="headline"><?php echo $headline ?></h2>
<?php endif; ?>
<?php if(!empty($template)) : ?>
	<?php $this->load->view($template); ?>
<?php else : ?>
	Добро пожаловать
<?php endif; ?>
	<div class="actions">
		<a href="#" onclick="history.back();return false;" style="display: block;" class="button">Назад</a>
	</div>
</div>

<div class="clear"></div>

<?php $this->load->view('admin/layout/footer'); ?>