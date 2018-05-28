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
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');

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
        $this->db->where('Backup.userId ', $userId);
        $this->db->where('Basetbl.isDeleted ', 0);
        $this->db->group_by('Backup.serverId ');
       
        $query = $this->db->get();
        return $query->result();
    }
    /**
     * This function is used to get the todays schedule listing count
    
     * @return number $count : This is row count
     */
    function userBackupstatus($status , $searchInfo = NULL)
    {
        $this->db->select('BaseTbl.id, BaseTbl.date,BaseTbl.userId, BaseTbl.clientId, BaseTbl.backupId,
        BaseTbl.status');
        $this->db->from('tbl_backup_schedule as BaseTbl');
        $this->db->join('tbl_backups as Backup', 'Basetbl.backupId = Backup.id','left');
        $this->db->join('tbl_servers as Server', 'Backup.serverId = Server.id','left');
     
        $this->db->where('BaseTbl.date BETWEEN "'. date("01-m-Y").'" AND "'.date("d-m-Y").'"');
        //$this->db->where('BaseTbl.date', $date);
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
            //$this->db->where('BaseTbl.date BETWEEN "'. date("01-".$m."-Y").'" AND "'.date("d-".$m."-Y").'"') ;
        }
        $this->db->where('BaseTbl.status', $status);
        $query = $this->db->get();
        return count($query->result());
    }

}

  