<?php foreach ($arrDepartments as $arrDepartment): ?> 
                                    <div class="departmentlistElement col-lg-11" id="<?= $arrDepartment['id'] ?>" selected="selected"><?= $arrDepartment['department'] ?></div>
                                    
                                    <div class="departmentlistElementRequests" deptid="<?= $arrDepartment['id'] ?>" style="float: left; display: none;"> 
                                        <?php foreach ($arrWorkflows[$arrDepartment['id']] as $arrWorkflow): ?>
                                        <div class=" requestTypelistElement col-lg-11" id="<?= $arrWorkflow['id'] ?>"><?= $arrWorkflow['name'] ?>
                                            <i data-toggle="modal" departmentid="<?= $arrDepartment["id"] ?>" requesttyepid="<?= $arrWorkflow['id'] ?>" data-target="#createProject" class="pull-right addTypeRequestAddIcon fa fa-plus-square-o" style="font-size: 14px; display: none;"></i>
					</div>
                                         <?php endforeach; ?>
                                    </div>
                                     <?php endforeach; ?>