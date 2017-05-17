

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
        <h2>Project list</h2>

        <ol class="breadcrumb">
            <li>
                <a href="index.html">Home</a>
            </li>

            <li class="active">
                <strong>Project list</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    <div class="ibox-content m-b-sm border-bottom">
        <form id="searchForm" name="searchForm">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="order_id">Order ID</label>
                        <input type="text" id="order_id" name="order_id" value="" placeholder="Order ID" class="form-control">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="status">Project Members</label>
                        <select id="projectAssigned" name="projectAssigned" placeholder="Assigned To" class="form-control">
                            <option value="null">Select User</option>
                            <?php foreach ($arrUsers as $arrUser): ?>
                                <option value="<?= $arrUser['username'] ?>"><?= $arrUser['username'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="customer">Priority</label>
                        <select id="projectPriority" name="projectPriority" class="form-control">
                            <option value="null">Select Priority</option>
                            <?php foreach ($arrPriorities as $arrPriority): ?>
                                <option value="<?= $arrPriority['id'] ?>"><?= $arrPriority['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="date_added">Date added</label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="date_added" type="text" class="form-control" value="03/04/2014">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="date_modified">Date modified</label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="date_modified" type="text" class="form-control" value="03/06/2014">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="amount">Amount</label>
                        <input type="text" id="amount" name="amount" value="" placeholder="Amount" class="form-control">
                    </div>
                </div>
            </div>

            <button type="button" id="SearchButton" class="btn btn-w-m btn-primary">Search</button>
            <button type="button" id="ResetButton" class="btn btn-w-m btn-danger">Reset</button>
        </form>
    </div>

    <div class="row mainWrapper">

        <!-- Departments -->
        <div class="col-lg-3 departmentsWrapper" style="padding: 0!important;">
            <div class="wrapper wrapper-content animated fadeInUp">

                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Departments</h5>
                    </div>

                    <div class="ibox-content">
                        <div class="input-group" style="width:100%;">
                            <input id="searchDept" type="text" placeholder="Search Departments " class="input form-control">
                        </div>
                        <div class="clients-list">
                            <div class="tab-pane active" style="height: 170px !important;">
                                <ul id="departmentlist" class=" sortable-list connectList agile-list ui-sortable" style="overflow: hidden; width: auto;">
                                    <?php foreach ($arrDepartments as $arrDepartment): ?> 
                                    <div class="departmentlistElement col-lg-11" id="<?= $arrDepartment['id'] ?>" selected="selected"><?= $arrDepartment['department'] ?></div>
                                    
                                    <div class="departmentlistElementRequests" deptid="<?= $arrDepartment['id'] ?>" style="float: left; display: none;"> 
                                        <?php foreach ($arrWorkflows[$arrDepartment['id']] as $arrWorkflow): ?>
                                        <div class=" requestTypelistElement col-lg-11" id="<?= $arrWorkflow['request_id'] ?>"><?= $arrWorkflow['request_type'] ?>
                                            <i data-toggle="modal" departmentid="<?= $arrDepartment["id"] ?>" requesttyepid="<?= $arrWorkflow['request_id'] ?>" data-target="#createProject" class="pull-right addTypeRequestAddIcon fa fa-plus-square-o" style="font-size: 14px; display: none;"></i>
					</div>
                                         <?php endforeach; ?>
                                    </div>
                                     <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 projectsWrapper" style="padding: 0!important;">
            <div class="wrapper wrapper-content animated fadeInUp">

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>All projects assigned to this account</h5>

                        <div class="ibox-tools">
                            <button type="button" id="createNewProject" class="btn btn-primary" data-toggle="modal" data-target="#createProject">
                                +
                            </button>
                        </div>
                    </div>

                    <div class="ibox-content">
                        <div class="row m-b-sm m-t-sm">

                            <div class="form-group">
                                <form id="moveStatus" name="moveStatus">
                                    <label class="col-sm-2 control-label">Status</label>

                                    <div class="col-sm-10">
                                        <label class="checkbox-inline i-checks" type="inlineCheckbox1"> 
                                            <input type="checkbox" value="0" id="inlineCheckbox1" class="changeStatus"> Requested
                                        </label> 

                                        <label class="checkbox-inline i-checks" type="inlineCheckbox1"> 
                                            <input type="checkbox" value="5" id="inlineCheckbox1" class="changeStatus"> Accepted
                                        </label> 

                                        <label class="checkbox-inline i-checks" type="inlineCheckbox2">
                                            <input type="checkbox" value="6" id="inlineCheckbox2" class="changeStatus"> Assigned 
                                        </label> 

                                        <label class="checkbox-inline i-checks" type="inlineCheckbox2">
                                            <input type="checkbox" value="1" id="inlineCheckbox2" class="changeStatus"> Started 
                                        </label> 

                                        <label class="checkbox-inline i-checks" type="inlineCheckbox3">
                                            <input type="checkbox" value="3" id="inlineCheckbox3" class="changeStatus"> Completed 
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <ul id="projectList" class="sortable-list connectList agile-list ui-sortable project-list" style="max-height: 600px; overflow: hidden; width: auto;">
                            
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php //include 'footer.php'; ?>
</div>
</div>


<div class="modal inmodal in" id="createProject" tabindex="-1" role="dialog" aria-hidden="true">

</div>

<div class="modal inmodal in" id="viewProject" tabindex="-1" role="dialog" aria-hidden="true">

</div>

<div class="modal inmodal in" id="commentsModal" tabindex="-1" role="dialog" aria-hidden="true">

</div>

<div class="modal inmodal in" id="attachmentsModal" tabindex="-1" role="dialog" aria-hidden="true">

</div>
<script src="/FormBuilder/public/js/index.js" type="text/javascript"></script>