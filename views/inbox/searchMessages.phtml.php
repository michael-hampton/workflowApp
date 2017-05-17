<div class="col-lg-12 pull-left">
    <?= $pagination ?>
</div>

<table class="table table-hover table-mail">

    <thead>
        <tr>
            <th></th>
            <th>Project Name</th>
            <th>Subject</th>
            <th>Step</th>
            <th>Date Sent</th>
        </tr>
    </thead>

    <tbody>

        <?php
        foreach ($arrNotifications as $key => $arrNotification):

            $arrNotification['project']->setAuditData ();

            $class = $arrNotification['notifications']->getHasRead () == 0 ? 'unread' : 'read';
            ?>

            <tr class="<?= $class ?> mailItem">
                <td id="<?= $arrNotification['notifications']->getId () ?>" class="check-mail">
                    <input type="checkbox" class="i-checks">
                </td>
                <td class="mail-subject"><a href="mail_detail.html"><?= $arrNotifications[$key]['project']->getName () ?></a></td>
                <td class="mail-ontact"><a href="mail_detail.html"><?= $arrNotification['notifications']->getSubject () ?></a></td>
                <td class=""><?= $arrNotification['project']->getCurrent_step () ?></td>

                <td class="text-right mail-date"><?= $arrNotification['notifications']->getDateSent () ?></td>
            </tr>

            <?php
        endforeach;
        ?>

    </tbody>
</table>