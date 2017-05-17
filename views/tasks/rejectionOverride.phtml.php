<br>
<center>
    <i style="font-size: 72px; color : #ed5565" class=" fa fa-exclamation-triangle"></i>
</center>

<div class="row">
    <p>

    <div class="col-md-10 col-md-offset-1">
        <h3 style="line-height: 23px;">

            <?php
            if ( $workflow == 8 )
            {
                 echo ' This request has been held due to inappropriate form content.<br>
                Some elements did not meet the requirements. See the elements below.
                <br><br>
		You have got 2 options.';
            }
            else
            {
                echo ' This request has been rejected due to inappropriate form content.<br>
                Some elements did not meet the requirements. See the elements below.
                <br><br>
		You have got 3 options.';
            }
            ?>


        </h3>
    </div>	

    <p>
</div>
<br>

<?php $button = $workflow == 8 ? 'Abandon' : 'Reject'; ?>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <ul style="list-style: inside none disc;">
            <li><?= $button ?> the request. It cannot be undone. </li>
            <?php if($workflow != 8): ?>
                <li>Let the form to get filled again.</li>
            <?php endif; ?>
            <li>Accept the discrepancy. Let the request move to the next step. </li>
        </ul>
    </div>	
    <br>	
</div>
<br>

<center>
    <div class="col-md-10 col-md-offset-1">
        <div class="col-md-4">
            <button id="<?= $button ?>" class=" btn btn-w-m btn-danger" style="width:90% " type="button"><?= $button ?></button>
        </div>
        
        <?php if($workflow != 8): ?>
            <div class="col-md-4">
                <button id="GiveAChance" class=" btn btn-w-m btn-success" style=" width:90%  "  type="button">Give a chance</button>
            </div>  
        <?php endif; ?>

        <div class="col-md-4">
            <button id="MoveOn" class=" btn btn-primary" style="width:90%  " type="button">Move on</button>
        </div>
    </div>
</center>
<br>
<br>
<hr style=" background: #ed5565 none repeat scroll 0 0;
    border: 0px;
    color: red !important;
    height: 1px;
    opacity: 0.39;
    width: 80%;">
<script>

    $ (document).ready (function ()
    {

        $ ("#finish").hide ();
        $ ("#Save").hide ();

    })

</script>