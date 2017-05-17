
<?php if ( !isset ($html) || $html == "" ): ?>
    <div class="col-lg-12 pull-left">
        <?= $summary ?>
    </div>

    <?php
    return;
endif;
?>


<?= $html ?>


<script>

<?php if ( isset ($html) && $html != "" ): ?>
        $ (".saveStep").show ();
        $ (".completeStep").show ();
<?php endif; ?>

<?php if ( $canSave === false ): ?>
        //$ (".saveStep").prop ("disabled", true);
        //$ (".completeStep").prop ("disabled", true);
<?php endif; ?>

</script>