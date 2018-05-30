<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'/third_party/PHPExcel/Classes/PHPExcel.php';
require_once APPPATH.'/third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Server (ServerController)
 * Server Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Server extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('server_model');
        $this->isLoggedIn();   
        $this->load->library('email');
    }
  
    /**
     * This function used to load the first screen of the server
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Orion eSolutions : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
        
    }
    
    /**
     * This function is used to load the server list
     */
   
    function serverListing()
    {
        if($this->isMember() == TRUE)
        {
            $this->load->model('server_model');
        
            $this->load->library('pagination');
            
            $count = $this->server_model->membersServersCount($this->vendorId);
           
            $returns = $this->paginationCompress ( "servers/", $count, 5 );

            if(isset($_GET['search_server'])=='Search' || isset($_GET['name']) || isset($_GET['os']) || isset($_GET['client']) || isset($_GET['server']) || isset($_GET['hostname']) || isset($_GET['status']))
            {
                $search_data['name'] = $this->input->get('name');
                $search_data['os'] = $this->input->get('os');
                $search_data['clientId'] = $this->input->get('client');
                $search_data['server'] = $this->input->get('server');
                $search_data['hostname'] = $this->input->get('hostname');
                $search_data['status'] = $this->input->get('status');

                $data['servers'] = $this->server_model->membersServers($returns["page"], $returns["segment"],$this->vendorId);
                $data['clients'] = $this->server_model->getClients();
                $data['os'] = $this->server_model->getOs();
                $data['serverRecords'] = $this->server_model->membersServers($returns["page"], $returns["segment"],$this->vendorId,$search_data);
                $this->global['pageTitle'] = 'Orion eSolutions : Server Listing';
                
                $this->loadViews("servers", $this->global, $data, NULL);
               
            }
            elseif(isset($_GET['search_server'])!='Search')
            {
                $data['clients'] = $this->server_model->getClients();
                $data['os'] = $this->server_model->getOs();
                $data['serverRecords'] = $this->server_model->membersServers($returns["page"], $returns["segment"],$this->vendorId);
                $data['servers'] = $this->server_model->membersServers( $returns["page"], $returns["segment"],$this->vendorId);
               
                $this->global['pageTitle'] = 'Orion eSolutions : Server Listing';
                
                $this->loadViews("servers", $this->global, $data, NULL);
            }
           
        }
        elseif($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('server_model');
        
            $this->load->library('pagination');
            
            $count = $this->server_model->serverListingCount();
            $returns = $this->paginationCompress ( "servers/", $count, 5 );
            
            if(isset($_GET['search_server'])=='Search' || isset($_GET['name'])  || isset($_GET['os'])  || isset($_GET['client']) || isset($_GET['server']) || isset($_GET['hostname']) || isset($_GET['status']))
            {
                $search_data['name'] = $this->input->get('name');
                $search_data['os'] = $this->input->get('os');
                $search_data['clientId'] = $this->input->get('client');
                $search_data['server'] = $this->input->get('server');
                $search_data['hostname'] = $this->input->get('hostname');
                $search_data['status'] = $this->input->get('status');

                $data['servers'] = $this->server_model->serverListing( null, $returns["segment"]);
                $data['clients'] = $this->server_model->getClients();
                $data['os'] = $this->server_model->getOs();
                $data['serverRecords'] = $this->server_model->searchServer($returns["page"], $returns["segment"],$search_data);
                $this->global['pageTitle'] = 'Orion eSolutions : Server Listing';
                
                $this->loadViews("servers", $this->global, $data, NULL);
            }
            elseif(isset($_GET['search_server'])!='Search')
            {
                $data['clients'] = $this->server_model->getClients();
                $data['os'] = $this->server_model->getOs();
                $data['servers'] = $this->server_model->serverListing( null, $returns["segment"]);
                $data['serverRecords'] = $this->server_model->serverListing( $returns["page"], $returns["segment"]);
                $this->global['pageTitle'] = 'Orion eSolutions : Server Listing';
                
                $this->loadViews("servers", $this->global, $data, NULL);
            }
        }
    }  
    /**
     * This function is used to load the all servers list
     */
   
    function allServerListing()
    {
        if($this->isMember() == TRUE)
        {
            $this->load->model('server_model');
        
            $this->load->library('pagination');
            
            $count = $this->server_model->membersServersCount($this->vendorId);
           
            $returns = $this->paginationCompress ( "all-servers/", $count, 0 );

            
            if(isset($_GET['search_server'])=='Search' || isset($_GET['name']) || isset($_GET['os']) || isset($_GET['client']) || isset($_GET['server']) || isset($_GET['hostname']) || isset($_GET['status']))
            {
                $search_data['name'] = $this->input->get('name');
                $search_data['os'] = $this->input->get('os');
                $search_data['clientId'] = $this->input->get('client');
                $search_data['server'] = $this->input->get('server');
                $search_data['hostname'] = $this->input->get('hostname');
                $search_data['status'] = $this->input->get('status');

                $data['servers'] = $this->server_model->membersServers($returns["page"], $returns["segment"],$this->vendorId);
                $data['clients'] = $this->server_model->getClients();
                $data['os'] = $this->server_model->getOs();
                $data['serverRecords'] = $this->server_model->membersServers($returns["page"], $returns["segment"],$this->vendorId,$search_data);
                $this->global['pageTitle'] = 'Orion eSolutions : Server Listing';
                
                $this->loadViews("servers", $this->global, $data, NULL);
               
            }
            elseif(isset($_GET['search_server'])!='Search')
            {
                $data['clients'] = $this->server_model->getClients();
                $data['os'] = $this->server_model->getOs();
                $data['serverRecords'] = $this->server_model->membersServers($returns["page"], $returns["segment"],$this->vendorId);
                $data['servers'] = $this->server_model->membersServers( $returns["page"], $returns["segment"],$this->vendorId);
               
                $this->global['pageTitle'] = 'Orion eSolutions : Server Listing';
                
                $this->loadViews("servers", $this->global, $data, NULL);
            }
           
        }
        elseif($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('server_model');
        
            $this->load->library('pagination');
            
            $count = $this->server_model->serverListingCount();
            $returns = $this->paginationCompress ( "servers/", $count, 0 );
            
           
            if(isset($_GET['search_server'])=='Search' || isset($_GET['name']) || isset($_GET['os']) || isset($_GET['client']) || isset($_GET['server']) || isset($_GET['hostname']) || isset($_GET['status']))
            {
                $search_data['name'] = $this->input->get('name');
                $search_data['os'] = $this->input->get('os');
                $search_data['clientId'] = $this->input->get('client');
                $search_data['server'] = $this->input->get('server');
                $search_data['hostname'] = $this->input->get('hostname');
                $search_data['status'] = $this->input->get('status');

                $data['servers'] = $this->server_model->serverListing( null, $returns["segment"]);
                $data['clients'] = $this->server_model->getClients();
                $data['os'] = $this->server_model->getOs();
                $data['serverRecords'] = $this->server_model->searchServer($returns["page"], $returns["segment"],$search_data);
                $this->global['pageTitle'] = 'Orion eSolutions : Server Listing';
                
                $this->loadViews("servers", $this->global, $data, NULL);
            }
            elseif(isset($_GET['search_server'])!='Search')
            {
                $data['clients'] = $this->server_model->getClients();
                $data['os'] = $this->server_model->getOs();
                $data['servers'] = $this->server_model->serverListing( null, $returns["segment"]);
                $data['serverRecords'] = $this->server_model->serverListing( $returns["page"], $returns["segment"]);
                $this->global['pageTitle'] = 'Orion eSolutions : Server Listing';
                
                $this->loadViews("servers", $this->global, $data, NULL);
            }
        }
    }  
    /**
     * This function is used to load the add new server
     */
    function addServer()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        elseif(isset($_POST['add_server'])!='Submit')
        {
            $this->load->model('server_model');
            $data['clients'] = $this->server_model->getClients();
            $data['os'] = $this->server_model->getOs();
           
            $this->global['pageTitle'] = 'Orion eSolutions : Add New Server';
           
            $this->loadViews("addNewServer", $this->global, $data, NULL);
        }
        elseif(isset($_POST['add_server'])=='Submit'){
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('name','Name','trim|required|max_length[100]|xss_clean');
            $this->form_validation->set_rules('client','Client','trim|required|numeric');
            $this->form_validation->set_rules('server','Server','trim|required|max_length[100]|xss_clean');
            $this->form_validation->set_rules('hostname','Hostname','trim|required|max_length[100]|xss_clean');
            $this->form_validation->set_rules('username','Username','max_length[20]');
            $this->form_validation->set_rules('password','Password','max_length[20]|min_length[4]');
            $this->form_validation->set_rules('status','Status','trim|required|numeric');
            $this->form_validation->set_rules('details','Details','trim|xss_clean');
           
            if($this->form_validation->run() == FALSE)
            {
                unset($_POST['add_server']);
                $this->addServer();
            }
            else
            {
                $name = ucwords(strtolower($this->input->post('name')));
                $client = $this->input->post('client');
                $server = $this->input->post('server');
                $hostname = $this->input->post('hostname');
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $status = $this->input->post('status');
                $details = $this->input->post('details');
                $os = $this->input->post('os');
                $other = $this->input->post('other');
                if($os == "Other")
                {
                    $operatingSystem =  $other;
                }
                else
                {
                    $operatingSystem =  $os;
                }
                $serverInfo = array('name'=>ucwords($name),'operatingSystem'=>$operatingSystem,'clientId'=>$client,
                'server'=>$server,'hostname'=>$hostname,'username'=>$username,'password'=>$password,
                'status'=>$status,'details'=>$details, 'createdBy'=>$this->vendorId,
                 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('server_model');
                $result = $this->server_model->addNewServer($serverInfo);
                
                if($result > 0)
                {
                    //$ipInfo = array('ip'=>$server,'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                   // $res = $this->server_model->addNewIP($ipInfo);
                    $this->session->set_flashdata('success', 'New server created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Server creation failed');
                }
                
                redirect('servers');
            }
        }else{}
    }

    /**
     * This function is server load server edit information
     * @param number $id : Optional : This is server id
     */
    function editServer($id = NULL)
    {
        if($this->isAdmin() == FALSE )
        {
            $this->loadThis();
        }
        elseif(isset($_POST['edit_server'])!='Submit')
        {
            if($id == null)
            {
                redirect('servers');
            }
            
            $data['serverInfo'] = $this->server_model->getServerInfo($id);
            $data['clients'] = $this->server_model->getClients();
            $data['os'] = $this->server_model->getOs();
            
            $this->global['pageTitle'] = 'Orion eSolutions : Edit Server';
           
            $this->loadViews("editOldServer", $this->global, $data, NULL);
        }
        elseif(isset($_POST['edit_server'])=='Submit'){
            $this->load->library('form_validation');
            
            $id = $this->input->post('id');
            
            $this->form_validation->set_rules('name','Name','trim|required|max_length[100]|xss_clean');
            $this->form_validation->set_rules('client','Client','trim|required|numeric');
            $this->form_validation->set_rules('server','Server','trim|required|max_length[100]|xss_clean');
            $this->form_validation->set_rules('hostname','Hostname','trim|required|max_length[100]|xss_clean');
            $this->form_validation->set_rules('username','Username','max_length[20]');
            $this->form_validation->set_rules('password','Password','max_length[20]|min_length[4]');
            $this->form_validation->set_rules('status','Status','trim|required|numeric');
            $this->form_validation->set_rules('details','Details','trim|xss_clean');
            
            if($this->form_validation->run() == FALSE)
            {
                unset($_POST['edit_server']);
                $this->editServer($id);
            }
            else
            {
                $name = ucwords(strtolower($this->input->post('name')));
                $client = $this->input->post('client');
                $server = $this->input->post('server');
                $hostname = $this->input->post('hostname');
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $status = $this->input->post('status');
                $details = $this->input->post('details');
                $os = $this->input->post('os');
                $other = $this->input->post('other');
                if($os == "Other")
                {
                    $operatingSystem =  $other;
                }
                else
                {
                    $operatingSystem =  $os;
                }
                $serverInfo = array();
                
                $serverInfo = array('name'=>ucwords($name),'operatingSystem'=>$operatingSystem,
                'clientId'=>$client,'server'=>$server,'hostname'=>$hostname,'username'=>$username,
                'password'=>$password,'status'=>$status,'details'=>$details, 'updatedBy'=>$this->vendorId, 
                'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->server_model->editServer($serverInfo, $id);
                
                if($result == true)
                {

                    $this->session->set_flashdata('success', 'Server updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Server updation failed');
                }
                
                redirect('edit-server/'.$id);
            }
        }else{}
    }
    
    /**
     * This function is used to delete the server using id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteServer()
    {
        if($this->isAdmin() == FALSE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        elseif(isset($_POST['delete_server'])!='Delete')
        {

            $id = $this->input->post('id');
            $serverInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->server_model->deleteServer($id, $serverInfo);
            
            if ($result > 0)
            { 
                echo(json_encode(array('status'=>TRUE))); 
            }
            else 
            {
                echo(json_encode(array('status'=>FALSE))); 
            }
        }
        elseif(isset($_POST['delete_server'])=='Delete')
        {
            $del = $this->input->post('delete_servers');
            if($del != null)
            {
                foreach($del as $id):
                    $serverInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                    
                    $result = $this->server_model->deleteServer($id, $serverInfo);
                endforeach;
                if ($result > 0)
                {  
                    redirect('servers');
                    unset($_POST['delete_server']);
                }
            }
            else
            {
                redirect('servers');
            }
        }
    }
    
    function addServers()
    {  
        //Path of files were you want to upload on localhost (C:/xampp/htdocs/ProjectName/uploads/excel/)	 
        $configUpload['upload_path'] = APPPATH.'../assets/excel/';
        $configUpload['allowed_types'] = 'xls|xlsx|csv';
        $configUpload['max_size'] = '1000';
        
        $this->load->library('upload', $configUpload);
        $this->upload->do_upload('serverfile');	
        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
        $file_name = $upload_data['file_name']; //uploded file name
        $extension=$upload_data['file_ext'];    // uploded file extension
        $file = explode(".",$file_name); 
       
        if($file[1] != 'xlsx' && $file[1] != 'xls' && $file[1] != 'csv')
        {
           echo "<script>alert('Only file type xls, xlsx, csv can be uploaded');</script>";
           redirect('add-server?invalid=Only file type xls, xlsx, csv can be uploaded');
        }
        else
        {
        //$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
        
        $objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
        //Set to read only
        $objReader->setReadDataOnly(true); 		  
        //Load excel file
        $path = 'C:/wamp64/www/server-m/assets/excel/';
        $objPHPExcel = $objReader->load( APPPATH.'../assets/excel/'.$file_name);		 
        $totalrows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0); 

        $name = $objWorksheet->getCellByColumnAndRow(0,1)->getValue();
        $client = $objWorksheet->getCellByColumnAndRow(1,1)->getValue(); //Excel Column 1
        $serverIP = $objWorksheet->getCellByColumnAndRow(2,1)->getValue(); //Excel Column 2
        $hostname = $objWorksheet->getCellByColumnAndRow(3,1)->getValue(); //Excel Column 3
        $username = $objWorksheet->getCellByColumnAndRow(4,1)->getValue(); //Excel Column 4
        $password = $objWorksheet->getCellByColumnAndRow(5,1)->getValue(); //Excel Column 5
        $status = $objWorksheet->getCellByColumnAndRow(6,1)->getValue(); //Excel Column 6
        $details = $objWorksheet->getCellByColumnAndRow(7,1)->getValue(); //Excel Column 7 
        if(!isset($name,$client,$serverIP,$hostname,$username,$password,$status,$details))
        {
            redirect("add-server?message=Please upload file with valid data");
        }
        else
        {
        //loop from first data untill last data
        for($i = 2 ; $i <= $totalrows ; $i++)
        {
            $name = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();			
            $client = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue(); //Excel Column 1
            $serverIP = $objWorksheet->getCellByColumnAndRow(2,$i)->getValue(); //Excel Column 2
            $hostname = $objWorksheet->getCellByColumnAndRow(3,$i)->getValue(); //Excel Column 3
            $username = $objWorksheet->getCellByColumnAndRow(4,$i)->getValue(); //Excel Column 4
            $password = $objWorksheet->getCellByColumnAndRow(5,$i)->getValue(); //Excel Column 5
            $status = $objWorksheet->getCellByColumnAndRow(6,$i)->getValue(); //Excel Column 6
            $details = $objWorksheet->getCellByColumnAndRow(7,$i)->getValue(); //Excel Column 7

            if(isset($name,$client,$serverIP,$hostname,$status,$details) && (is_string($client) == TRUE) && (is_numeric($status) == TRUE))
            {
                $clientId = $this->checkClientExists($client);
                if(!empty($clientId))
                {
                    $serverInfo = array('name'=>ucwords($name),'clientId'=>$clientId,'server'=>$serverIP,'hostname'=>$hostname,
                    'username'=>$username,'password'=>$password,'status'=>$status,'details'=>$details,
                    'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                                    
                    $this->load->model('server_model');
                    $result = $this->server_model->addNewServer($serverInfo);
                    if($result > 0)
                    {
                        $this->session->set_flashdata('success', 'New server created successfully');
                    }
                    else
                    {
                        $this->session->set_flashdata('error', 'Server creation failed');
                    }
                }
                else
                {
                    $clientInfo = array('name'=>$client,'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                            
                    $this->load->model('client_model');
                    $result = $this->client_model->add($clientInfo);

                    $clientId = $this->checkClientExists($client);
                    if(!empty($clientId))
                    {
                        $serverInfo = array('name'=>ucwords($name),'clientId'=>$clientId,'server'=>$serverIP,'hostname'=>$hostname,
                        'username'=>$username,'password'=>$password,'status'=>$status,'details'=>$details,
                        'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                                    
                        $this->load->model('server_model');
                        $result = $this->server_model->addNewServer($serverInfo);
                        if($result > 0)
                        {
                            $this->session->set_flashdata('success', 'New server created successfully');
                        }
                        else
                        {
                            $this->session->set_flashdata('error', 'Server creation failed');
                        }
                    }
                }
            }
            else
            {
                redirect("add-server?message=Please upload file with valid data");
                break;
            }
        } 
        unlink( APPPATH.'../assets/excel/'.$file_name); //File Deleted After uploading in database .			 
        redirect('servers');
        }
    }
}
   
    function checkClientExists($clientName)
    {
        $result = $this->server_model->checkClientExists($clientName);
        if(!empty($result))
        { 
            return $result['id'];
        }
    }
     /**
     * This function is used to load the Check IP Blacklist 
     */
    function checkBlacklist()
    {
        $this->load->model('server_model');
        $data['IP_List'] = $this->server_model->ipListing();

        $data['server_ip'] = $this->server_model->serverListing( null, null);
        $list = array();
        $ip_list = array_merge($data['IP_List'], $data['server_ip']);
        $statusList = array();
        foreach( $ip_list as $servers)
        {
            if(isset($servers->server))
            {
                $serverId = $servers->id;
                $ip = $servers->server;
                $serverName = $servers->name;
            }
            else
            {
                $serverId = "";
                $ip =$servers->ip;
                $serverName = "";
            }
           $data["listing"][] = $this->dnsbllookup($ip, TRUE);
           foreach( $data["listing"] as $datalist)
            {
               foreach( $datalist as $data)
               {
                   $ip = $data['ip'];
                    $host = $data['host'];
                    if($data['listed'] == "Listed")
                    {
                        $isListed = 1;
                    }
                    elseif($data['listed'] == "Not Listed")
                    {
                        $isListed = 0;
                    }
                    if($serverId != "")
                    {
                        $ipInfo = array('ip'=>$ip,'serverId'=>$serverId,'host'=>$host,'isListed'=>$isListed);
                    }
                    else
                    {
                        $ipInfo = array('ip'=>$ip,'host'=>$host,'isListed'=>$isListed);
                    }
                    $result = $this->server_model->addIPBlacklist($ipInfo);
                   
                }
            } 
        }
        foreach( $ip_list as $ipList)
        {
            if(isset($ipList->server))
            {
                $serverId = $ipList->id;
                $ip = $ipList->server;
                $serverName = $ipList->name;
            }
            else
            {
                $serverId = "";
                $ip =$ipList->ip;
                $serverName = "";
            }
       $data['blacklist'] = $this->server_model->getIPBlacklist();
       $id = "";
       $isBlacklisted = "Not Checked";
       if(!empty($data['blacklist']))
       {
           foreach($data["blacklist"] as  $list => $li)
           { 
               if($li->ip == $ip)  
               {   
                   if($li->isListed == "1")  
                   {
                       $id = $li->id;
                       $isBlacklisted = "Listed";
                       break;
                   }
                   else
                   {
                       $id = $li->id;
                       $isBlacklisted = "Not Listed"; 
                   }
               }
           }
        }
        $statusList[] = array("id"=>$id,
                              "ip"=> $ip,
                              "isListed"=> $isBlacklisted
                            );
    }
       $data['status'] = $statusList;
       //var_dump($data['status']);
        $this->blacklistMail($data['status']);
    }
    /**
     * This function is used to load the Check IP Blacklist page
     */
    function ipBlacklisting()
    {
        if($this->isAdmin() == FALSE && $this->isMember() == FALSE)
        {
            $this->loadThis();
        }
        $this->load->model('server_model');
        $data['IP_List'] = $this->server_model->ipListing();

        $data['server_ip'] = $this->server_model->serverListing( null, null);
        $list = array();
        $ip_list = array_merge($data['IP_List'], $data['server_ip']);
        foreach( $ip_list as $servers)
        {
            if(isset($servers->server))
            {
                $serverId = $servers->id;
                $ip = $servers->server;
                $serverName = $servers->name;
            }
            else
            {
                $serverId = "";
                $ip =$servers->ip;
                $serverName = "";
            }
            $data['blacklist'] = $this->server_model->getIPBlacklist();
            $isBlacklisted = "Not checked";
            $id = $servers->id;
            if(!empty($data['blacklist']))
            {
                foreach($data["blacklist"] as  $list => $li)
                { 
                    if($li->ip == $ip)  
                    {   
                        if($li->isListed == "1")  
                        {
                            $id = $li->id;
                            $isBlacklisted = "Listed";
                            break;
                        }
                        else
                        {
                            $id = $li->id;
                            $isBlacklisted = "Not Listed"; 
                        
                        }
                    }
                }
            }
            if($serverName != "")
            {
                $statusList[] = array("id"=>$id,
                                "serverName"=>$servers->name,
                                "ip"=> $ip,
                                "isListed"=> $isBlacklisted
                );
            }
            else
            {
                $statusList[] = array("id"=>$id,
                                "serverName"=>NULL,
                                "ip"=> $ip,
                                "isListed"=> $isBlacklisted
                );
            }
        }
        $data['status'] = $statusList;
        $this->global['pageTitle'] = 'Orion eSolutions : Check IP Blacklist';
           
         $this->loadViews("checkBlacklist", $this->global, $data, NULL);
        
    }
    function dnsbllookup($ip, $isList)
    {
        // Add your preferred list of DNSBL's
        $dnsbl_lookup = [
            "dnsbl-1.uceprotect.net",
            "dnsbl-2.uceprotect.net",
            "dnsbl-3.uceprotect.net",
            "dnsbl.dronebl.org",
            "dnsbl.sorbs.net",
            "zen.spamhaus.org",
            "bl.spamcop.net",
            "list.dsbl.org",
            "sbl.spamhaus.org",
            "xbl.spamhaus.org",
            "b.barracudacentral.org"
        ];
        $listed = "";
        $listing = array();
        if ($ip) {
            $reverse_ip = implode(".", array_reverse(explode(".", $ip)));
            foreach ($dnsbl_lookup as $host) {
                if (checkdnsrr($reverse_ip . "." . $host . ".", "A")) { 
                    $listed .= '</br>'.$reverse_ip . '.' . $host ;
                     $listing[] = array('ip'=>$ip,
                                      'host'=>$host,
                                      'listed'=>'Listed'    
                             );
                }
                else
                {
                     $listing[] = array('ip'=>$ip,
                                      'host'=>$host,
                                      'listed'=>'Not Listed'    
                             );
                }
            }
        }
        if($isList == TRUE)
        {
            return $listing;
        }
        if($isList == FALSE)
        {
            if(empty($listed) ) {
                $message = 'Record was not found';
                redirect("check-ip-blacklist?status=".$message);
            } else {
                redirect("check-ip-blacklist?listed=".$listed);
            }
        }
        
    }
    function blacklist()
    {
            if (isset($_GET['ip']) && $_GET['ip'] != null)
            {
                $ip = $_GET['ip'];
                if (filter_var($ip, FILTER_VALIDATE_IP)) 
                {
                    $this->dnsbllookup($ip, FALSE);
                }
                else 
                {
                    $notValid = "Please enter a valid IP";
                    redirect("check-ip-blacklist?invalid=".$notValid);
                }
            }
    }
    /**
     * This function is used to send mail for IP Blacklist
     */
    function blacklistMail($data)
    {
        $row = "";
        $userInfo = $this->server_model->userListing();
       
         if(!empty($data))
        {
            foreach($data as $list)
            { 
                if($list['isListed'] == "Listed")
                {
                    $row .= '<tr   style="border-bottom: 1px solid #e8e8e8;text-align:center;" >
                            <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                            '.$list['ip'].'
                            </td>
                            <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                            '. $list['isListed'].'
                            </td>
                        </tr>'; 
                }
            }
        }  
        $message = '<table style="border:1px solid #e8e8e8;border-spacing:0;width:100%;">
                                <tr style="background:#D1F2EB;  border-bottom: 1px solid #e8e8e8;">
                                    <th   style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                        IP Address
                                    </th>
                                    <th  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                        is Listed?
                                    </th>
                                </tr>
                                    '.$row.'
                                <tr>
                                    <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                        <a href="'.base_url().'/check-ip-blacklist">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            </table>';
        if(!empty($userInfo))
        {
            foreach($userInfo as $user)
            { 
                $name = $user->name;
                $email = $user->email;
        $body = '
            <div style="text-align:center;"><img src="'. base_url() .'assets/dist/img/logo.png" alt="" /></div>
            <p>Hi '.$name.'</p>
            <p>IP Blacklist: </p> 
            '.$message.'
            ';
       
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $subject = "IP Blacklist";
        $result = $this->email
            ->from('webmaster@example.com','Orion eSolutions')
            // ->reply_to('')    // Optional, an account where a human being reads.
            ->to($email)
            ->subject($subject)
            ->message($body)
            ->send();
       
        if($result == TRUE)
        {
            $mailLogInfo = array('email_to'=>$email,'email_from'=>"webmaster@example.com",
            'email_subject'=>$subject ,'email_body'=>$body,'type_email'=>"ip_blacklist");
            $res = $this->server_model->addMailLog($mailLogInfo);
        }
        //var_dump($result);
        //echo '<br />';
        //echo $this->email->print_debugger();
        }
    }
    }
     /**
     * This function is used to load the add new ip
     */
    function addIP()
    {
        if(isset($_POST['add_ip'])=='Submit')
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('ip','IP','trim|required|xss_clean');
           
            $ip = $this->input->post('ip');
               
            $ipInfo = array('ip'=>$ip,'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('server_model');
                $result = $this->server_model->addNewIP($ipInfo);
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New ip created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'IP creation failed');
                }
                redirect("check-ip-blacklist");
        }
        redirect("check-ip-blacklist");
    }
  /**
     * This function is server load ip edit information
     * @param number $id : Optional : This is ip id
     */
    function editIP($id = NULL)
    {
        if(isset($_POST['edit_ip'])!='Edit')
        {
            if($id == null)
            {
                redirect('check-ip-blacklist');
            }
        }
        elseif(isset($_POST['edit_ip'])=='Edit'){
            $this->load->library('form_validation');
            
            $id = $this->input->post('id');
            $this->form_validation->set_rules('ip','IP','trim|required|xss_clean');
           
            $ip = $this->input->post('ip');
               
                $ipInfo = array();
                
                $ipInfo = array('ip'=>$ip,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->server_model->editIP($ipInfo, $id);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'IP updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'IP updation failed');
                }
                redirect('check-ip-blacklist');
            }
    }
     /**
     * This function is used to delete the ip using id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteIP()
    {
        if(isset($_POST['delete_ip'])!='Delete')
        {
            $id = $this->input->post('id');
            $ipInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->server_model->deleteIP($id, $ipInfo);
            
            if ($result > 0)
            { 
                echo(json_encode(array('status'=>TRUE))); 
            }
            else 
            {
                echo(json_encode(array('status'=>FALSE))); 
            }
        }
        elseif(isset($_POST['delete_ips'])=='Delete')
        {
            $del = $this->input->post('delete_ips');
            if($del != null)
            {
                foreach($del as $id):
                    $ipInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                    
                    $result = $this->server_model->deleteIP($id, $ipInfo);
                endforeach;
                if ($result > 0)
                {  
                    redirect('check-ip-blacklist');
                    unset($_POST['delete_ips']);
                }
            }
            else
            {
                redirect('check-ip-blacklist');
            }
        }
    }
    
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Orion eSolutions : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}
?>