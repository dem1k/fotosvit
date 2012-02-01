<table class="extended">
<thead>
<th>id</th>
      <th>title</th>
      <th>name</th>
      <th>email</th>
      <th>message</th>
      <th>date</th>

</thead>
<tbody>
    <?php foreach($messages as $message):?>

    <tr>
<td><?=$message->id?></td>
      <td><?=$message->title?></td>
      <td><?=$message->name?></td>
      <td><?=$message->email?></td>
      <td><?=$message->message?></td>
      <td><?=$message->date?></td>
    </tr>
    <?php endforeach;?>
</tbody>
<tfoot>
<th>id</th>
      <th>title</th>
      <th>name</th>
      <th>email</th>
      <th>message</th>
      <th>date</th>

</tfoot>

</table>

