<div class="replyBox">

</div>
<input type="hidden" id="emailAddress" name="email" value="<?= $arrMessageHeader[0]['email'] ?>">
<form id="NewBarcodeForm">
    <input type="hidden" id="messageId" name="messageId" value="<?= $arrMessageHeader[0]['id'] ?>">
    <input type="hidden" id="channel" name="channel" value="<?= $arrMessageHeader[0]['channel'] ?>">
    <input type="hidden" id="messageType" name="messageType" value="<?= $arrMessageHeader[0]['message_type'] ?>">
</form>

<div class="mail-box-header">

    <div class="pull-right tooltip-demo">
        <a class="btn btn-white btn-sm Reply" data-toggle="tooltip" data-placement="top" title="Reply"><i class="fa fa-reply"></i> Reply</a>
        <a href="#" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Print email"><i class="fa fa-print"></i> </a>
        <a id="<?= $arrMessageHeader[0]['id'] ?>" class="btn btn-white btn-sm moveToTrash" data-toggle="tooltip" data-placement="top" title="Move to trash"><i class="fa fa-trash-o"></i> </a>
    </div>

    <h2>
        View Message
    </h2>

    <div class="mail-tools tooltip-demo m-t-md">

        <h3>
            <span class="font-noraml">Subject: </span>Aldus PageMaker including versions of Lorem Ipsum.
        </h3>

        <div style="margin-bottom:10%; width: 100%;">
            <div class="pull-right font-noraml">Channel: <?= $arrMessageHeader[0]['channel'] ?></div>
            <div class="pull-left font-normal">#<?= $arrMessageHeader[0]['returnId'] ?></div>
        </div>


        <h5 style="width:100%;">
            <span class="pull-right font-noraml"><?= $arrMessageHeader[0]['date_added'] ?> </span>
            <span class="font-noraml">From: </span><?= $arrMessageHeader[0]['email'] ?>
        </h5>
    </div>
</div>

<div class="mail-box">
    <div class="mail-body">
        <?= $arrMessageHeader[0]['comment'] ?>
    </div>

    <?php foreach ($arrMessageDetails as $arrMessageDetail): ?>
        <div class="mail-body">
            <div class="tab-pane active" id="tab-1">
                <div class="feed-activity-list">
                    <div class="feed-element">            
                        <div class="media-body ">
                            <strong><?= $arrMessageDetail['customer_firstName'] . " " . $arrMessageDetail['customer_lastName'] ?></strong> posted message on
                            <small class="text-muted"><?= $arrMessageDetail['date_added'] ?></small>

                            <div class="well">
                                <?= $arrMessageDetail['comment'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>



    <!--<div class="mail-attachment">
                    
        <p>
            <span><i class="fa fa-paperclip"></i> 2 attachments - </span>
                        
            <a href="#">Download all</a>
                |
                        
                <a href="#">View all images</a>
                    </p>

                    <div class="attachment">
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-file"></i>
                                    </div>
                                    <div class="file-name">
                                        Document_2014.doc
                                        <br>
                                        <small>Added: Jan 11, 2014</small>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-responsive" src="img/p1.jpg">
                                    </div>
                                    <div class="file-name">
                                        Italy street.jpg
                                        <br>
                                        <small>Added: Jan 6, 2014</small>
                                    </div>
                                </a>

                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-responsive" src="img/p2.jpg">
                                    </div>
                                    <div class="file-name">
                                        My feel.png
                                        <br>
                                        <small>Added: Jan 7, 2014</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    </div>
                    <div class="mail-body text-right tooltip-demo">
                            <a class="btn btn-sm btn-white" href="mail_compose.html"><i class="fa fa-reply"></i> Reply</a>
                            <a class="btn btn-sm btn-white" href="mail_compose.html"><i class="fa fa-arrow-right"></i> Forward</a>
                            <button title="" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Print" class="btn btn-sm btn-white"><i class="fa fa-print"></i> Print</button>
                            <button title="" data-placement="top" data-toggle="tooltip" data-original-title="Trash" class="btn btn-sm btn-white"><i class="fa fa-trash-o"></i> Remove</button>
                    </div>
                    <div class="clearfix"></div>


            </div>
        </div>-->


    <script>
        $ (".moveToTrash").click (function ()
        {
            var SendUrl = '/FormBuilder/messages/trash/';
            var id = $(this).attr("id");
            
            $.ajax ({
                type: "POST",
                url: SendUrl,
                data: {id: id},
                success: function (response)
                {
                }
            });

            return false;
        });
    </script>