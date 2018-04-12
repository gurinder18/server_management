<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Server_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function serverListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.id, BaseTbl.name, BaseTbl.clientId, BaseTbl.server, BaseTbl.hostname,
        BaseTbl.username, BaseTbl.password, BaseTbl.status, BaseTbl.details');
        $this->db->from('tbl_servers as BaseTbl');
        //$this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "( BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.status  LIKE '%".$searchText."%')";
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
    function serverListing($searchText = '', $page, $segment)
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
    function addNewServer2($serverInfo)
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

  