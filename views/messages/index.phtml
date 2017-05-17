<link href="/FormBuilder/public/css/plugins/summernote/summernote.css" rel="stylesheet" type="text/css">
<link href="/FormBuilder/public/css/plugins/summernote/summernote2.css" rel="stylesheet" type="text/css">

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-content mailbox-content">
                    <div class="file-manager">
                        <a class="btn btn-block btn-primary compose-mail" href="mail_compose.html">Compose Mail</a>
                        <div class="space-25"></div>
                        <h5>Folders</h5>
                        <ul class="folder-list m-b-md" style="padding: 0">
                            <li><a class="filterMessage" id="all" href="#"> <i class="fa fa-inbox "></i> Inbox <span class="label label-warning pull-right">16</span> </a></li>
                            <li><a class="filterMessage" id="sentMessages" href="#"> <i class="fa fa-envelope-o"></i> Send Mail</a></li>
                            <li><a href="mailbox.html"> <i class="fa fa-certificate"></i> Important</a></li>
                            <li><a class="filterMessage" id="archived" href="#"> <i class="fa fa-file-text-o"></i> Archive <span class="label label-danger pull-right">2</span></a></li>
                            <li><a class="filterMessage" id="trashed" href="#"> <i class="fa fa-trash-o"></i> Trash</a></li>
                            <li><a class="filterMessage hasStatus" id="status_0" href="#"> <i class="fa fa-trash-o"></i> Unread</a></li>
                            <li><a class="filterMessage hasStatus" id="status_1" href="#"> <i class="fa fa-trash-o"></i> Read</a></li>
                        </ul>
                        <h5>Categories</h5>
                        <ul class="category-list" style="padding: 0">
                            <?php foreach ($messageTypes as $messageType): ?>
                                <li><a class="category" id="<?= $messageType['pk'] ?>" href="#"> <i class="fa fa-circle text-navy"></i> <?= $messageType['description'] ?> </a></li>
                            <?php endforeach; ?>    
                        </ul>

                        <h5 class="tag-title">Channels</h5>
                        <ul class="tag-list" style="padding: 0">
                            <?php foreach ($arrWebsites as $arrWebsite): ?>
                                <li><a class="filterMessage Channel" id="website_<?= $arrWebsite['websiteName'] ?>" href=""><i class="fa fa-tag"></i> <?= $arrWebsite['websiteName'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 animated fadeInRight">
            <div class="openWindow"></div>

            <div class="emailView">
                <div class="mail-box-header">

                    <form class="pull-right mail-search">
                        <div class="input-group">
                            <input type="text" class="form-control input-sm" name="form[search]" placeholder="Search email">
                            <div class="input-group-btn">
                                <button id="searchButton" type="button" class="btn btn-sm btn-primary">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="searchMessageBox">
                        <h2>
                            Inbox (16)
                        </h2>
                        <div class="mail-tools tooltip-demo m-t-md">
                            <div class="btn-group pull-right">
                                <button class="btn btn-white btn-sm"><i class="fa fa-arrow-left"></i></button>
                                <button class="btn btn-white btn-sm"><i class="fa fa-arrow-right"></i></button>

                            </div>
                            <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="left" title="Refresh inbox"><i class="fa fa-refresh"></i> Refresh</button>
                            <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Mark as read"><i class="fa fa-eye"></i> </button>
                            <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Mark as important"><i class="fa fa-exclamation"></i> </button>
                            <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to trash"><i class="fa fa-trash-o"></i> </button>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>


<script>
    var pageResultOrderFiedls = "id";
    var pageResultOrderDirection = "asc";
    var CurrentPageNumber = 0;

    /////////////////////////////////////////////
    //
    // Default for new search
    //

    var DefpageResultOrderFiedls = "id";
    var DefpageResultOrderDirection = "asc";
    var DefCurrentPageNumber = 0;
    var status = 2;
    var category = null;
    var channel = null;

    $ (document).ready (function ()
    {

        search ();

        $ ("#searchButton").click (function ()
        {
            search ();
        });

        $ (".filterMessage").click (function ()
        {

            if ($ (this).hasClass ("hasStatus"))
            {
                status = $ (this).attr ("id").split ("_")[1];
                search ("getStatus");
            }
            else if ($ (this).hasClass ("Channel"))
            {
                channel = $ (this).attr ("id").split ("_")[1];
                search ("website");
                return false;
            }
            else
            {
                search ($ (this).attr ("id"));
            }

            $ (".openWindow").slideUp ();
            $ (".emailView").slideDown ();
        });

        $ (".category").click (function ()
        {
            category = $ (this).attr ("id");
            search ("category");
        });
    });

    function rebindAll ()
    {

        $ (".Reply").click (function ()
        {
            var strUrl = '/FormBuilder/messages/composeEmail';

            $.ajax ({
                type: "POST",
                url: strUrl,
                success: function (response)
                {
                    //$(".mail-box-header").hide()
                    $ (".replyBox").html (response);
                    $ ("#email").val ($ ("#emailAddress").val ());

                    $ (".Discard").click (function ()
                    {
                        $ (".replyBox").slideUp ();
                        rebindAll ();
                    });
                    rebindAll ();
                }
            });
        });

        $ ('.i-checks').iCheck ({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        $ (".job").click (function ()
        {
            var jobid = $ (this).attr ("jobid");

            var strUrl = '/FormBuilder/messages/getMessageDetails/' + jobid;

            $.ajax ({
                type: "POST",
                url: strUrl,
                success: function (response)
                {
                    $ (".emailView").slideUp ();
                    $ (".openWindow").slideDown ();
                    $ (".openWindow").html (response);
                    //$(".inboxWrapper").hide();
                    //$(".backButton").show();
                    rebindAll ();
                }
            });
        });

        $ (".compose-mail").click (function ()
        {
            var strUrl = '/FormBuilder/messages/composeEmail';

            $.ajax ({
                type: "POST",
                url: strUrl,
                success: function (response)
                {
                    $ (".emailView").slideUp ();
                    $ (".openWindow").slideDown ();
                    $ (".openWindow").html (response);
                    rebindAll ();
                }
            });
            
            return false;
        });

        $ ("#sendToArchive").click (function ()
        {
            var SendUrl = '/FormBuilder/messages/sendToArchive/';

            var archived = [];

            $.each ($ ("input.checkbox"), function ()
            {
                var checked = $ (this).parent ('[class*="icheckbox"]').hasClass ("checked");

                if (checked)
                {
                    archived.push ($ (this).attr ("id"));
                }
            });

            $.ajax ({
                type: "POST",
                url: SendUrl,
                data: {archived: archived},
                success: function (response)
                {
                    alert (response);
                    jumpToPage (CurrentPageNumber);
                }
            });

            return false;
        });

        $ ("#markAsRead").click (function ()
        {
            var SendUrl = '/FormBuilder/messages/markAsRead/';

            var read = [];

            $.each ($ ("input.checkbox"), function ()
            {
                var checked = $ (this).parent ('[class*="icheckbox"]').hasClass ("checked");

                if (checked)
                {
                    read.push ($ (this).attr ("id"));
                }
            });

            $.ajax ({
                type: "POST",
                url: SendUrl,
                data: {read: read},
                success: function (response)
                {
                    alert (response);
                    jumpToPage (CurrentPageNumber);
                }
            });

            return false;
        });
    }

    function search(type = 'all') {
        errorCounter = 0;

        $ (".TaskManagerFormInput").removeClass ("TaskManagerFormError");
        $ (".arrow_boxTOPChannel").hide ();
        $ (".arrow_boxTOPChannelTOP").hide ();

        CurrentPageNumber = DefCurrentPageNumber;

        var formData = $ (".mail-search").serialize ();

        if (status != 2)
        {
            formData += "&status=" + status;
        }

        if (category != null)
        {
            formData += "&category=" + category;
        }

        if (channel != null)
        {
            formData += "&channel=" + channel;
        }

        SendUrl = "/FormBuilder/messages/searchMessages/" + DefCurrentPageNumber + "/" + DefpageResultOrderFiedls + "/" + DefpageResultOrderDirection + "/" + type;

        $.ajax ({
            type: "POST",
            url: SendUrl,
            data: formData,
            success: function (response)
            {

                $ (".searchMessageBox").html (response);
                rebindAll ();
            }
        });
    }

    function jumpToPage (pageNumber)
    {

        CurrentPageNumber = pageNumber;
        DefCurrentPageNumber = pageNumber;

        search ();
    }
</script>

<script src="/FormBuilder/public/js/plugins/summernote/summernote.js" type="text/javascript"></script>