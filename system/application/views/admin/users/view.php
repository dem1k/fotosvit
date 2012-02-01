<script type="text/javascript">
$(function(){
// Add tables functionality (like sorting, filtering, and paging)
  $('table.extended').dataTable({
    "bAutoWidth": false,
    "iDisplayLength": 25,
    "oLanguage": {
      "sLengthMenu": "Показывать _MENU_ записей на стр.",
      "sZeroRecords": "К сожалению ничего не найдено.",
      "sInfo": "Показано с _START_ по _END_ из _TOTAL_ записей",
      "sInfoEmtpy": "0 из 0 записей",
      "sInfoFiltered": "(найденых из всего _MAX_ записей)",
      "sSearch": "Поиск"
    },
    "aaSorting": [[ 0, "desc" ]]
  });
});
</script>
<a class="button" href="/admin/users/create">Создать пользователя</a>

<?php if(!empty($users[0])): ?>

<table class="extended">
	<thead>
		<tr>
			<th>Имя</th>
			<th>Логин</th>
			<th>Управление</th>
		</tr>
	</thead>
	<tbody>
<?php foreach($users as $item): ?>
		<tr>
			<td><?php echo $item->username ?></td>
			<td><?php echo $item->login ?></td>
			<td>
				<a href="/admin/users/edit/<?php echo $item->id ?>">редактировать</a>
				<a href="/admin/users/delete/<?php echo $item->id ?>">Удалить</a>
			</td>
		</tr>
<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th>Имя</th>
			<th>Логин</th>
			<th>Управление</th>
		</tr>
	</tfoot>
</table>
<div class="clear"></div>

<?php else: ?>
	Пользователей нету.
<?php endif ?>
