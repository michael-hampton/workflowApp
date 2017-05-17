<?php foreach ($arrComments as $arrComment): ?>
    <div class="timeline-item">
        <div class="row">
            <div class="col-xs-3 date">
                <i class="fa fa-briefcase"></i>
                <?= $arrComment['datetime'] ?>
                <br>
                                    
                <small class="text-navy"><?= $arrComment['username'] ?></small>
            </div>
                                
            <div class="col-xs-9 content no-top-border">
                <?= $arrComment['comment'] ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>

