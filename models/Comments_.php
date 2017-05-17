<?php
class Comments extends BaseModel
{
    protected $table = "comments";
    
    public function getComments($projectId)
    {
        return $this->_select($this->table, array(), array("comment_type" => 2, "source_id" => $projectId), array("datetime" => "DESC"));
    }
    
    public function saveNewComment($commentType, $comment, $sourceId)
    {
        $arrParameters = array("source_id" => $sourceId, "comment" => $comment, "datetime" => date('Y-m-d H:i:s'), "username" => $_SESSION['user']['username'], "comment_type" => $commentType);
        $this->_insert($this->table, $arrParameters);
    }
}