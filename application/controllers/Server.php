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

            if(isset($_GET['search_server'])!='Search')
            {
                $data['clients'] = $this->server_model->getClients();
                $data['serverRecords'] = $this->server_model->membersServers($returns["page"], $returns["segment"],$this->vendorId);
                $data['servers'] = $this->server_model->membersServers( $returns["page"], $returns["segment"],$this->vendorId);
               
                $this->global['pageTitle'] = 'Orion eSolutions : Server Listing';
                
                $this->loadViews("servers", $this->global, $data, NULL);
            }
            elseif(isset($_GET['search_server'])=='Search')
            {
                $search_data['name'] = $this->input->get('name');
                $search_data['clientId'] = $this->input->get('client');
                $search_data['server'] = $this->input->get('server');
                $search_data['hostname'] = $this->input->get('hostname');
                $search_data['status'] = $this->input->get('status');

                $data['servers'] = $this->server_model->membersServers($returns["page"], $returns["segment"],$this->vendorId);
                $data['clients'] = $this->server_model->getClients();
                $data['serverRecords'] = $this->server_model->membersServers($returns["page"], $returns["segment"],$this->vendorId,$search_data);
                $this->global['pageTitle'] = 'Orion eSolutions : Server Listing';
                
                $this->loadViews("servers", $this->global, $data, NULL);
               
            }else{}
           
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
            
            if(isset($_GET['search_server'])!='Search')
            {
                $data['clients'] = $this->server_model->getClients();
                $data['servers'] = $this->server_model->serverListing( null, $returns["segment"]);
                $data['serverRecords'] = $this->server_model->serverListing( $returns["page"], $returns["segment"]);
                $this->global['pageTitle'] = 'Orion eSolutions : Server Listing';
                
                $this->loadViews("servers", $this->global, $data, NULL);
            }
            elseif(isset($_GET['search_server'])=='Search')
            {
                $search_data['name'] = $this->input->get('name');
                $search_data['clientId'] = $this->input->get('client');
                $search_data['server'] = $this->input->get('server');
                $search_data['hostname'] = $this->input->get('hostname');
                $search_data['status'] = $this->input->get('status');

                $data['servers'] = $this->server_model->serverListing( null, $returns["segment"]);
                $data['clients'] = $this->server_model->getClients();
                $data['serverRecords'] = $this->server_model->searchServer($returns["page"], $returns["segment"],$search_data);
                $this->global['pageTitle'] = 'Orion eSolutions : Server Listing';
                
                $this->loadViews("servers", $this->global, $data, NULL);
            }else{}
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
            $this->form_validation->set_rules('password','Password','max_length[20]');
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
                
                $serverInfo = array('name'=>ucwords($name),'clientId'=>$client,'server'=>$server,'hostname'=>$hostname,
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
            $this->form_validation->set_rules('password','Password','max_length[20]');
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
               
                $serverInfo = array();
                
                $serverInfo = array('name'=>ucwords($name),'clientId'=>$client,'server'=>$server,'hostname'=>$hostname,
                'username'=>$username,'password'=>$password,'status'=>$status,'details'=>$details, 'updatedBy'=>$this->vendorId, 
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
     * This function is used to load the add new server
     */
    function checkBlacklist()
    {
        if($this->isAdmin() == FALSE && $this->isMember() == FALSE)
        {
            $this->loadThis();
        }
        $this->load->model('server_model');
        $data['clients'] = $this->server_model->getClients();
           
        $this->global['pageTitle'] = 'Orion eSolutions : Add New Server';
           
         $this->loadViews("checkBlacklist", $this->global, $data, NULL);
        
    }
    function dnsbllookup($ip)
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
            "xbl.spamhaus.org"
        ];
        $listed = "";
        if ($ip) {
            $reverse_ip = implode(".", array_reverse(explode(".", $ip)));
            foreach ($dnsbl_lookup as $host) {
                if (checkdnsrr($reverse_ip . "." . $host . ".", "A")) {
                    $listed .= '</br>'.$reverse_ip . '.' . $host ;
                }
            }
        }
        if (empty($listed)) {
            $message = 'Record was not found';
            redirect("check-ip-blacklist?status=".$message);
        } else {
            redirect("check-ip-blacklist?listed=".$listed);
        }
    }
    function blacklist()
    {
        if (isset($_GET['ip']) && $_GET['ip'] != null)
        {
            $ip = $_GET['ip'];
            if (filter_var($ip, FILTER_VALIDATE_IP)) 
            {
                echo $this->dnsbllookup($ip);
            }
            else 
            {
                $notValid = "Please enter a valid IP";
                redirect("check-ip-blacklist?invalid=".$notValid);
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