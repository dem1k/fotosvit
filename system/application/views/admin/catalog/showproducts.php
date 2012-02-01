<center>Раздел <strong>"<?=$catName['name']?>"</strong>  Подраздел  <strong>"<?=$subName['name']?>"</strong> </center>
<br/>
<a class="button" href="/admin/catalog/createProduct/<?=$id?>">Создать Product</a>
<br/>
<table class="extended">
    <thead>
        <th>ID</th>
        <th>Image</th>
        <th>Название</th>
        <th>Description</th>
        <th>Действие</th>
    </thead>
<tbody>
    <?php foreach($products as $product):?>
    <tr>
        <td><?=$product['id']?></td>
        <td><img height="135" src="/uploads/products/<?=$product['image']?>" alt="<?=$product['image']?>"> </img></td>
        <td><?=$product['name']?></td>
        <td><?=$product['description']?></td>
        <td><!--<a href="/admin/catalog/showProducts/<?=$product['id']?>">Просмотр </a>--><a href="/admin/catalog/editProduct/<?=$product['id']?>">Редактирование</a><a href="/admin/catalog/deleteProduct/<?=$product['id']?>" >Удаление</a></td>
    </tr>
    <?php endforeach;?>
</tbody>
    <tfoot>
        <th>ID</th>
        <th>Image</th>
        <th>Название</th>
        <th>Description</th>
        <th>Действие</th>
    </tfoot>
</table>