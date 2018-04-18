<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Server_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function serverListingCount()
    {
        $this->db->select('BaseTbl.id, BaseTbl.name, BaseTbl.clientId, BaseTbl.server, BaseTbl.hostname,
        BaseTbl.username, BaseTbl.password, BaseTbl.status, BaseTbl.details');
        $this->db->from('tbl_servers as BaseTbl');
        
        $this->db->where('BaseTbl.isDeleted', 0);
       // $this->db->where('BaseTbl.roleId !=', 1);
        $query = $this->db->get();
        
        return count($query->result());
    }
    

    /**
     * This function is used to get the user listing count for admin
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function serverListing($page=null, $segment=null)
    {
        $this->db->select('BaseTbl.id, BaseTbl.name, BaseTbl.clientId, BaseTbl.server, BaseTbl.hostname,
         BaseTbl.username, BaseTbl.password, BaseTbl.status, BaseTbl.details,Client.name As ClientName');
        // $this->db->select('BaseTbl.*'," Client.*");
        $this->db->from('tbl_servers as BaseTbl');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
       
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->limit($page, $segment);
        
        $query = $this->db->get();
        $result = $query->result();    
        //print_r($result); die;    
        return $result;
    }
 
    function searchServer($page, $segment,$search_data)
    {
        $this->db->select('BaseTbl.id, BaseTbl.name, BaseTbl.clientId, BaseTbl.server, BaseTbl.hostname,
        BaseTbl.username, BaseTbl.password, BaseTbl.status, BaseTbl.details,Client.name As ClientName');
         //$this->db->select('BaseTbl.*');
         $this->db->from('tbl_servers as BaseTbl');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');

        $this->db->where('BaseTbl.isDeleted', 0);
        
        if($search_data['name']!=null){
             $this->db->where('BaseTbl.name', $search_data['name']);
        }
        if($search_data['clientId']!=null){
             $this->db->where('BaseTbl.clientId', $search_data['clientId']);
        }
        if($search_data['server']!=null){
            $this->db->where('BaseTbl.server', $search_data['server']);
        }
        if($search_data['hostname']!=null){
             $this->db->where('BaseTbl.hostname', $search_data['hostname']);
        }
        if($search_data['status']!=null){
             $this->db->where('BaseTbl.status', $search_data['status']);
         }
        $this->db->limit($page, $segment);
        
        $query = $this->db->get();
        $result = $query->result();    
           
        return $result;
    }

    /**
     * This function is used to get the server listing count for member access
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function membersServersCount($userId)
    {
        $this->db->select('BaseTbl.id, BaseTbl.name, BaseTbl.clientId, BaseTbl.server, BaseTbl.hostname,
        BaseTbl.username, BaseTbl.password, BaseTbl.status, BaseTbl.details,Client.name As ClientName');
        // $this->db->select('BaseTbl.*'," Client.*");
        $this->db->from('tbl_servers as BaseTbl');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
        $this->db->join('tbl_backups as Backups', 'Backups.serverId = BaseTbl.id','left');
        
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('Backups.userId', $userId);
        
       $query = $this->db->get();
       
       //print_r($query);
       return count($query->result());
    }

    /**
     * This function is used to get the user listing count for member
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function membersServers($page, $segment,$userId,$search_data=NULL)
    {
        $this->db->select('BaseTbl.id, BaseTbl.name, BaseTbl.clientId, BaseTbl.server, BaseTbl.hostname,
        BaseTbl.username, BaseTbl.password, BaseTbl.status, BaseTbl.details,Client.name As ClientName');
        // $this->db->select('BaseTbl.*'," Client.*");
        $this->db->from('tbl_servers as BaseTbl');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
        $this->db->join('tbl_backups as Backups', 'Backups.serverId = BaseTbl.id','left');
        
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('Backups.userId', $userId);
        
        if($search_data['name']!=null){
            $this->db->where('BaseTbl.name', $search_data['name']);
       }
       if($search_data['clientId']!=null){
            $this->db->where('BaseTbl.clientId', $search_data['clientId']);
       }
       if($search_data['server']!=null){
           $this->db->where('BaseTbl.server', $search_data['server']);
       }
       if($search_data['hostname']!=null){
            $this->db->where('BaseTbl.hostname', $search_data['hostname']);
       }
       if($search_data['status']!=null){
            $this->db->where('BaseTbl.status', $search_data['status']);
        }

       $this->db->limit($page, $segment);
       
       $query = $this->db->get();
       $result = $query->result();    
       //print_r($query);
       return $result;
    }
    

    /**
     * This function is used to get the backups information
     * @return array $result : This is result of the query
     */
    function getUsersBackups($userId)
    {
        $this->db->select('id,serverId');
        $this->db->from('tbl_backups');
        $this->db->where('isDeleted !=', 1);
        $this->db->where('userId', $userId);
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
     * This function is used to get the clients information by id
     * @return array $result : This is result of the query
     */
    function getClientById($id)
    {
        $this->db->select('name');
        $this->db->from('tbl_clients');
        $this->db->where('id ==', $id);
        $this->db->where('isDeleted !=', 1);
        $query = $this->db->get();
    
        return $query->result();
    }

    /**
     * This function is used to add new server to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewServer($serverInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_servers', $serverInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get server information by id
     * @param number $id : This is server id
     * @return array $result : This is server information
     */
    function getServerInfo($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.name, BaseTbl.clientId, BaseTbl.server, BaseTbl.hostname,
        BaseTbl.username, BaseTbl.password, BaseTbl.status, BaseTbl.details,Client.name As ClientName');
        $this->db->from('tbl_servers as BaseTbl');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
       
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.id', $id);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to update the server information
     * @param array $serverInfo : This is servers updated information
     * @param number $id : This is server id
     */
    function editServer($serverInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_servers', $serverInfo);
        
        return TRUE;
    }
    
     
    
    /**
     * This function is used to delete the server information
     * @param number $id : This is server id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteServer($id, $serverInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_servers', $serverInfo);
        
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

  