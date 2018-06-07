<?php 

if(!defined('BASEPATH')) exit('No direct script access allowed');

class Client_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function clientListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.id, BaseTbl.name, BaseTbl.phone, BaseTbl.email, BaseTbl.address,
        BaseTbl.city, BaseTbl.state, BaseTbl.country, BaseTbl.zip, BaseTbl.status, BaseTbl.organisation,
         BaseTbl.contacts');
        $this->db->from('tbl_clients as BaseTbl');
       
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        return count($query->result());
    }
    
    /**
     * This function is used to get the client listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function clients($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.id, BaseTbl.name, BaseTbl.phone, BaseTbl.email, BaseTbl.address,
         BaseTbl.city, BaseTbl.state,BaseTbl.country, BaseTbl.zip, BaseTbl.status, BaseTbl.organisation, 
         BaseTbl.contacts');
        $this->db->from('tbl_clients as BaseTbl');
      
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->order_by("BaseTbl.name", "asc");
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();    
       //var_dump( $result);
        return $result;
    } 
      /**
     * This function used to get client information 
     * @param number $id : This is client id
     * @return array $result : This is client information
     */
    function getClientsUsers()
    {
        $this->db->select('BaseTbl.id, BaseTbl.clientId, BaseTbl.userId, User.name As UserName');
        
        $this->db->from('tbl_client_users as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
       
        $this->db->where('BaseTbl.isDeleted', 0);
		//$this->db->where('roleId !=', 1);
        //$this->db->where('clientId', $id);
        $query = $this->db->get();
        
        return $query->result();
    }
    /**
     * This function is used to get the users 
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
     * This function is used to add new client to system
     * @return number $insert_id : This is last inserted id
     */
    function add($clientInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_clients', $clientInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
      /**
     * This function is used to add client's users 
     * @return number $insert_id : This is last inserted id
     */
    function addClientsUser($clientUserInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_client_users', $clientUserInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    /**
     * This function used to get client information by id
     * @param number $id : This is client id
     * @return array $result : This is client information
     */
    function getClientInfo($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.name, BaseTbl.phone, BaseTbl.email, BaseTbl.address,
        BaseTbl.city, BaseTbl.state,BaseTbl.country, BaseTbl.zip, BaseTbl.status, BaseTbl.organisation,
         BaseTbl.contacts');
        
        $this->db->from('tbl_clients as BaseTbl');
        //$this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
       
        //$this->db->where('isDeleted', 0);
		//$this->db->where('roleId !=', 1);
        $this->db->where('id', $id);
        $query = $this->db->get();
        
        return $query->result();
    }
     /**
     * This function used to get client information by id
     * @param number $id : This is client id
     * @return array $result : This is client information
     */
    function getClientsUserInfo($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.clientId, BaseTbl.userId, User.name As UserName');
        
        $this->db->from('tbl_client_users as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
       
        $this->db->where('BaseTbl.isDeleted', 0);
		//$this->db->where('roleId !=', 1);
        $this->db->where('clientId', $id);
        $query = $this->db->get();
        
        return $query->result();
    }
    /**
     * This function used to get client information by clientId and userId
     * @param number $id : This is client id
     * @return array $result : This is client information
     */
    function getClientsUser($id, $userId)
    {
        $this->db->select('BaseTbl.id, BaseTbl.clientId, BaseTbl.userId, User.name As UserName');
        
        $this->db->from('tbl_client_users as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
       
		//$this->db->where('roleId !=', 1);
        $this->db->where('clientId', $id);
        $this->db->where('BaseTbl.userId', $userId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    /**
     * This function is used to update the client information
     * @param array $clientInfo : This is clients updated information
     * @param number $id : This is client id
     */
    function edit($clientInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_clients', $clientInfo);
        
        return TRUE;
    }
     /**
     * This function is used to update the client information
     * @param array $clientInfo : This is clients updated information
     * @param number $id : This is client id
     */
    function editClientsUser($clientUserInfo, $id )
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_client_users', $clientUserInfo);
       
        return TRUE;
    }
    /**
     * This function is used to delete the client's user information
     * @param number $id : This is client id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteClientsUser($userId,$clientId, $clientInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->where('clientId', $clientId);
        $this->db->update('tbl_client_users', $clientInfo);
       
        return $this->db->affected_rows();
    }
    
    /**
     * This function is used to delete the client information
     * @param number $id : This is client id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteClient($id, $clientInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_clients', $clientInfo);
        $result = $this->db->affected_rows();
        if($this->db->affected_rows()>0)
        {
            $this->db->where('clientId', $id);
      
            $this->db->update('tbl_servers', $clientInfo);  
            if($this->db->affected_rows()>0)
            {
                $this->db->where('clientId', $id);
        
                $this->db->update('tbl_backups', $clientInfo);  
            }
        }
        return $result;
    }


}