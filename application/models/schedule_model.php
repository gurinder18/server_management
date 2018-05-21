<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Schedule_model extends CI_Model
{
    /**
     * This function is used to get the pending schedule listing count
    
     * @return number $count : This is row count
     */
    function schedulesCount($page, $segment,$search_data=Null,$date,$userId)
    {
        $this->db->select('BaseTbl.id, BaseTbl.date,BaseTbl.userId, BaseTbl.clientId, BaseTbl.backupId,
        BaseTbl.status');
        $this->db->from('tbl_backup_schedule as BaseTbl');
        
        $this->db->where('BaseTbl.date', $date);
        $this->db->where('BaseTbl.userId', $userId);
       
        $query = $this->db->get();
        
        return count($query->result());
    }
    
    /**
     * This function is used to get the pending schedule listing 
    
     * @return number $count : This is row count
     */
    function schedules($page, $segment,$search_data=Null,$date,$userId)
    {
        $this->db->select('BaseTbl.id, BaseTbl.date,BaseTbl.userId, BaseTbl.clientId, BaseTbl.backupId,
        BaseTbl.status,Client.name As ClientName,Backup.serverId,Server.name As ServerName,Server.server As ServerIP,
        Server.hostname As ServerHostname,Server.name As ServerName,Status.status As ScheduleStatus');
        $this->db->from('tbl_backup_schedule as BaseTbl');
        
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
        $this->db->join('tbl_backups as Backup', 'Backup.id = BaseTbl.backupId','left');
        $this->db->join('tbl_servers as Server', 'Server.id = Backup.serverId','left');
        $this->db->join('tbl_backup_status as Status', 'Status.id = BaseTbl.status','left');
      
        $this->db->where('BaseTbl.date', $date);
        $this->db->where('BaseTbl.userId', $userId);
        
        $likeCriteria='';
        if( $search_data['serverIP']!=null) {
            $likeCriteria = "( Server.server  LIKE '%".$search_data['serverIP']."%')";
        }
        if($search_data['hostname']!=null ) {
            $likeCriteria = "(Server.hostname  LIKE '%".$search_data['hostname']."%' )";
        }
        if(!$likeCriteria==''){
            $this->db->where($likeCriteria);
        }
        if($search_data['serverId']!=null){
            $this->db->where('Server.id', $search_data['serverId']);
        }
        if($search_data['clientId']!=null){
            $this->db->where('BaseTbl.clientId', $search_data['clientId']);
        }
        if($search_data['status']!=null){
            $this->db->where('BaseTbl.status', $search_data['status']);
        }
        $this->db->order_by('Client.name', 'asc');
        
        $this->db->limit($page, $segment);
        
        $query = $this->db->get();
        $result = $query->result();    
           
        return $result;
    }

    /**
     * This function is used to get the clients information
     * @return array $result : This is result of the query
     */
    function getClients()
    {
        $this->db->select('id, name');
        $this->db->from('tbl_clients');
        $this->db->where_not_in('isDeleted', [1]);
        $this->db->where('status', 1);
        $query = $this->db->get();
        if(count($query)>0){
            return $query->result_array();
        }
        return [];
    }
    /**
     * This function is used to get the servers information by client id
     * @return array $result : This is result of the query
     */
    function getServers()
    {
        $this->db->select(['id','name']);
        $this->db->from('tbl_servers');
        //$this->db->where('clientId', $clientId);
        $this->db->where_not_in('isDeleted', [1]);
        $this->db->where('status', 1);
        $query = $this->db->get();
        if(count($query)>0){
            return $query->result_array();
        }
        return [];
        
    }

     /**
     * This function used to get schedule information by id
     * @param number $id : This is server id
     * @return array $result : This is schedule information
     */
    function getScheduleInfo($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.date,BaseTbl.userId, BaseTbl.clientId, BaseTbl.backupId,
        BaseTbl.status,Client.name As ClientName,Backup.serverId,Backup.scheduleType,Server.name As ServerName,Server.server As ServerIP,
        Server.hostname As ServerHostname,Server.name As ServerName,Server.details As ServerDetails,Status.status As ScheduleStatus');
        $this->db->from('tbl_backup_schedule as BaseTbl');

        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
        $this->db->join('tbl_backups as Backup', 'Backup.id = BaseTbl.backupId','left');
        $this->db->join('tbl_servers as Server', 'Server.id = Backup.serverId','left');
        $this->db->join('tbl_backup_status as Status', 'Status.id = BaseTbl.status','left');

      
        $this->db->where('BaseTbl.id', $id);
        $query = $this->db->get();
        
        return $query->row_array();
    }
  /**
     * This function used to get comment information by id
     * @param number $id : This is server id
     * @return array $result : This is schedule information
     */
    function getCommentInfo($scheduleId)
    {
        $this->db->select('BaseTbl.id, BaseTbl.scheduleId,BaseTbl.userId, BaseTbl.statusId AS CommentStatus, BaseTbl.userComment,
        Attach.filePath As file');
        $this->db->from('tbl_backup_comments as BaseTbl');

        $this->db->join('tbl_backup_attachments as Attach', 'Attach.commentId = BaseTbl.id','left');
        
        $this->db->where('BaseTbl.scheduleId', $scheduleId);
        $query = $this->db->get();
        $result = $query->result();    
        
        return $result;
    }

      /**
     * This function is used to add new comment to system
     * @return number $insert_id : This is last inserted id
     */
    function addComment($commentInfo,$attachmentInfo=null)
    {
        
        $this->db->trans_start();
        $this->db->insert('tbl_backup_comments', $commentInfo);
        
        $insert_id = $this->db->insert_id();
        
      
        if(!$attachmentInfo==null && $insert_id >0)
        {
            $addAttachment = array('scheduleId'=>$attachmentInfo['scheduleId'],'commentId'=>$insert_id,
            'filePath'=>$attachmentInfo['filePath'],'createdDtm'=>date('Y-m-d H:i:s'));
            //print_r($addAttachment);die;
            $this->db->trans_start();
            $this->db->insert('tbl_backup_attachments', $addAttachment);
            
            $attachment_id = $this->db->insert_id();
           
        }
        $this->db->trans_complete();
        return $insert_id;
    }
    
    
    /**
     * This function is used to get the backups
     * @param string $search_data : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function backups( $page, $segment,$search_data)
    {
        $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.clientId, BaseTbl.serverId, BaseTbl.scheduleType,
         BaseTbl.scheduleTimings, BaseTbl.information,User.name As UserName,
         Client.name As ClientName,Server.name As ServerName');
         //$this->db->select('BaseTbl.*');
        $this->db->from('tbl_backups as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
        $this->db->join('tbl_servers as Server', 'Server.id = BaseTbl.serverId','left');

        $this->db->where('BaseTbl.isDeleted', 0);
        
        if($search_data['userId']!=null){
            $this->db->where('BaseTbl.userId', $search_data['userId']);
       }
       if($search_data['clientId']!=null){
            $this->db->where('BaseTbl.clientId', $search_data['clientId']);
       }
       if($search_data['serverId']!=null){
           $this->db->where('BaseTbl.serverId', $search_data['serverId']);
       }
       if($search_data['scheduleType']!=null){
            $this->db->where('BaseTbl.scheduleType', $search_data['scheduleType']);
       }
       if($search_data['scheduleTimings']!=null){
            $this->db->where('BaseTbl.scheduleTimings', $search_data['scheduleTimings']);
        }
        $this->db->limit($page, $segment);
        
        $query = $this->db->get();
        $result = $query->result();    
        //print_r($result); die;    
        return $result;
    }

    /**
     * This function is used to get the backup listing count for member accessing
    
     * @return number $count : This is row count
     */
    function memberbackupCount($page=null, $segment=null,$userId,$search_data=null)
    {
        $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.clientId, BaseTbl.serverId, BaseTbl.scheduleType,
        BaseTbl.scheduleTimings, BaseTbl.information,User.name As UserName,
        Client.name As ClientName,Server.name As ServerName');
        //$this->db->select('BaseTbl.*');
       $this->db->from('tbl_backups as BaseTbl');
       $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
       $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
       $this->db->join('tbl_servers as Server', 'Server.id = BaseTbl.serverId','left');

       $this->db->where('BaseTbl.isDeleted', 0);
       
       if($search_data['userId']!=null){
           $this->db->where('BaseTbl.userId', $search_data['userId']);
      }
      if($search_data['clientId']!=null){
           $this->db->where('BaseTbl.clientId', $search_data['clientId']);
      }
      if($search_data['serverId']!=null){
          $this->db->where('BaseTbl.serverId', $search_data['serverId']);
      }
      if($search_data['scheduleType']!=null){
           $this->db->where('BaseTbl.scheduleType', $search_data['scheduleType']);
      }
      if($search_data['scheduleTimings']!=null){
           $this->db->where('BaseTbl.scheduleTimings', $search_data['scheduleTimings']);
       }
       $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        return count($query->result());
    }
    
    /**
     * This function is used to get the backups only of loggedin member
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function membersBackups( $page, $segment,$userId,$search_data=NULL)
    {
        $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.clientId, BaseTbl.serverId, BaseTbl.scheduleType,
        BaseTbl.scheduleTimings, BaseTbl.information,User.name As UserName,
        Client.name As ClientName,Server.name As ServerName');
        //$this->db->select('BaseTbl.*');
       $this->db->from('tbl_backups as BaseTbl');
       $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
       $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
       $this->db->join('tbl_servers as Server', 'Server.id = BaseTbl.serverId','left');

       $this->db->where('BaseTbl.isDeleted', 0);
       $this->db->where('BaseTbl.userId', $userId);
       
        if($search_data['clientId']!=null){
                $this->db->where('BaseTbl.clientId', $search_data['clientId']);
        }
        if($search_data['serverId']!=null){
            $this->db->where('BaseTbl.serverId', $search_data['serverId']);
        }
        if($search_data['scheduleType']!=null){
                $this->db->where('BaseTbl.scheduleType', $search_data['scheduleType']);
        }
        if($search_data['scheduleTimings']!=null){
                $this->db->where('BaseTbl.scheduleTimings', $search_data['scheduleTimings']);
        }

       $this->db->limit($page, $segment);
        
       $query = $this->db->get();
       $result = $query->result();    
       
       return $result;
    }
   

     /**
     * This function is used to get the servers information by id
     * @return array $result : This is result of the query
     */
    function getUsers()
    {
        $this->db->select('userId, name');
        $this->db->from('tbl_users');
        $this->db->where('roleId =', 2);
        $this->db->where('isDeleted !=', 1);
        $this->db->where('status', 1);
        $query = $this->db->get();
    
        return $query->result();
    }
    
     /**
     * This function is used to get the client information by id
     * @return array $result : This is result of the query
     */
    function getClientById($clientId)
    {
        $this->db->select('id, name ,phone,email,address,city,state,zip,status');
        $this->db->from('tbl_clients');
        $this->db->where('id =', $clientId);
        $this->db->where('isDeleted !=', 1);
        $this->db->where('status', 1);
        $query = $this->db->get();
        //print_r($query->result());
        return $query->result();
    }
     /**
     * This function is used to get the servers information by id
     * @return array $result : This is result of the query
     */
    function getServerById($serverId)
    {
        $this->db->select('id, name ,server,hostname,username,password,status,details');
        $this->db->from('tbl_servers');
        $this->db->where('id =', $serverId);
        $this->db->where('isDeleted !=', 1);
        $this->db->where('status', 1);
        $query = $this->db->get();
    
        return $query->result();
    }
  
   
     
    /**
     * This function is used to update the server information
     * @param array $backupInfo : This is servers updated information
     * @param number $id : This is server id
     */
    function updateScheduleStatus($scheduleInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_backup_schedule', $scheduleInfo);
        //var_dump($this->db);die;
        return TRUE;
    }
    
}

  