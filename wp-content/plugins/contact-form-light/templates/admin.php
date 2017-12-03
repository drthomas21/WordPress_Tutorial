<div class="wrapper">
    <h2><?= CONTACT_PLUGIN_NAME; ?></h2>
    <?php do_action(self::ACTION_ADMIN_TOP); ?>

    <table border='1'>
        <thead>
            <tr>
                <td>#</td>
                <td>IP Address</td>
                <td>From</td>
                <td>Subject</td>
                <td>Body</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($Records as $Record): ?>
                <tr>
                    <td><?= $Record->id; ?></td>
                    <td><?= $Record->ip_address; ?></td>
                    <td><?= $Record->from; ?></td>
                    <td><?= $Record->subject; ?></td>
                    <td><?= $Record->body; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php do_action(self::ACTION_ADMIN_BOTTOM); ?>
</div>
