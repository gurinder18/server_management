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
        $this->db->select('BaseTbl.id, BaseTbl.name,BaseTbl.operatingSystem, BaseTbl.clientId, BaseTbl.server, BaseTbl.hostname,
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
        $this->db->select('BaseTbl.id, BaseTbl.name,BaseTbl.operatingSystem, BaseTbl.clientId, BaseTbl.server, 
        BaseTbl.hostname, BaseTbl.username, BaseTbl.password, BaseTbl.status, BaseTbl.details,
        Client.name As ClientName');
        // $this->db->select('BaseTbl.*'," Client.*");
        $this->db->from('tbl_servers as BaseTbl');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
       
        $this->db->where('BaseTbl.isDeleted', 0);
        //$this->db->order_by('BaseTbl.isDeleted', 0);
        $this->db->limit($page, $segment);
        $this->db->order_by("Client.name", "asc");
        $query = $this->db->get();
        $result = $query->result();    
        
        return $result;
    }
 
    function searchServer($page, $segment,$search_data)
    {
        $this->db->select('BaseTbl.id, BaseTbl.name,BaseTbl.operatingSystem, BaseTbl.clientId, BaseTbl.server, BaseTbl.hostname,
        BaseTbl.username, BaseTbl.password, BaseTbl.status, BaseTbl.details,Client.name As ClientName');
         //$this->db->select('BaseTbl.*');
         $this->db->from('tbl_servers as BaseTbl');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');

        $this->db->where('BaseTbl.isDeleted', 0);

        $likeCriteria = "";
        if( $search_data['server']!=null) {
            $likeCriteria = "( BaseTbl.server  LIKE '%".$search_data['server']."%')";
            $this->db->where($likeCriteria);
                          
        }
        if($search_data['hostname']!=null ) {
            $likeCriteria = "(BaseTbl.hostname  LIKE '%".$search_data['hostname']."%' )";
            $this->db->where($likeCriteria);
        }
        if($search_data['name']!=null){
             $this->db->where('BaseTbl.name', $search_data['name']);
        }
        if($search_data['os']!=null){
            $this->db->where('BaseTbl.operatingSystem', $search_data['os']);
        }
        if($search_data['clientId']!=null){
             $this->db->where('BaseTbl.clientId', $search_data['clientId']);
        }
       
        if($search_data['status']!=null){
             $this->db->where('BaseTbl.status', $search_data['status']);
         }
        $this->db->limit($page, $segment);
        $this->db->order_by("Client.name", "asc");
        $this->db->order_by("BaseTbl.createdDtm", "asc");
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
        $this->db->select('BaseTbl.id, BaseTbl.name,BaseTbl.operatingSystem, BaseTbl.clientId, BaseTbl.server, BaseTbl.hostname,
        BaseTbl.username, BaseTbl.password, BaseTbl.status, BaseTbl.details,Client.name As ClientName');
        // $this->db->select('BaseTbl.*'," Client.*");
        $this->db->from('tbl_servers as BaseTbl');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
        $this->db->join('tbl_backups as Backups', 'Backups.serverId = BaseTbl.id','left');
        
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('Backups.userId', $userId);
        $this->db->group_by('Backups.serverId');
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
        $this->db->select('BaseTbl.id, BaseTbl.name,BaseTbl.operatingSystem, BaseTbl.clientId, BaseTbl.server, BaseTbl.hostname,
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
        if($search_data['os']!=null){
            $this->db->where('BaseTbl.operatingSystem', $search_data['os']);
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
        $this->db->group_by('Backups.serverId');
        $this->db->order_by("Client.name", "asc");
       $this->db->limit($page, $segment);
       
       $query = $this->db->get();
       $result = $query->result();    
      // print_r($this->db);
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
        $this->db->where('status', 1);
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
        $this->db->select('BaseTbl.id, BaseTbl.name,BaseTbl.operatingSystem, BaseTbl.clientId, BaseTbl.server, BaseTbl.hostname,
        BaseTbl.username, BaseTbl.password, BaseTbl.status, BaseTbl.details,Client.name As ClientName');
        $this->db->from('tbl_servers as BaseTbl');
        $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
       
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.id', $id);
        $query = $this->db->get();
        
        return $query->result();
    }
     /**
     * This function is used to get the operating systems 
     * @return array $result : This is result of the query
     */
    function getOs()
    {
        $this->db->select('id,operatingSystem');
        $this->db->from('tbl_servers');
        $this->db->where('isDeleted !=', 1);
        $this->db->where('operatingSystem !=', "Linux");
        $this->db->where('operatingSystem !=', "Windows");
        $this->db->group_by('operatingSystem','asc');
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
        $result = $this->db->affected_rows();
        if($this->db->affected_rows()>0)
        {
            $this->db->where('serverId', $id);
      
            $this->db->update('tbl_backups', $serverInfo);  
        }
        return $result;
    }
     /**
     * This function is used to check whether email id is already exist or not
     * @return {mixed} $result : This is searched result
     */
    function checkClientExists($clientName)
    {
        $this->db->select("id,name");
        $this->db->from("tbl_clients");
        $this->db->where("name", $clientName);   
        $this->db->where("isDeleted", 0);
      
        $query = $this->db->get();
        
        return $query->row_array();
    }
     /**
     * This function is used to get  IP List
     */
    function ipListing()
    {
        $this->db->select('BaseTbl.id, BaseTbl.ip');
       
        $this->db->from('tbl_ip as BaseTbl');
       
        $this->db->where('BaseTbl.isDeleted', 0);
       
        $this->db->order_by("BaseTbl.ip", "asc");
        $query = $this->db->get();
        $result = $query->result();    
        
        return $result;
    }
    /**
     * This function is used to check whether ip already exist or not
     * @return {mixed} $result : This is searched result
     */
    function checkIpExists($ip)
    {
        $this->db->select("id,ip");
        $this->db->from("tbl_ip");
        $this->db->where("ip", $ip);   
        $this->db->where("isDeleted", 0);
      
        $query = $this->db->get();
        
        return $query->row_array();
    }
    /**
     * This function is used to add new ip to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewIP($ipInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_ip', $ipInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
      /**
     * This function is used to update the ip information
     * @param array $serverInfo : This is ips updated information
     * @param number $id : This is ip- id
     */
    function editIP($ipInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_ip', $ipInfo);
        
        return TRUE;
    }
    /**
     * This function is used to delete the ip information
     * @param number $id : This is ip- id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteIP($id, $ipInfo)
    {
        $this->db->where('id', $id);
      
        $this->db->update('tbl_ip', $ipInfo);
        $result = $this->db->affected_rows();
        
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
     * This function is used to get the user info
     */
    function userListing()
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile,BaseTbl.status, 
        Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
       
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.status', 1);
        $this->db->order_by('BaseTbl.roleId', 'asc');
        $this->db->order_by('BaseTbl.name', 'asc');
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
     /**
     * This function is used to add new ip blacklist to system
     * @return number $insert_id : This is last inserted id
     */
    function addIPBlacklist($ipInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_ip_blacklist', $ipInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    /**
     * This function is used to get the blacklisted-ip list 
     * @return array $result : This is result
     */
    function getAllIP()
    {
        $this->db->select('BaseTbl.id, BaseTbl.ip');
       
        $this->db->from('tbl_ip_blacklist as BaseTbl');
       
        $this->db->group_by("BaseTbl.ip");
        $query = $this->db->get();
        $result = $query->result();    
        
        return $result;
    }
     /**
     * This function is used to get the ip-blacklist info
     * @return array $result : This is result
     */
    function getIPBlacklist()
    {
        $this->db->select('BaseTbl.id, BaseTbl.ip,BaseTbl.serverId, BaseTbl.host, BaseTbl.isListed');
       
        $this->db->from('tbl_ip_blacklist as BaseTbl');
       // $this->db->where('BaseTbl.createdDtm', date("Y-m-d"));
        $this->db->where('BaseTbl.createdDtm BETWEEN "'. date("Y-m-d").'" AND "'.date("Y-m-d", strtotime("+1 day")).'"');
        $query = $this->db->get();

        $result = $query->result();    
        
        return $result;
    }
}

  