<a class="button" href="/admin/payment/create">Создать вид оплаты </a><br/>
<table width="100%" border="1px solid " cellspacing="0" cellpadding="0">
            <thead>
            <th width="20px">ID</th>
            <th>Название</th>
            <th width="200px">Действие</th>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment):?>
                <tr>
                    <td><?=$payment['id']?>
                    </td>
                    <td><?=$payment['name']?>
                    </td>
                    <td>
                        <a href="/admin/payment/edit/<?=$payment['id']?>/">Редактировать</a>
                        <a  onclick="return confirm('Удалить вид оплаты <?=$payment['name']?>')"
                            href="/admin/payment/delete/<?=$payment['id']?>/">Удалить</a>
                    </td>
                </tr>
                <?endforeach;?>
            </tbody>
            <tfoot> <th>ID</th>
            <th>Название</th>
            <th>Действие</th></tfoot>
        </table>