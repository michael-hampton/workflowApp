<?php

class Teams extends BaseModel
{

    protected $table = "user_management.poms_users";
    public $id;
    public $object;

  
    public function insertUser($firstName, $lastName, $email, $username)
    {
        $this->_insert($this->table, array("firstName" => $firstName, "lastName" => $lastName, "user_email" => $email, "username" => $username, "team_id" => 1));
    }
    
    public function updateTeamMember($teamId, $userId)
    {
        $this->_update("user_management.poms_users", array("team_id" => $teamId), array("usrid" => $userId));
    }
    
    public function deleteTeam($teamId)
    {
        $this->_update("user_management.teams", array("team_id" => $teamId));
    }
}
