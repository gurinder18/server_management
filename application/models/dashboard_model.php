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


}

  