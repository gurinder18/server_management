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
        $this->db->select('BaseTbl.serverId, BaseTbl.name, BaseTbl.status');
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
        $this->db->select('BaseTbl.serverId, BaseTbl.name, BaseTbl.status');
        $this->db->from('tbl_servers as BaseTbl');
        //$this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "( BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.status  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
      
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
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
     * @param number $serverId : This is server id
     * @return array $result : This is server information
     */
    function getServerInfo($serverId)
    {
        $this->db->select('serverId, name, status');
        $this->db->from('tbl_servers');
        $this->db->where('isDeleted', 0);
        $this->db->where('serverId', $serverId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to update the server information
     * @param array $serverInfo : This is servers updated information
     * @param number $serverId : This is server id
     */
    function editServer($serverInfo, $serverId)
    {
        $this->db->where('serverId', $serverId);
        $this->db->update('tbl_servers', $serverInfo);
        
        return TRUE;
    }
    
    
    
    /**
     * This function is used to delete the server information
     * @param number $serverId : This is server id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteServer($serverId, $serverInfo)
    {
        $this->db->where('serverId', $serverId);
        $this->db->update('tbl_servers', $serverInfo);
        
        return $this->db->affected_rows();
    }
   
}

  