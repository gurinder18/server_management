<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Backup_model extends CI_Model
{
    /**
     * This function is used to get the backup listing count
    
     * @return number $count : This is row count
     */
    function backupListingCount( $page, $segment,$search_data=null)
    {
        $this->db->select('BaseTbl.id,BaseTbl.userId, BaseTbl.clientId, BaseTbl.serverId,
        BaseTbl.scheduleType, BaseTbl.scheduleTimings, BaseTbl.information');
        $this->db->from('tbl_backups as BaseTbl');
        //$this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
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
        $this->db->where('BaseTbl.isDeleted', 0);
        //$this->db->where('BaseTbl.roleId !=', 1);
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        return count($query->result());
    }
    

    /**
     * This function is used to get the backups
     * @param string $search_data : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function backups( $page, $segment,$search_data=null)
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
        $this->db->order_by("BaseTbl.createdDtm", "asc");
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
        $this->db->order_by("BaseTbl.createdDtm", "asc");
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
     * This function is used to get the clients information
     * @return array $result : This is result of the query
     */
    function getClients()
    {
        $this->db->select('id, name');
        $this->db->from('tbl_clients');
        $this->db->where('isDeleted !=', 1);
        $this->db->where('status', 1);
        $query = $this->db->get();
    
        return $query->result();
    }
    /**
     * This function is used to get the servers information by client id
     * @return array $result : This is result of the query
     */
    function getServers($clientId)
    {
        $this->db->select(['id','name']);
        $this->db->from('tbl_servers');
        $this->db->where('clientId', $clientId);
        $this->db->where_not_in('isDeleted', [1]);
        $this->db->where('status', 1);
        $query = $this->db->get();
        if(count($query)>0){
            return $query->result_array();
        }
        return [];
        
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
     * This function is used to add new server to system
     * @return number $insert_id : This is last inserted id
     */
    function addBackup($backupInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_backups', $backupInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get server information by id
     * @param number $id : This is server id
     * @return array $result : This is server information
     */
    function getBackupInfo($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.clientId, BaseTbl.serverId, BaseTbl.scheduleType,
        BaseTbl.scheduleTimings, BaseTbl.information,User.name As UserName,
        Client.name As ClientName,Server.name As ServerName');

        $this->db->from('tbl_backups as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
        $this->db->join('tbl_servers as Server', 'Server.id = BaseTbl.serverId','left');

        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.id', $id);
        $query = $this->db->get();
        
        return $query->row_array();
    }
    
     
    /**
     * This function is used to update the server information
     * @param array $backupInfo : This is servers updated information
     * @param number $id : This is server id
     */
    function editBackup($backupInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_backups', $backupInfo);
        //print_r($this->db);die;
        return TRUE;
    }
    
    /**
     * This function is used to delete the server information
     * @param number $id : This is server id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteBackup($id, $backupInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_backups', $backupInfo);
    
        return $this->db->affected_rows();
    }
     /**
     * This function is used to get todays backup schedule information
     */
    function getBackups( $timing)
    {
        $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.clientId, BaseTbl.serverId, BaseTbl.scheduleType,
        BaseTbl.scheduleTimings, BaseTbl.information,User.name As UserName,User.email As UserEmail,
        Client.name As ClientName,Server.name As ServerName,Server.server As ServerIP,
        Server.hostname As ServerHostname');
        //$this->db->select('BaseTbl.*');
       $this->db->from('tbl_backups as BaseTbl');
       $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
       $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
       $this->db->join('tbl_servers as Server', 'Server.id = BaseTbl.serverId','left');

       $this->db->where('BaseTbl.isDeleted', 0);
       $this->db->where('BaseTbl.scheduleType', "Daily");
       $this->db->or_where('BaseTbl.scheduleTimings', $timing['date']);
       $this->db->or_where('BaseTbl.scheduleTimings',$timing['day']);
      
       $query = $this->db->get();
      
       $result = $query->result();    
       
       return $result;
    }
     /**
     * This function is used to  add backup schedule information
     */
    function addBackupSchedule($scheduleInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_backup_schedule', $scheduleInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    /**
     * This function is used to get the todays backup schedule- users email
     */
    function getBackupsUserEmail($date)
    {
        $this->db->select('BaseTbl.id,User.email As UserEmail, BaseTbl.userId As UserId');
       
        $this->db->from('tbl_backup_schedule as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
       
        $this->db->where('BaseTbl.date', $date);
        $this->db->group_by('User.email');
        $query = $this->db->get();
      
        $result = $query->result();    
       
        return $result;
    }
     /**
     * This function is used to get the todays backup schedule of particular user
     */
    function getTodaysBackup($data)
    {
        $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.clientId, 
        User.name As UserName,User.email As UserEmail,Client.name As ClientName,
        Server.name As ServerName,Server.server As ServerIP,
        Server.hostname As ServerHostname');
        //$this->db->select('BaseTbl.*');
        $this->db->from('tbl_backup_schedule as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
        $this->db->join('tbl_backups as Backup', 'Backup.id = BaseTbl.backupId','left');
        $this->db->join('tbl_servers as Server', 'Server.id = Backup.serverId','left');
 
        $this->db->where('BaseTbl.date', $data['date']);
        $this->db->where('User.email', $data['email']);
        $query = $this->db->get();
       
        $result = $query->result();    
       
        return $result;
    }
   /**
     * This function is used to add mail log 
     */
    function addMailLog($mailLogInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_mail_log', $mailLogInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    /**
     * This function is used to get todays backup schedule information
     */
    function getPendingBackups($scheduleInfo)
    {
        $this->db->select('BaseTbl.id as scheduleId ,BaseTbl.backupId,Backup.id ,  Backup.clientId, Backup.serverId,
        Backup.scheduleType,Backup.scheduleTimings, Backup.information,User.name As UserName,
        User.email As UserEmail,Client.name As ClientName,Server.name As ServerName,
        Server.server As ServerIP, Server.hostname As ServerHostname,BaseTbl.userId');
        //$this->db->select('BaseTbl.*');
       $this->db->from('tbl_backup_schedule as BaseTbl');
       $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
       $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
       $this->db->join('tbl_backups as Backup', 'Backup.id = BaseTbl.backupId','left');
       $this->db->join('tbl_servers as Server', 'Server.id = Backup.serverId','left');
        
       //$this->db->where('BaseTbl.isDeleted', 0);
       if($scheduleInfo['daily'] != '')
       {
            $this->db->where('Backup.scheduleType', "Daily");
       }
       $this->db->where('BaseTbl.status', 1);
       if($scheduleInfo['fullDate']!='')
       {
            $this->db->where('BaseTbl.date', $scheduleInfo['fullDate']);
       }
       $this->db->where('BaseTbl.userId', $scheduleInfo['user']);
       if($scheduleInfo['backupId']!='')
       {
            $this->db->where('BaseTbl.backupId', $scheduleInfo['backupId']);
       }
    //    if($scheduleInfo['date'] != '')
    //    {
    //         $this->db->or_where('Backup.scheduleTimings', $scheduleInfo['date']);
    //    }
    //    if($scheduleInfo['day'] != '')
    //    {
    //         $this->db->or_where('Backup.scheduleTimings',$scheduleInfo['day']);
    //         $this->db->where('BaseTbl.date', $scheduleInfo['fullDate']);

    //    }
      
       $query = $this->db->get();
       $result = $query->result();    
      //print_r( $this->db);
       return $result;
    }
}

  