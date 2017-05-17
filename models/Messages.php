<?php
class Messages extends BaseModel
{

    public $db;
    
    public function onConstruct()
    {
        $this->db = \Phalcon\DI\FactoryDefault::getDefault()->getShared('db');
    }
    
    public function getAllSites()
    {
        $sql = "SELECT * FROM channels.websites ORDER BY websiteId, websiteName";
        $arrParameters = array();
        $result = $this->db->query($sql, $arrParameters);
        $arrResultSet = $result->fetchAll();

        return $arrResultSet;
    }

    
    public function getAllTemplatesCount( $searchPost = null )
    {
        $arrParameters = array();

        $strDbSql = "SELECT COUNT(*) AS totalCount FROM orders.message_templates m";
        
         if ( is_array ( $searchPost ) ) {
            $strDbSql .= " WHERE 1=1";
            
            if(!empty($searchPost['templateName'])) {
                $strDbSql .= " AND (templateName = ? OR templateSubject = ?)";
                $arrParameters[] = $searchPost['templateName'];
                $arrParameters[] = $searchPost['templateName'];
            }
            
            if(!empty($searchPost['sender'])) {
                $strDbSql .= " AND (sendersName = ? OR sendersEmail = ?)";
                $arrParameters[] = $searchPost['sender'];
                $arrParameters[] = $searchPost['sender'];
            }

            if(!empty($searchPost['website']) && $searchPost['website'] != "null") {
                $strDbSql .= " AND channel = ?";
                $arrParameters[] = $searchPost['website'];
            }
            
            if(!empty($searchPost['dateFrom'])) {
                $strDbSql .= " AND date_added BETWEEN ? AND ?";
                $arrParameters[] = $searchPost['dateFrom'];
                 $arrParameters[] = $searchPost['dateTo'];
            }
        }
                      
         $result = $this->db->query ( $strDbSql, $arrParameters );
        $arrResultSet = $result->fetchAll ();

        return $arrResultSet[0]['totalCount'];
    }
    
    public function getAllMessagesCount ( $searchPost = null )
    {
        $arrParameters = array();

        $strDbSql = "SELECT COUNT(*) AS totalCount FROM orders.messages m 
                      INNER JOIN product_cms.customers cus ON cus.customerId = m.userId
                      WHERE message_id = 0";
                      
         if ( is_array ( $searchPost ) ) {
            if(!empty($searchPost['pageName'])) {
                
            }
            
            if(!empty($searchPost['archived']) && $searchPost['archived'] == 1) {
                $strDbSql .= " AND archived = 1";
            } else {
                $strDbSql .= " AND archived = 0";
            }
            
            if(!empty($searchPost['trashed']) && $searchPost['trashed'] == 1) {
                $strDbSql .= " AND trash = 1";
            } else {
                $strDbSql .= " AND trash = 0";
            }
            
            if((isset($searchPost['status'])) && $searchPost['status'] != "null" && $searchPost['status'] != "") {
                $strDbSql .= " AND m.has_read = ?";
                $arrParameters[] = $searchPost['status'];
            }
            
             if((isset($searchPost['messageType'])) && $searchPost['messageType'] != "null" && $searchPost['messageType'] != "") {
                $strDbSql .= " AND m.message_type = ?";
                $arrParameters[] = $searchPost['messageType'];
            }
            
            if(!empty($searchPost['website']) && $searchPost['website'] != "null") {
                $strDbSql .= " AND m.channel = ?";
                $arrParameters[] = $searchPost['website'];
            }
            
            if(!empty($searchPost['dateFrom']) && !empty($searchPost['dateTo'])) {
                $strDbSql .= " AND date_added BETWEEN ? AND ?";
                $arrParameters[] = $searchPost['dateFrom'];
                $arrParameters[] = $searchPost['dateTo'];
            }
        }
        
        $result = $this->db->query ( $strDbSql, $arrParameters );
        $arrResultSet = $result->fetchAll ();

        return $arrResultSet[0]['totalCount'];
    }
    
