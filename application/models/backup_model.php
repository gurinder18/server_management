<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Backup_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function backupListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.id,BaseTbl.userId, BaseTbl.clientId, BaseTbl.serverId,
        BaseTbl.scheduleType, BaseTbl.scheduleTimings, BaseTbl.information');
        $this->db->from('tbl_backups as BaseTbl');
        //$this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "( BaseTbl.userId  LIKE '%".$searchText."%'
                            OR  BaseTbl.clientId  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
       // $this->db->where('BaseTbl.isDeleted', 0);
       // $this->db->where('BaseTbl.roleId !=', 1);
        $query = $this->db->get();
        
        return count($query->result());
    }
    

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function backups($searchText = '', $page, $segment)
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
       
        $this->db->limit($page, $segment);
        
        $query = $this->db->get();
        $result = $query->result();    
        //print_r($result); die;    
        return $result;
    }

    function searchBackups($searchText = '', $page, $segment,$search_data)
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
           
        return $result;
    }


    function membersBackups($searchText = '', $page, $segment,$userId)
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
      
       $this->db->limit($page, $segment);
       
       $query = $this->db->get();
       $result = $query->result();    
       //print_r($result); die;    
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
        $query = $this->db->get();
    
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
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function checkUserExists($username, $id = 0)
    {
        $this->db->select("username");
        $this->db->from("tbl_servers");
        $this->db->where("username", $username);   
        $this->db->where("isDeleted", 0);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    }
    
}

  