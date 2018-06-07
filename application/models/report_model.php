<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Report_model extends CI_Model
{
    /**
     * This function is used to get the today schedule listing count
    
     * @return number $count : This is row count
     */
    function reportCount($page, $segment,$search_data=Null, $fromDate,$toDate)
    {
        $this->db->select('BaseTbl.id, BaseTbl.date,BaseTbl.userId, BaseTbl.clientId, BaseTbl.backupId,
        BaseTbl.status,Client.name As ClientName,Backup.scheduleType As ScheduleType,
        Backup.scheduleTimings As Day,Backup.serverId,Server.name As ServerName,Server.server As ServerIP,
        Server.hostname As ServerHostname,Server.name As ServerName,Status.status As ScheduleStatus');

        $this->db->from('tbl_backup_schedule as BaseTbl');

        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
        $this->db->join('tbl_backups as Backup', 'Backup.id = BaseTbl.backupId','left');
        $this->db->join('tbl_servers as Server', 'Server.id = Backup.serverId','left');
        $this->db->join('tbl_backup_status as Status', 'Status.id = BaseTbl.status','left');

        if($search_data==null)
        {
            $this->db->where('BaseTbl.createdAt BETWEEN "'. $fromDate.'" AND "'.$toDate.'"');
        }
        if($search_data['fromDate']!=null && $search_data['toDate']!=null)
        {
            $this->db->where('BaseTbl.createdAt BETWEEN "'. $search_data['fromDate'].'" AND "'.$search_data['toDate'].'"');
        }
       
        if($search_data['serverId']!=null)
        {
            $this->db->where('Server.id', $search_data['serverId']);
        }
        if($search_data['clientId']!=null)
        {
            $this->db->where('BaseTbl.clientId', $search_data['clientId']);
        }
        if($search_data['userId']!=null)
        {
            $this->db->where('BaseTbl.userId', $search_data['userId']);
        }
        if($search_data['status']!=null)
        {
            $this->db->where('BaseTbl.status', $search_data['status']);
        }
        if($search_data['scheduleType']!=null)
        {
            $this->db->where('Backup.scheduleType', $search_data['scheduleType']);
        }
      
        $query = $this->db->get();
        
        return count($query->result());
    }
    
    /**
     * This function is used to get the todays schedule listing 
    
     */
    function report($page, $segment,$search_data=Null, $fromDate,$toDate)
    {
        $this->db->select('BaseTbl.id, BaseTbl.date,BaseTbl.userId, BaseTbl.clientId, BaseTbl.backupId,
        BaseTbl.status,Client.name As ClientName,Backup.scheduleTimings As Day,Backup.serverId,
        Server.name As ServerName,Server.server As ServerIP, Server.hostname As ServerHostname,
        Server.name As ServerName,Status.status As ScheduleStatus,User.name As UserName,
        Backup.scheduleType As ScheduleType,');

        $this->db->from('tbl_backup_schedule as BaseTbl');
       
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
        $this->db->join('tbl_backups as Backup', 'Backup.id = BaseTbl.backupId','left');
        $this->db->join('tbl_servers as Server', 'Server.id = Backup.serverId','left');
        $this->db->join('tbl_backup_status as Status', 'Status.id = BaseTbl.status','left');

        if($search_data==null)
        {
            $this->db->where('BaseTbl.createdAt BETWEEN "'. $fromDate.'" AND "'.$toDate.'"');
        }
        if($search_data['serverId']!=null)
        {
            $this->db->where('Server.id', $search_data['serverId']);
        }
        if($search_data['clientId']!=null)
        {
            $this->db->where('BaseTbl.clientId', $search_data['clientId']);
        }
        if($search_data['userId']!=null)
        {
            $this->db->where('BaseTbl.userId', $search_data['userId']);
        }
        if($search_data['status']!=null)
        {
            $this->db->where('BaseTbl.status', $search_data['status']);
        }
        if($search_data['scheduleType']!=null)
        {
            $this->db->where('Backup.scheduleType', $search_data['scheduleType']);
        }
        if($search_data['fromDate']!=null && $search_data['toDate']!=null)
        {
            $this->db->where('BaseTbl.createdAt BETWEEN "'. $search_data['fromDate'].'" AND "'.$search_data['toDate'].'"');
        }
        
        $this->db->limit($page, $segment);
        $this->db->order_by("BaseTbl.date", "asc");
        $this->db->order_by("Client.name", "asc");
        $this->db->order_by("User.name", "asc");
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
     * This function is used to get the users information 
     * @return array $result : This is result of the query
     */
    function getUsers()
    {
        $this->db->select('BaseTbl.userId, BaseTbl.name');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        $this->db->where('Role.slug ', "member" );
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
        //print_r($this->db);die;
        return TRUE;
    }
    /**
     * This function is used to get the attachments information
     * @return array $result : This is result of the query
     */
    function getAttachments($search_data)
    {
        $this->db->select('id, scheduleId, commentId, filePath, createdDtm');
        $this->db->from('tbl_backup_attachments');
        
        $this->db->where('createdDtm BETWEEN "'. $search_data['fromDate'].'" AND "'.$search_data['toDate'].'"');
        
        $query = $this->db->get();
        if(count($query)>0){
            return $query->result_array();
        }
        return [];
    }
}

  