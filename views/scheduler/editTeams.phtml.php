<style>
    .modal-title {
        color: #000;
    }

    .img-circle {
        z-index: 9999999;
    }
</style>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>

            <h4 class="modal-title">Edit Teams</h4>
        </div>

        <div class="modal-body">
            <div class="row">

                <?php foreach ($arrAllTeams as $arrAllTeam): ?>

                    <div class="col-lg-12">
                        <div class="ibox pull-left col-lg-12">
                            <div class="ibox-title">
                                <h5><?= $arrAllTeam['team_name'] ?></h5>
                                <div class="infont col-md-2 col-sm-2 pull-right deleteTeam" teamid="<?= $arrAllTeam['team_id'] ?>" style="font-size: 26px; line-height: 20px; color: #000"><i class="fa fa-trash-o"></i></div>
                            </div>
                            <div teamid="<?= $arrAllTeam['team_id'] ?>" class="ibox-content teamBox pull-left col-lg-12">
                                <div class="team-members pull-left col-lg-12">
                                    <?php foreach ($arrTeams[$arrAllTeam['team_id']] as $arrTeam): ?>
                                        <img userid="<?= $arrTeam['usrid'] ?>" alt="<?= $arrTeam['username'] ?>" class="img-circle dragUser" src="/FormBuilder/public/img/users/<?= $arrTeam['img_src'] ?>">
                                    <?php endforeach; ?>    
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>

                <div class="col-lg-12">
                    <div class="ibox pull-left col-lg-12">
                        <div class="ibox-title">
                            <h5>Unclassified</h5>
                            
                        </div>
                        <div class="ibox-content pull-left col-lg-12">
                            <div class="team-members pull-left col-lg-12">
                                <?php foreach ($arrUnclassified as $arrUnclassifiedUser): ?>
                                    <img userid="<?= $arrUnclassifiedUser['usrid'] ?>" alt="<?= $arrUnclassifiedUser['username'] ?>" class="img-circle dragUser" src="/FormBuilder/public/img/users/<?= $arrUnclassifiedUser['img_src'] ?>">
                                <?php endforeach; ?>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
    </div>
</div>


<script>
    $ (document).ready (function ()
    {
        $ (".deleteTeam").click (function ()
        {
            var teamid = $ (this).attr ("teamid");

            $.ajax ({
                url: "/FormBuilder/scheduler/deleteTeam",
                type: "POST",
                data: {teamid: teamid},
                success: function (data, textStatus, jqXHR)
                {
                    alert (data);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert ("ERROR SENDING POST REQUEST");
                }
            });

        });

        $ ('.dragUser').draggable ({
            cursor: 'move',
            revert: "invalid"
        });

        $ (".teamBox").droppable ({
            accept: ".dragUser",
            activeClass: "over",
            drop: function (event, ui)
            {
                console.log ("drop");
                $ (this).removeClass ("border").removeClass ("over");
                var dropped = ui.draggable;
                var userid = dropped.attr ("userid");
                var teamid = $ (this).attr ("teamid");
                var droppedOn = $ (this);
                $ (dropped).detach ().css ({top: 0, left: 0}).appendTo (droppedOn);

                $.ajax ({
                    url: "/FormBuilder/scheduler/updateTeams",
                    type: "POST",
                    data: {userid: userid, teamid: teamid},
                    success: function (data, textStatus, jqXHR)
                    {
                        alert (data);
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert ("ERROR SENDING POST REQUEST");
                    }
                });
            }
        });

        $ (".teamBox").sortable ();
    });
</script>