    public function getAllTemplates ( $searchPost = null, $page = 0, $page_limit = 10, $orderByField, $orderDirRule )
    {
        $arrParameters = array();
        $totalRows = $this->getAllTemplatesCount ( $searchPost );
        
        $strDbSql = "SELECT * FROM orders.message_templates";
        
         if ( is_array ( $searchPost ) ) {
            $strDbSql .= " WHERE 1=1";
            
            if(!empty($searchPost['templateName'])) {
                $strDbSql .= " AND (templateName = ? OR templateSubject = ?)";
                $arrParameters[] = $searchPost['templateName'];
                $arrParameters[] = $searchPost['templateName'];
            }
            
            if(!empty($searchPost['sender'])) {
                $strDbSql .= " AND (sendersName = ? OR sendersEmail = ?)";
                $arrParameters[] = $searchPost['sender'];
                $arrParameters[] = $searchPost['sender'];
            }

            if(!empty($searchPost['website']) && $searchPost['website'] != "null") {
                $strDbSql .= " AND channel = ?";
                $arrParameters[] = $searchPost['website'];
            }
            
            if(!empty($searchPost['dateFrom'])) {
                $strDbSql .= " AND date_added BETWEEN ? AND ?";
                $arrParameters[] = $searchPost['dateFrom'];
                 $arrParameters[] = $searchPost['dateTo'];
            }
        }
        
         $strDbSql .= " ORDER BY " . $orderByField . " " . $orderDirRule;
                 
          ///////////////////////////////////////////////////////////////////////////////////////////////
        //
        //      Pagination
        //

        //all rows
        $_SESSION["pagination"]["total_counter"] = $totalRows;

        $current_page = $page;
        $startwith = $page_limit * $page;
        $total_pages = $totalRows / $page_limit;
        $_SESSION["pagination"]["current_page"] = $current_page;

        // calculating displaying pages
        $_SESSION["pagination"]["total_pages"] = (int)($totalRows / $page_limit);
        if ( fmod ( $totalRows, $page_limit ) > 0 )
            $_SESSION["pagination"]["total_pages"]++;

        //setting up sql offset and limit
        $strDbSql .= " LIMIT " . $startwith . "," . $page_limit;

        //echo $this->parms($strDbSql,$arrParameters);
        //die;

        //$arrParameters = array("userId" => $id);
        $result = $this->db->query ( $strDbSql, $arrParameters );
        $arrResultSet = $result->fetchAll ();

        return $arrResultSet;
    }

    public function getAllMessages ( $searchPost = null, $page = 0, $page_limit = 10, $orderByField, $orderDirRule )
    {

        $arrParameters = array();
        $totalRows = $this->getAllMessagesCount ( $searchPost );

        $strDbSql = "SELECT m.*, cus.customer_firstName, cus.customer_lastName, cus.email, mt.description FROM orders.messages m 
                      INNER JOIN product_cms.customers cus ON cus.customerId = m.userId
                      INNER JOIN orders.message_types mt ON mt.pk = m.message_type
                      WHERE 1=1";

        if ( is_array ( $searchPost ) ) {
            if(!empty($searchPost['pageName'])) {
                
            }
            
             if(!empty($searchPost['archived']) && $searchPost['archived'] == 1) {
                $strDbSql .= " AND archived = 1";
            } else {
                $strDbSql .= " AND archived = 0";
            }
            
            if(!empty($searchPost['trashed']) && $searchPost['trashed'] == 1) {
                $strDbSql .= " AND trash = 1";
            } else {
                $strDbSql .= " AND trash = 0";
            }
            
            if((isset($searchPost['status'])) && $searchPost['status'] != "null" && $searchPost['status'] != "") {
                $strDbSql .= " AND m.has_read = ?";
                $arrParameters[] = $searchPost['status'];
            }
            
            if(isset($searchPost['sent']) && $searchPost['sent'] == 1) {
                $strDbSql.= " AND sent = 1";
            } else {
                $strDbSql .= " AND m.message_id = 0";
            }
            
             if((isset($searchPost['messageType'])) && $searchPost['messageType'] != "null" && $searchPost['messageType'] != "") {
                $strDbSql .= " AND m.message_type = ?";
                $arrParameters[] = $searchPost['messageType'];
            }
            
            if(!empty($searchPost['website']) && $searchPost['website'] != "null") {
                $strDbSql .= " AND m.channel = ?";
                $arrParameters[] = $searchPost['website'];
            }
            
            if(!empty($searchPost['dateFrom']) && !empty($searchPost['dateTo'])) {
                $strDbSql .= " AND date_added BETWEEN ? AND ?";
                $arrParameters[] = $searchPost['dateFrom'];
                $arrParameters[] = $searchPost['dateTo'];
            }
        } else {
            //$strDbSql .= " AND message_id = 0";
        }

        //$strDbSql .= " GROUP BY m.returnId";
        $strDbSql .= " ORDER BY " . $orderByField . " " . $orderDirRule;

        ///////////////////////////////////////////////////////////////////////////////////////////////
        //
        //      Pagination
        //

        //all rows
        $_SESSION["pagination"]["total_counter"] = $totalRows;

        $current_page = $page;
        $startwith = $page_limit * $page;
        $total_pages = $totalRows / $page_limit;
        $_SESSION["pagination"]["current_page"] = $current_page;

        // calculating displaying pages
        $_SESSION["pagination"]["total_pages"] = (int)($totalRows / $page_limit);
        if ( fmod ( $totalRows, $page_limit ) > 0 )
            $_SESSION["pagination"]["total_pages"]++;

        //setting up sql offset and limit
        $strDbSql .= " LIMIT " . $startwith . "," . $page_limit;

        //echo $this->parms($strDbSql,$arrParameters);
        //die;

        //$arrParameters = array("userId" => $id);
        $result = $this->db->query ( $strDbSql, $arrParameters );
        $arrResultSet = $result->fetchAll ();
        
        foreach ($arrResultSet as $intKey => $arrResult) {
            $submessages = $this->getMessageById($arrResult['id']);
            $arrResultSet[$intKey]['count'] = count($submessages);
        }

        return $arrResultSet;
    }
    
