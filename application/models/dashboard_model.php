<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    /**
     * This function is used to get the pending schedule listing count
    
     * @return number $count : This is row count
     */
    function todaysPendingBackupCount($date,$userId=Null)
    {
        $this->db->select('BaseTbl.id, BaseTbl.date,BaseTbl.userId, BaseTbl.clientId, BaseTbl.backupId,
        BaseTbl.status');
        $this->db->from('tbl_backup_schedule as BaseTbl');
       
        $this->db->where('BaseTbl.date', $date);
        if(!$userId==NULL)
        {
            $this->db->where('BaseTbl.userId', $userId);
        }
        $this->db->where('BaseTbl.status', 1);
       
        $query = $this->db->get();
        
        return count($query->result());
    }
    /**
     * This function is used to get the todays schedule listing count
    
     * @return number $count : This is row count
     */
    function todaysBackupCount($date)
    {
        $this->db->select('BaseTbl.id, BaseTbl.date,BaseTbl.userId, BaseTbl.clientId, BaseTbl.backupId,
        BaseTbl.status');
        $this->db->from('tbl_backup_schedule as BaseTbl');
       
        $this->db->where('BaseTbl.date', $date);
      
        
        $query = $this->db->get();
        return count($query->result());
    }
  /**
     * This function is used to get the todays schedule listing count
    
     * @return number $count : This is row count
     */
    function userBackupDetails($date)
    {
        $this->db->select('BaseTbl.id, BaseTbl.date,BaseTbl.userId, BaseTbl.clientId, BaseTbl.backupId,
        BaseTbl.status');
        $this->db->from('tbl_backup_schedule as BaseTbl');
       
        $this->db->where('BaseTbl.date', $date);
      
        $query = $this->db->get();
        return count($query->result());
    }
   
     /**
     * This function is used to get the users information
     * @return array $result : This is result of the query
     */
    function getUsers()
    {
        $this->db->select('Basetbl.userId, Basetbl.name');
        $this->db->from('tbl_users as Basetbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = Basetbl.roleId','left');

        $this->db->where('Basetbl.isDeleted ', 0);
        $this->db->where('Role.slug','member' );
       
        $query = $this->db->get();
        return $query->result();
    }
     /**
     * This function is used to get the servers information
     * @return array $result : This is result of the query
     */
    function getServers($userId)
    {
        $this->db->select('Basetbl.id, Basetbl.name');
        $this->db->from('tbl_servers as Basetbl');
        
        $this->db->join('tbl_backups as Backup', 'Basetbl.id = Backup.serverId','left');
        $this->db->join('tbl_users as User', 'Backup.userId = User.userId','left');
        if($userId != "")
        {
            $this->db->where('Backup.userId ', $userId);
        }
        $this->db->where('Basetbl.isDeleted ', 0);
        $this->db->group_by('Backup.serverId ');
       
        $query = $this->db->get();
        return $query->result();
    }
    /**
     * This function is used to get the todays schedule listing count
    
     * @return number $count : This is row count
     */
    function userBackupstatus($status ,$userId = NULL, $searchInfo = NULL)
    {
        $this->db->select('BaseTbl.id, BaseTbl.date,BaseTbl.userId, BaseTbl.clientId, BaseTbl.backupId,
        BaseTbl.status');
        $this->db->from('tbl_backup_schedule as BaseTbl');
        $this->db->join('tbl_backups as Backup', 'BaseTbl.backupId = Backup.id','left');
        $this->db->join('tbl_servers as Server', 'Backup.serverId = Server.id','left');
     
        $this->db->where('BaseTbl.date BETWEEN "'. date("01-m-Y").'" AND "'.date("d-m-Y").'"');
        if($userId != NULL)
        {
            $this->db->where('BaseTbl.userId', $userId);
        }//$this->db->where('BaseTbl.date', $date);
        if($searchInfo['user'] != NULL)
        {
            $this->db->where('BaseTbl.userId', $searchInfo['user']);
        }
        if($searchInfo['server'] != NULL)
        {
            $this->db->where('Backup.serverId', $searchInfo['server']);
        }
        if($searchInfo['month'] != NULL)
        {
            $m = $searchInfo['month'];
            if($m >= 1 && $m <= 9)
            {
                $m = "0".$m;
            }
            if($m == date("m"))
            {
            $this->db->where('BaseTbl.createdAt BETWEEN "'. date("Y-".$m."-01").'" AND "'.date("Y-".$m."-d").'"') ;
            }
            elseif($m != date("m"))
            {
                $this->db->where('BaseTbl.createdAt BETWEEN "'. date("Y-".$m."-01").'" AND "'.date("Y-".$m."-31").'"') ;
            }
        }
        $this->db->where('BaseTbl.status', $status);
        $query = $this->db->get();
        
        return count($query->result());
    }
 /**
     * This function is used to get the backup info 
    
     * @return number $count : This is row count
     */
    function backupDetails($userId, $search_data = null)
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
        
        $this->db->where('BaseTbl.date BETWEEN "'. date("01-m-Y").'" AND "'.date("d-m-Y").'"');
        if($search_data==null)
        {
            //$this->db->where('BaseTbl.createdAt BETWEEN "'. $fromDate.'" AND "'.$toDate.'"');
        }
        if($search_data['status']!=null)
        {
            $this->db->where('BaseTbl.status', $search_data['status']);
        }
        if($search_data['serverId']!=null)
        {
            $this->db->where('Server.id', $search_data['serverId']);
        }
        if($search_data['userId']!=null)
        {
            $this->db->where('BaseTbl.userId',$search_data['userId']);
        }
        if($userId!=null)
        {
            $this->db->where('BaseTbl.userId',$userId);
        }
       
        $this->db->order_by("BaseTbl.date", "asc");
        $this->db->order_by("Client.name", "asc");
        $this->db->order_by("User.name", "asc");
        $query = $this->db->get();
        $result = $query->result();   
      
        return $result;
    }
}

  