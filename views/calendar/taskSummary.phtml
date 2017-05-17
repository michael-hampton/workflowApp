<style>
    .lineH {
        line-height: 30px;
    }
</style>

<div class="modal-dialog">
    <div class="modal-content animated bounceInRight">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            <i class="fa fa-laptop modal-icon"></i>
            <h4 class="modal-title"> <?= $task['project_name'] ?></h4>
        </div>
        <div class="modal-body">
            <div class="ibox-content">
                <form class="form-horizontal">
                    <input type="hidden" id="projectId" name="projectId" value="<?= $task['id'] ?>">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Name</label>

                        <div class="col-lg-8 lineH">
                            <?= $task['project_name'] ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Description</label>

                        <div class="col-lg-8 lineH">
                            <?= $task['description'] ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label">Date Added</label>

                        <div class="col-lg-8 lineH">
                            <?= date ("Y-m-d", strtotime ($arrJSON['date_added'])) ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label">Date Started</label>

                        <div class="col-lg-8 lineH">
                            <?= isset($arrJSON['date_started']) ? date ("Y-m-d", strtotime ($arrJSON['date_started'])) : '' ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label">Priority</label>

                        <div class="col-lg-8 lineH">
                            <?= $task['priority_name'] ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label">Due Date</label>

                        <div class="col-lg-8 lineH">
                            <?= date ("Y-m-d", strtotime ($arrJSON['due_date'])) ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label">Assigned To</label>

                        <div class="col-lg-8 lineH">
                            <?= isset($arrJSON['assigned_for']) ? $arrJSON['assigned_for'] : ''  ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label">Re-assign to</label>
                        <div class="col-lg-8">
                            <select id="assignTo" name="assignTo" class="form-control">
                                <option value="">Select User</option>
                                <?php foreach ($arrUsers as $arrUser): ?>
                                    <option value="<?= $arrUser['username'] ?>"><?= $arrUser['username'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            <button type="button" id="Assign" class="btn btn-primary">Save changes</button>

            <?php if (!isset($arrJSON['accepted_by'])): ?>
                <button id="reject" moveTo="4" type="button" class="btn btn-w-m btn-danger changeStatus">Reject</button>
                <button id="accept" moveTo="5" type="button" class="btn btn-w-m btn-primary changeStatus">Accept</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $ (document).ready (function ()
    {
        $ ("#Assign").click (function ()
        {
            $.ajax ({
                type: "POST",
                url: "/FormBuilder/calendar/updateTeamMember/" + $ ("#projectId").val () + "/" + $ ("#assignTo").val (),
                success: function (response)
                {
                    alert (response);
                    //$(".fc-view-container").html(response);
                }
            });
        });

        $ (".changeStatus").click (function ()
        {
            var formData = null;
            var moveTo = $ (this).attr ("moveTo");
            var id = $ (this).attr ("id");
            var projectId = $ ("#projectId").val ();

            $ (".alert").hide ();

            if (id == "reject")
            {
                formData = {"reason": $ ("#reason").val ()};
            }

            if (id == "accept")
            {
                formData = {"priority": $ ("#priority").val ()};
            }

            if (id == "assignProject")
            {
                formData = {"assignedTo": $ ("#assignTo").val ()};
            }

            SendUrl = "/FormBuilder/index/changeStatus/" + projectId + "/" + "/" + moveTo;

            $.ajax ({
                type: "POST",
                url: SendUrl,
                data: formData,
                success: function (response)
                {
                    if ($.trim (response) == "")
                    {
                        refreshUnassignedTasks();
                    }
                    else
                    {
                        var objResponse = $.parseJSON ($.trim (response));

                        $.each (objResponse, function (index, element)
                        {
                            $ ("#" + element).slideDown ();
                        });
                    }

                    //$(".elements-list").html(response);
                    //rebindElements();
                }
            });
        });
    });

    function refreshUnassignedTasks ()
    {
        $.ajax ({
            type: "GET",
            url: '/FormBuilder/calendar/refreshUnassignedTasks',
            success: function (response)
            {
                $("#external-events").html(response);
                externalEvents();
            }
        });
    }
</script>