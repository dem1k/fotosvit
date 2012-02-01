<!--a class="button" href="/admin/category/create">Создать раздел</a-->
<br/>


<table class="extended">
    <thead>
        <th>ID</th>
        <th></th>
        <th>Actions</th>
    </thead>
<tbody>
<?php foreach($categories as $category):?>
    <tr>
        <td><?=$category['id']?></td>
        <td><?=$category['name']?></td>
        <td><a href="/admin/catalog/showSubsection/<?=$category['id']?>">Подразделы</a></td>
    </tr>
    <?php endforeach?>
</tbody>
    <tfoot>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
    </tfoot>
</table>