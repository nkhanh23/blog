<table>
    <thead>
        <tr>STT</tr>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Ngày tạo</th>
    </thead>
    <tbody>
        <?php
        $count = 1;
        foreach ($data as $item):
        ?>
            <tr>
                <td><?php echo $count;
                    $count++ ?></td>
                <td><?php echo  $item['fullname']; ?></td>
                <td><?php echo  $item['fullname']; ?></td>
                <td><?php echo  $item['created_at']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>