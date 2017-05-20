<style>
    /* -------------------------------- 

Primary style

-------------------------------- */
    *, *::after, *::before {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    *::after, *::before {
        content: '';
    }


    a {
        color: #89ba2c;
        text-decoration: none;
    }

    .cd-main-content {
        text-align: center;
    }
    .cd-main-content h1 {
        font-size: 20px;
        font-size: 1.25rem;
        color: #64788c;
        padding: 4em 0;
    }
    .cd-main-content .cd-btn {
        position: relative;
        display: inline-block;
        padding: 1em 2em;
        background-color: #89ba2c;
        color: #ffffff;
        font-weight: bold;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        border-radius: 50em;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5), 0 0 5px rgba(0, 0, 0, 0.1);
        -webkit-transition: all 0.2s;
        -moz-transition: all 0.2s;
        transition: all 0.2s;
    }
    .no-touch .cd-main-content .cd-btn:hover {
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5), 0 0 20px rgba(0, 0, 0, 0.3);
    }
    @media only screen and (min-width: 1170px) {
        .cd-main-content h1 {
            font-size: 32px;
            font-size: 2rem;
        }
    }

    .cd-panel {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        visibility: hidden;
        -webkit-transition: visibility 0s 0.6s;
        -moz-transition: visibility 0s 0.6s;
        transition: visibility 0s 0.6s;
    }
    .cd-panel::after {
        /* overlay layer */
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: transparent;
        cursor: pointer;
        -webkit-transition: background 0.3s 0.3s;
        -moz-transition: background 0.3s 0.3s;
        transition: background 0.3s 0.3s;
    }
    .cd-panel.is-visible {
        visibility: visible;
        -webkit-transition: visibility 0s 0s;
        -moz-transition: visibility 0s 0s;
        transition: visibility 0s 0s;
    }
    .cd-panel.is-visible::after {
        background: rgba(0, 0, 0, 0.6);
        -webkit-transition: background 0.3s 0s;
        -moz-transition: background 0.3s 0s;
        transition: background 0.3s 0s;
    }
    .cd-panel.is-visible .cd-panel-close::before {
        -webkit-animation: cd-close-1 0.6s 0.3s;
        -moz-animation: cd-close-1 0.6s 0.3s;
        animation: cd-close-1 0.6s 0.3s;
    }
    .cd-panel.is-visible .cd-panel-close::after {
        -webkit-animation: cd-close-2 0.6s 0.3s;
        -moz-animation: cd-close-2 0.6s 0.3s;
        animation: cd-close-2 0.6s 0.3s;
    }

    @-webkit-keyframes cd-close-1 {
        0%, 50% {
            -webkit-transform: rotate(0);
        }
        100% {
            -webkit-transform: rotate(45deg);
        }
    }
    @-moz-keyframes cd-close-1 {
        0%, 50% {
            -moz-transform: rotate(0);
        }
        100% {
            -moz-transform: rotate(45deg);
        }
    }
    @keyframes cd-close-1 {
        0%, 50% {
            -webkit-transform: rotate(0);
            -moz-transform: rotate(0);
            -ms-transform: rotate(0);
            -o-transform: rotate(0);
            transform: rotate(0);
        }
        100% {
            -webkit-transform: rotate(45deg);
            -moz-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            -o-transform: rotate(45deg);
            transform: rotate(45deg);
        }
    }
    @-webkit-keyframes cd-close-2 {
        0%, 50% {
            -webkit-transform: rotate(0);
        }
        100% {
            -webkit-transform: rotate(-45deg);
        }
    }
    @-moz-keyframes cd-close-2 {
        0%, 50% {
            -moz-transform: rotate(0);
        }
        100% {
            -moz-transform: rotate(-45deg);
        }
    }
    @keyframes cd-close-2 {
        0%, 50% {
            -webkit-transform: rotate(0);
            -moz-transform: rotate(0);
            -ms-transform: rotate(0);
            -o-transform: rotate(0);
            transform: rotate(0);
        }
        100% {
            -webkit-transform: rotate(-45deg);
            -moz-transform: rotate(-45deg);
            -ms-transform: rotate(-45deg);
            -o-transform: rotate(-45deg);
            transform: rotate(-45deg);
        }
    }
    .cd-panel-header {
        position: fixed;
        width: 90%;
        height: 50px;
        line-height: 50px;
        background: rgba(255, 255, 255, 0.96);
        z-index: 2;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.08);
        -webkit-transition: top 0.3s 0s;
        -moz-transition: top 0.3s 0s;
        transition: top 0.3s 0s;
    }
    .cd-panel-header h1 {
        font-weight: bold;
        color: #89ba2c;
        padding-left: 5%;
    }
    .from-right .cd-panel-header, .from-left .cd-panel-header {
        top: -50px;
    }
    .from-right .cd-panel-header {
        right: 0;
    }
    .from-left .cd-panel-header {
        left: 0;
    }
    .is-visible .cd-panel-header {
        top: 0;
        -webkit-transition: top 0.3s 0.3s;
        -moz-transition: top 0.3s 0.3s;
        transition: top 0.3s 0.3s;
    }
    @media only screen and (min-width: 768px) {
        .cd-panel-header {
            width: 70%;
        }
    }
    @media only screen and (min-width: 1170px) {
        .cd-panel-header {
            width: 50%;
        }
    }

    .cd-panel-close {
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        width: 60px;
        /* image replacement */
        display: inline-block;
        overflow: hidden;
        text-indent: 100%;
        white-space: nowrap;
    }
    .cd-panel-close::before, .cd-panel-close::after {
        /* close icon created in CSS */
        position: absolute;
        top: 22px;
        left: 20px;
        height: 3px;
        width: 20px;
        background-color: #424f5c;
        /* this fixes a bug where pseudo elements are slighty off position */
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
    }
    .cd-panel-close::before {
        -webkit-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        -o-transform: rotate(45deg);
        transform: rotate(45deg);
    }
    .cd-panel-close::after {
        -webkit-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
        -ms-transform: rotate(-45deg);
        -o-transform: rotate(-45deg);
        transform: rotate(-45deg);
    }
    .no-touch .cd-panel-close:hover {
        background-color: #424f5c;
    }
    .no-touch .cd-panel-close:hover::before, .no-touch .cd-panel-close:hover::after {
        background-color: #ffffff;
        -webkit-transition-property: -webkit-transform;
        -moz-transition-property: -moz-transform;
        transition-property: transform;
        -webkit-transition-duration: 0.3s;
        -moz-transition-duration: 0.3s;
        transition-duration: 0.3s;
    }
    .no-touch .cd-panel-close:hover::before {
        -webkit-transform: rotate(220deg);
        -moz-transform: rotate(220deg);
        -ms-transform: rotate(220deg);
        -o-transform: rotate(220deg);
        transform: rotate(220deg);
    }
    .no-touch .cd-panel-close:hover::after {
        -webkit-transform: rotate(135deg);
        -moz-transform: rotate(135deg);
        -ms-transform: rotate(135deg);
        -o-transform: rotate(135deg);
        transform: rotate(135deg);
    }

    .cd-panel-container {
        position: fixed;
        width: 90%;
        height: 100%;
        top: 0;
        background: #dbe2e9;
        z-index: 1;
        -webkit-transition-property: -webkit-transform;
        -moz-transition-property: -moz-transform;
        transition-property: transform;
        -webkit-transition-duration: 0.3s;
        -moz-transition-duration: 0.3s;
        transition-duration: 0.3s;
        -webkit-transition-delay: 0.3s;
        -moz-transition-delay: 0.3s;
        transition-delay: 0.3s;
    }
    .from-right .cd-panel-container {
        right: 0;
        -webkit-transform: translate3d(100%, 0, 0);
        -moz-transform: translate3d(100%, 0, 0);
        -ms-transform: translate3d(100%, 0, 0);
        -o-transform: translate3d(100%, 0, 0);
        transform: translate3d(100%, 0, 0);
    }
    .from-left .cd-panel-container {
        left: 0;
        -webkit-transform: translate3d(-100%, 0, 0);
        -moz-transform: translate3d(-100%, 0, 0);
        -ms-transform: translate3d(-100%, 0, 0);
        -o-transform: translate3d(-100%, 0, 0);
        transform: translate3d(-100%, 0, 0);
    }
    .is-visible .cd-panel-container {
        -webkit-transform: translate3d(0, 0, 0);
        -moz-transform: translate3d(0, 0, 0);
        -ms-transform: translate3d(0, 0, 0);
        -o-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0);
        -webkit-transition-delay: 0s;
        -moz-transition-delay: 0s;
        transition-delay: 0s;
    }
    @media only screen and (min-width: 768px) {
        .cd-panel-container {
            width: 70%;
        }
    }
    @media only screen and (min-width: 1170px) {
        .cd-panel-container {
            width: 50%;
        }
    }

    .cd-panel-content {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        padding: 70px 5%;
        overflow: auto;
        /* smooth scrolling on touch devices */
        -webkit-overflow-scrolling: touch;
    }
    .cd-panel-content p {
        font-size: 14px;
        font-size: 0.875rem;
        font-family: "Droid Serif", serif;
        color: #424f5c;
        line-height: 1.4;
        margin: 2em 0;
    }
    .cd-panel-content p:first-of-type {
        margin-top: 0;
    }
    @media only screen and (min-width: 768px) {
        .cd-panel-content p {
            font-size: 16px;
            font-size: 1rem;
            line-height: 1.6;
        }
    }


    .list-group {
        padding-left: 0;
        margin-bottom: 20px;
    }

    .list-group.clear-list .list-group-item {
        border-top: 1px solid #e7eaec;
        border-bottom: 0;
        border-right: 0;
        border-left: 0;
        padding: 10px 0;
    }

    .list-group-item:first-child {
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }

    .list-group-item {
        background-color: inherit;
        border: 1px solid #e7eaec;
        display: block;
        margin-bottom: -1px;
        padding: 10px 15px;
        position: relative;
    }
    .pull-right {
        float: right!important;
    }