    public function getAllMessagesForUser ( $id )
    {
        $sql = "SELECT * FROM orders.messages WHERE message_type != 1 AND message_id = 0 AND userId = :userId";

        $arrParameters = array( "userId" => $id );
        $result = $this->db->query ( $sql, $arrParameters );
        $arrResultSet = $result->fetchAll ();

        return $arrResultSet;
    }
    
    public function getMessageHeaderById($messageId) {
         $sql = "SELECT m.*, 
                cus.customer_firstName, 
                cus.customer_lastName,
                cus.email
                FROM orders.messages m
                INNER JOIN product_cms.customers cus ON cus.customerId = m.userId
                WHERE m.id = :messageId";

        $arrParameters = array( "messageId" => $messageId );
        $result = $this->db->query ( $sql, $arrParameters );
        $arrResultSet = $result->fetchAll ();

        return $arrResultSet;
    }
    
    public function getMessageById ( $messageId )
    {
          $sql = "SELECT m.*, 
                cus.customer_firstName, 
                cus.customer_lastName,
                cus.email
                FROM orders.messages m
                INNER JOIN product_cms.customers cus ON cus.customerId = m.userId
                WHERE m.message_id = :messageId";
       
        $arrParameters = array( "messageId" => $messageId );
        $result = $this->db->query ( $sql, $arrParameters );
        $arrResultSet = $result->fetchAll ();

        return $arrResultSet;
    }
    
    public function getCustomerEmailAddresses()
    {
        $sql = "SELECT DISTINCT `email`, concat(`customer_firstName`, ' ',  `customer_lastName`) AS name
                FROM `customers`
                GROUP BY `email`";
        
        $result = $this->db->query ( $sql );
        $arrResultSet = $result->fetchAll ();
        
        return $arrResultSet;
    }
    
    public function getMessageTypes() {
        $sql = "SELECT * FROM orders.message_types ORDER BY pk ASC";
        $result = $this->db->query ($sql);
        $arrResultSet = $result->fetchAll();
        
        return $arrResultSet;
    }
    
    function parms ( $string, $data )
    {
        $indexed = $data == array_values ( $data );
        foreach ( $data as $k => $v ) {
            if ( is_string ( $v ) )
                $v = "'$v'";
            if ( $indexed )
                $string = preg_replace ( '/\?/', $v, $string, 1 );
            else $string = str_replace ( ":$k", $v, $string );
        }

        return $string;
    }
    
    public function getMessageTemplates() {
        $sql = "SELECT * FROM orders.message_templates";
        $result = $this->db->query ($sql);
        $arrResultSet = $result->fetchAll();
    }
    
    public function getMessageTemplateById($id) {
        $sql = "SELECT * FROM orders.message_templates WHERE pk = ?";
        $arrParameters = array($id);
        
        $result = $this->db->query ($sql,$arrParameters);
        $arrResultSet = $result->fetchAll();
        
        return $arrResultSet;
    }
    
    public function getDataForDashboard()
    {
        $sql = "SELECT channel, COUNT(*) AS count FROM orders.messages GROUP BY channel";
        $result = $this->db->query ($sql);
        $arrResultSet = $result->fetchAll();
        
        return $arrResultSet;
    }
    
