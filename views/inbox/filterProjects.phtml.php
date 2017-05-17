<?php
if(empty($arrCases)) {
    echo '<br><br><br><center><h2>No Cases could be found </h2></center>';
    exit;
}
?>

<div class="col-lg-12 pull-left">
    <?= $pagination ?>
</div>

<table class="table table-hover table-mail">

    <thead>
        <tr>
            <th></th>
            <th>Project Id</th>
            <th>Case Name</th>
            <th>Description</th>
            <th>Step Name</th>
        </tr>
    </thead>

    <tbody>

        <?php
        
        foreach ($arrCases as $key => $arrCase):

            //$class = $arrNotification['notifications']->getHasRead () == 0 ? 'unread' : 'read';
            ?>

            <tr class="mailItem">
                <td id="<?= $arrCase->getId () ?>" class="check-mail">
                    <input type="checkbox" class="i-checks">
                </td>
                <td class="mail-ontact"><?= $arrCase->getParentId() ?></td>
                <td class="mail-ontact"><?= $arrCase->getName() ?></td>
                <td class="mail-subject"><?= $arrCase->getDescription() ?></td>
                <td class=""><?= $arrCase->getCurrent_step() ?></td>
            </tr>

            <?php
        endforeach;
        ?>

    </tbody>
</table>