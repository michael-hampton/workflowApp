<br>
<center>
<i style="font-size: 72px; color : #ed5565" class=" fa fa-exclamation-triangle"></i>
</center>

<div class="row">
    <p>
	
        <div class="col-md-10 col-md-offset-1">
            <h3 style="line-height: 23px;">
                This request has been rejected due to inappropriate form content.<br>
                Some elements did not meet the requirements. See the elements below.
                <br><br>
                
                <?= $workflow == 8 ? 'You have got 2 options' : 'You have got 3 options' ?>
            </h3>
	</div>	
	
    <p>
</div>
<br>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
	<ul style="list-style: inside none disc;">
            <li>Reject the request. It cannot be undone. </li>
            <?php if($workflow != 8): ?>
                <li>Let the form to get filled again.</li>
            <?php endif; ?>
            <li>Accept the request. Let the request move to the next step. </li>
	</ul>
    </div>	
<br>	
</div>
<br>

<div id="rejectionError" class="alert alert-danger col-lg-12 pull-left" style="display: none;">You must specify a reason for rejection.</div> 

<div class="col-lg-12">
    <textarea id="rejectionReason" style="display: none;" class="form-control" placeholder="Reason for rejection"></textarea>
</div>

<center>
    <div class="col-md-10 col-md-offset-1">
	<div class="col-md-4">
            <button id="Reject" class=" btn btn-w-m btn-danger" style="width:90% " type="button">Reject</button>
	</div>
<!--	<div class="col-md-4">
            <button id="GiveAChance" class=" btn btn-w-m btn-success" style=" width:90%  "  type="button">Give a chance</button>
	</div>-->
	
	<div class="col-md-4">
            <button id="MoveOn" class=" btn btn-primary" style="width:90%  " type="button"><?= $workflow == 7 ? 'Accept' : 'Move On' ?></button>
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

	$(document).ready(function(){
	
		$("#finish").hide();
		$("#Save").hide();
	
	})
	
</script>