</style>



<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-content mailbox-content">

                    <div class="file-manager">
                        <!--                        <a class="btn btn-block btn-primary compose-mail" href="mail_compose.html">Compose Mail</a>-->

                        <i style="font-size: 28px;" class="fa fa-plus-square addProcess"></i>

                        <div class="hiddenRequests col-lg-12 pull-left" style="display:none;">
                            <div class="client-detail" style="height:200px; margin-bottom: 15px;">
                                <strong>Choose a request</strong>

                                <ul class="list-group clear-list">
                                    <li class="list-group-item fist-item">
                                        <span class="pull-right"> 
                                            <button type="button" class="btn btn-xs btn-primary addNewProcess">+</button>
                                        </span>
                                        Please contact me
                                    </li>
                                    <li class="list-group-item">
                                        <span class="pull-right"> 
                                            <button type="button" class="btn btn-xs btn-primary addNewProcess">+</button>
                                        </span>
                                        Sign a contract
                                    </li>
                                    <li class="list-group-item">
                                        <span class="pull-right"> 
                                            <button type="button" class="btn btn-xs btn-primary addNewProcess">+</button>
                                        </span>
                                        Open new shop
                                    </li>
                                    <li class="list-group-item">
                                        <span class="pull-right"> 
                                            <button type="button" class="btn btn-xs btn-primary addNewProcess">+</button>
                                        </span>
                                        Call back to Sylvia
                                    </li>
                                    <li class="list-group-item">
                                        <span class="pull-right"> 
                                            <button type="button" class="btn btn-xs btn-primary addNewProcess">+</button>
                                        </span>
                                        Write a letter to Sandra
                                    </li>
                                </ul>
                            </div>

                        </div>

                        <div class="space-25"></div>
                        <h5>Folders</h5>
                        <ul class="folder-list m-b-md" style="padding: 0">
                            <li><a class="changeStatus" status="1" href="#"> <i class="fa fa-inbox "></i> Inbox <span class="label label-warning pull-right">16</span> </a></li>
                            <li><a class="changeStatus" href="#"> <i class="fa fa-envelope-o"></i> Send Mail</a></li>
                            <li><a class="changeStatus" status="8" href="#" href="mailbox.html"> <i class="fa fa-certificate"></i> Important</a></li>
                            <li><a class="changeStatus" href="#" status="2" href="mailbox.html"> <i class="fa fa-file-text-o"></i> Drafts <span class="label label-danger pull-right">2</span></a></li>
                            <li><a class="changeStatus" href="#" href="mailbox.html"> <i class="fa fa-trash-o"></i> Trash</a></li>
                            <li><a class="changeStatus" href="#" status="3" href="#"> <i class="fa fa-trash-o"></i> Archived</a></li>
                        </ul>
                        <h5>Categories</h5>
                        <ul class="category-list" style="padding: 0">

                            <?php
                            $arrColours = array("text-navy", "text-danger", "text-primary", "text-info", "text-warning");
                            foreach ($arrCounters as $key => $arrCounter):
                                ?>
                                <li><a list-type="<?= $arrCounter['item'] ?>" href="#"> <i class="fa fa-circle <?= $arrColours[$key] ?>"></i> <?= $arrCounter['item'] . " (" . $arrCounter['count'] . ") " ?></a></li>
                            <?php endforeach; ?>
                        </ul>

                        <!--                        <h5 class="tag-title">Labels</h5>
                                                <ul class="tag-list" style="padding: 0">
                                                    <li><a href=""><i class="fa fa-tag"></i> Family</a></li>
                                                    <li><a href=""><i class="fa fa-tag"></i> Work</a></li>
                                                    <li><a href=""><i class="fa fa-tag"></i> Home</a></li>
                                                    <li><a href=""><i class="fa fa-tag"></i> Children</a></li>
                                                    <li><a href=""><i class="fa fa-tag"></i> Holidays</a></li>
                                                    <li><a href=""><i class="fa fa-tag"></i> Music</a></li>
                                                    <li><a href=""><i class="fa fa-tag"></i> Photography</a></li>
                                                    <li><a href=""><i class="fa fa-tag"></i> Film</a></li>
                                                </ul>-->
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-9 animated fadeInRight mainContentWrapper">
            <div class="mail-box-header">

                <form method="get" action="index.html" class="pull-right mail-search">
                    <div class="input-group">
                        <input type="text" class="form-control input-sm" id="search" name="search" placeholder="Search email">
                        <div class="input-group-btn">
                            <button type="button" id="Search" class="btn btn-sm btn-primary">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
                <h2>
                    Inbox (<?= count ($arrNotifications) ?>)
                </h2>
                <div class="mail-tools tooltip-demo m-t-md">

                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="left" title="Refresh inbox"><i class="fa fa-refresh"></i> Refresh</button>
                    <button class="btn btn-white btn-sm updateStatus" status="5" data-toggle="tooltip" data-placement="top" title="Mark as read"><i class="fa fa-eye"></i> </button>
                    <button class="btn btn-white btn-sm updateStatus" status="6" data-toggle="tooltip" data-placement="top" title="Mark as important"><i class="fa fa-exclamation"></i> </button>
                    <button class="btn btn-white btn-sm updateStatus" status="4" data-toggle="tooltip" data-placement="top" title="Move to trash"><i class="fa fa-trash-o"></i> </button>

                </div>
            </div>
            <div class="mail-box">

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


            </div>
        </div>
    </div>