    public function getMessagesForOrder($orderId) {
        $arrMessages = array();
        
        $sql = "SELECT * FROM orders.messages WHERE returnId = ? AND message_id = 0";
        $arrParameters = array($orderId);
        $result = $this->db->query ($sql,$arrParameters);
        $arrResultSet = $result->fetchAll();
        
        foreach($arrResultSet as $arrResult) {
            $arrMessages['header'] = $arrResult;
            
            $sql = "SELECT * FROM orders.messages WHERE message_id = ?";
            $arrParameters = array($arrResult['id']);
            $result = $this->db->query ($sql,$arrParameters);
            $arrResultSet = $result->fetchAll();
            
            foreach($arrResultSet as $arrResult) {
                 $arrMessages['messages'][] = $arrResult;
            }
           
        }
    
        return $arrMessages;
    }
    
    public function saveNewTemplate($name, $content, $channel, $subject, $sendersName, $sendersEmail, $groupId) {
        $sql = "INSERT INTO orders.message_templates (templateName, templateSubject, sendersName, sendersEmail, content, channel, customer_group, date_added) VALUES(?, ?, ?, ?, ?, ?, ?, NOW())";
        $arrParameters = array($name, $subject, $sendersName, $sendersEmail, $content, $channel, $groupId);
        $this->db->execute($sql,$arrParameters);
    }
    
    public function updateMessageTemplate($id, $content, $name, $channel, $subject, $sendersName, $sendersEmail) {
        $sql = "UPDATE orders.message_templates SET templateName = ?, templateSubject = ?, sendersName = ?, sendersEmail = ?, content = ?, channel = ? WHERE pk = ?";
        $arrParameters = array($name, $subject, $sendersName, $sendersEmail, $content, $channel, $id);
        $this->db->execute($sql,$arrParameters);
    }
    
    public function saveNewMessage($comment, $user, $message_type) {
        $sql = "INSERT INTO orders.messages (comment, userId, message_type, date_added) VALUES (?, ?, ?, NOW())";
        $arrParameters = array($comment, $user, $message_type);
        $this->db->execute($sql,$arrParameters);
    }
    
    public function updateMessage($id, $comment, $message_type, $user) {
        $sql = "UPDATE orders.messages SET comment = ?, userId = ?, message_type = ? WHERE id = ?";
        $arrParameters = array($comment, $user, $message_type, $id);
        $this->db->execute($sql, $arrParameters);
    }
    
    public function deleteMessageBatch($id) {
        $sql = "UPDATE orders.messages SET trash = 1 WHERE id = ?";
        $arrParameters = array($id);
        $this->db->execute($sql,$arrParameters);
    }
    
    public function deleteSingleMessage($id) {
        $sql = "DELETE FROM orders.messages WHERE message_id = ?";
        $arrParameters = array($id);
        $this->db->execute($sql,$arrParameters);
    }
    
    public function addMessageLine($comment, $messageType, $messageId, $channel) {
        if(!isset($_SESSION['user']['userId']) || empty($_SESSION['user']['userId'])) {
            $_SESSION['user']['userId'] = 6;
        }
        
        $sql = "INSERT INTO orders.messages (comment, date_added, userId, message_type, message_id, channel, sent) VALUES(?, NOW(), ?, ?, ?, ?, 1)";
        $arrParameters = array($comment, $_SESSION['user']['userId'], $messageType, $messageId, $channel);
        $this->db->execute($sql, $arrParameters);
    }
    
    public function getEmailAddressesForGroup($groupId)
    {
        $sql = "SELECT DISTINCT `email` FROM `customers` WHERE `customerGroup` = ?";
        $arrParameters = array($groupId);
        $result = $this->db->query ($sql, $arrParameters);
        $arrResultSet = $result->fetchAll();
        
        return $arrResultSet;
    }
    
    public function sendMail($email_addresses, $subject, $message, $from) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <'.$from.'>' . "\r\n";
        
        if(is_array($email_addresses)) {
            $email_addresses = explode(",", $email_addresses);
            
            foreach($email_addresses as $email_address) {
                mail($email_address, $subject, $message, $headers);
            }
        } else {
            mail($email_addresses, $subject, $message, $headers);
        }
    }
    
    public function markAsRead($arrData) {
        
        foreach($arrData as $id) {
            $sql = "UPDATE orders.messages SET has_read = 1 WHERE id = ?";
            $arrParameters = array($id);
            $this->db->execute($sql,$arrParameters);
        }
    }
    
    public function archiveMessage($arrData) {
        
        foreach($arrData as $id) {
            $sql = "UPDATE orders.messages SET archived = 1 WHERE id = ?";
            $arrParameters = array($id);
            $this->db->execute($sql,$arrParameters);
        }
    }
}