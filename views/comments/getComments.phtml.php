
<div class="alert alert-danger" style="display:none;">
    Comment cannot be empty.
</div>

<div class="alert alert-success" style="display:none;">
    Comment was saved successfully
</div>

<div class="col-lg-12 comments-list" style="width:100% !important;">
    <?php foreach ($arrComments as $arrComment): ?>
        <div class="social-feed-box col-lg-12">
            <div class="social-avatar">
                <a href="" class="pull-left">
                    <img alt="image" src="/FormBuilder/public/img/users/<?= $arrComment["username"] ?>.jpeg">
                </a>

                <div class="media-body">
                    <a href="#">
                        <?= $arrComment['username'] ?>
                    </a>

                    <small class="text-muted"><?= $arrComment['datetime'] ?></small>
                </div>
            </div>

            <div class="social-body">
                <?= $arrComment['comment'] ?>
                <!--<div class="btn-group">
                    <button class="btn btn-white btn-xs"><i class="fa fa-thumbs-up"></i> Like this!</button>
                    <button class="btn btn-white btn-xs"><i class="fa fa-comments"></i> Comment</button>
                    <button class="btn btn-white btn-xs"><i class="fa fa-share"></i> Share</button>
                </div>-->
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="col-lg-12" style="margin-top:2%;">
    <textarea name="comment" placeholder="Comment" id="comment" class="form-control"></textarea> 
</div>                                 
</div>

<script>

    $ ("#saveNewComment").click (function ()
    {
        var SendUrl = "/FormBuilder/comments/saveComments";
        $ ('#saveNewComment').prop ('disabled', true);

        $.ajax ({
            type: "POST",
            url: SendUrl,
            data: {"comment": $ ("#comment").val (), "projectId": $ ("#projectId").val ()},
            success: function (response)
            {
                if ($.trim (response) != "")
                {
                    $ (".alert-danger").slideDown ().delay (3000).slideUp ();
                }
                else
                {
                    $ (".alert-success").slideDown ().delay (3000).slideUp ();
                    $(".SearchButton").click();
                    $ ("#comment").val ("");

                    var count = parseInt ($ (".openComments > span").text ());

                    if (isNaN (count))
                    {
                        $ (".openComments > span").text (1);
                    }
                    else
                    {
                        $ (".openComments > span").text (parseInt ($ (".openComments > span").text ()) + 1);
                    }
                    
                    $(".openComments").click();

                }

                $ ('#saveNewComment').prop ('disabled', false);
            }
        });
    });
</script>