</div>

<script src="/FormBuilder/public/js/plugins/iCheck/icheck.min.js"></script>

<script>
    var selected = [];
    var page = 0;
    var orderBy = "ns.date_sent";
    var orderDir = "DESC";
    var status = 1;
    var listType = null;
    var processPage = 0;

    function projectsPage (selectedPage)
    {
        page = selectedPage;

        $.ajax ({
            type: "GET",
            url: "/FormBuilder/inbox/filterProjects/" + listType + "/" + page,
            success: function (response)
            {
                $ (".mail-box").html (response);
                rebind ();
            },
            error: function (request, status, error)
            {
                console.log ("critical errror occured");
            }
        });
    }

    $ (".category-list > li > a").off ();
    $ (".category-list > li > a").on ("click", function ()
    {
        listType = $ (this).attr ("list-type");
        page = 0;

        $.ajax ({
            type: "GET",
            url: "/FormBuilder/inbox/filterProjects/" + listType + "/" + page,
            success: function (response)
            {
                $ (".mail-box").html (response);
                rebind ();
            },
            error: function (request, status, error)
            {
                console.log ("critical errror occured");
            }
        });
    });

    function rebind ()
    {
        $ ('.i-checks').iCheck ({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        $ ('.i-checks').on ('ifChecked', function (event)
        {
            var id = $ (this).parent ().parent ().attr ("id");
            selected.push (id);
            console.log (id);
        });

        $ (".mailItem").on ("click", function ()
        {
            var id = $ (this).find (".check-mail").attr ("id");

            $.ajax ({
                type: "GET",
                url: "/FormBuilder/inbox/getMessage/" + id,
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
    }

    function search ()
    {
        var searchText = $ ("#search").val ();

        $.ajax ({
            type: "POST",
            url: "/FormBuilder/inbox/searchMessages/" + status + "/" + page + "/" + orderBy + "/" + orderDir,
            data: {"searchText": searchText},
            success: function (response)
            {
                 $(".mail-box-header").show();
                $ (".mail-box").html (response);
                rebind ();
            },
            error: function (request, status, error)
            {
                console.log ("critical errror occured");
            }
        })
    }
    
    function searchProcesses() {
        $.ajax ({
                type: "GET",
                url: "/FormBuilder/inbox/addCase/"+processPage,
                success: function (response)
                {
                    $(".mail-box-header").hide();
                    $ (".mail-box").html (response);
                    rebind ();
                },
                error: function (request, status, error)
                {
                    console.log ("critical errror occured");
                }
            });
    }
    
    function processPagination(page) {
        processPage = page;
        searchProcesses();
    }

    function jumpToPage (pageNo)
    {
        page = pageNo;
        search ();
    }

    $ (document).ready (function ()
    {
        search ();

        $ ("#Search").on ("click", function ()
        {
            search ();
        });

        $ (".addProcess").on ("click", function ()
        {
            if ($ (this).hasClass ("selected"))
            {
                $ (this).removeClass ("selected");
                search ();
                return false;
            }

            $ (this).addClass ("selected");
            searchProcesses();
            

        });

        $ ('.cd-panel').on ('click', function (event)
        {
            if ($ (event.target).is ('.cd-panel') || $ (event.target).is ('.cd-panel-close'))
            {
                $ ('.cd-panel').removeClass ('is-visible');
                event.preventDefault ();
            }
        });

        rebind ();

        $ (".updateStatus").on ("click", function ()
        {
            var status = $ (this).attr ("status");

            $.ajax ({
                type: "POST",
                data: {"selected": selected},
                url: "/FormBuilder/inbox/updateStatus/" + status,
                success: function (response)
                {
                    alert (response);
                },
                error: function (request, status, error)
                {
                    console.log ("critical errror occured");
                }
            })
        });

        $ (".changeStatus").on ("click", function ()
        {
            status = $ (this).attr ("status");
            page = 0;

            search ();
        });
    });

</script>