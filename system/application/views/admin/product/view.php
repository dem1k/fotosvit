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
<a class="button" href="/admin/product/create">Добавить продукт</a><br/>
<a class="button" href="/admin/product/create">Загрузить из файла</a><br/>

<? if (!empty($product[0])): ?>

<table class="extended">
    <thead>
        <tr>
            <th width="10">Артикул</th>
            <th>Фото</th>
            <th>Название</th>
            <th>Управление</th>
        </tr>
    </thead>
    <tbody>
            <? foreach ($product as $item): ?>
        <tr>
            <td><?php echo $item->article ?></td>
            <td><img height="50px"src="/uploads/products/<?=$item->image_small ?>"</td>
            <td><?php echo $item->name ?></td>
            <td>
                <a href="/admin/product/view/<?php echo $item->id ?>">просмотр</a>
                <a href="/admin/product/edit/<?php echo $item->id ?>">редактировать</a>
                <a href="/admin/product/delete/<?php echo $item->id ?>">Удалить</a>
            </td>
        </tr>
            <? endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th>id</th>
            <th>Название</th>
            <th>Описание</th>
            <th>Управление</th>
        </tr>
    </tfoot>
</table>
<div class="clear"></div>

<? else: ?>
Товары еще не добавлены
<? endif ?>
