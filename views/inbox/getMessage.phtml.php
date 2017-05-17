<div class="mail-box-header">
    <div class="pull-right tooltip-demo">
        <a href="mail_compose.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Reply"><i class="fa fa-reply"></i> Reply</a>
        <a href="#" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Print email"><i class="fa fa-print"></i> </a>
        <a href="mailbox.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to trash"><i class="fa fa-trash-o"></i> </a>
    </div>
    <h2>
        View Message
    </h2>
    <div class="mail-tools tooltip-demo m-t-md">


        <h3>
            <span class="font-noraml">Subject: </span>
            <?= $arrNotifications[0]['notifications']->getSubject () ?>
        </h3>
        <h5>
            <span class="pull-right font-noraml"><?= $arrNotifications[0]['notifications']->getDateSent () ?></span>
            <span class="font-noraml">From: </span>alex.smith@corporation.com
        </h5>
    </div>
</div>

<div class="mail-box">


    <div class="mail-body">
        <?= $arrNotifications[0]['notifications']->getBody () ?>
    </div>

    <?php foreach ($arrReplies as $arrReply): ?>
        <div class="mail-tools tooltip-demo m-t-md">


            <h3>
                <span class="font-noraml">Subject: </span>
                <?= $arrReply->getSubject () ?>
            </h3>
            <h5>
                <span class="pull-right font-noraml"><?= $arrReply->getDateSent () ?></span>
                <span class="font-noraml">From: </span>alex.smith@corporation.com
            </h5>

            <div class="mail-body">
                <?= $arrReply->getMessage () ?>
            </div>
        </div>

    <?php endforeach; ?>

    <!--    <div class="mail-attachment">
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
        </div>-->
    <div class="mail-body text-right tooltip-demo">
        <a id="<?= $arrNotifications[0]['notifications']->getId () ?>" class="btn btn-sm btn-white Reply" href="mail_compose.html"><i class="fa fa-reply"></i> Reply</a>
        <a class="btn btn-sm btn-white" href="mail_compose.html"><i class="fa fa-arrow-right"></i> Forward</a>
        <button title="" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Print" class="btn btn-sm btn-white Print"><i class="fa fa-print"></i> Print</button>
        <button title="" data-placement="top" data-toggle="tooltip" data-original-title="Trash" class="btn btn-sm btn-white"><i class="fa fa-trash-o"></i> Remove</button>
    </div>
    <div class="clearfix"></div>


</div>

<div id='DivIdToPrint' style="display: none;">
    <p>This is a sample text for printing purpose.</p>
</div>

<script>

    function printDiv ()
    {

        var divToPrint = document.getElementById ('DivIdToPrint');

        var newWin = window.open ('', 'Print-Window');

        newWin.document.open ();

        newWin.document.write ('<html><body onload="window.print()">' + $ (".mainContentWrapper").html () + '</body></html>');

        newWin.document.close ();

        setTimeout (function ()
        {
            newWin.close ();
        }, 10);

    }

    $ (document).ready (function ()
    {
        $ (".Reply").on ("click", function ()
        {
            var id = $ (this).attr ("id");

            $.ajax ({
                type: "GET",
                url: "/FormBuilder/inbox/compose/" + id,
                success: function (response)
                {
                    $ (".mainContentWrapper").html (response);
                    rebind ();
                },
                error: function (request, status, error)
                {
                    console.log ("critical errror occured");
                }
            });

            return false;
        });

        $ (".Print").on ("click", function ()
        {
            printDiv ();
        });
    });
</script>