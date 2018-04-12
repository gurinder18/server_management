<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Server (ServerController)
 * User Class to control all user related operations.
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
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('server_model');
        
            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->server_model->serverListingCount($searchText);

			$returns = $this->paginationCompress ( "servers/", $count, 5 );
            
            $data['serverRecords'] = $this->server_model->serverListing($searchText, $returns["page"], $returns["segment"]);
            $this->global['pageTitle'] = 'Orion eSolutions : Server Listing';
            //print_r($data);
            $this->loadViews("servers", $this->global, $data, NULL);
        }
    }
    function checkUserExists()
    {
        $id = $this->input->post("id");
        $username = $this->input->post("username");

        if(empty($id)){
            $result = $this->server_model->checkUserExists($username);
        } else {
            $result = $this->server_model->checkUserExists($username, $id);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * This function is used to load the add new form
     */
    function addNewServer()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('server_model');
            $data['clients'] = $this->server_model->getClients();
           
            $this->global['pageTitle'] = 'Orion eSolutions : Add New Server';
           
            $this->loadViews("addNewServer", $this->global, $data, NULL);
        }
    }

    
    /**
     * This function is used to add new server to the system
     */
    function addNewServer2()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
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
                $this->addNewServer();
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
                $result = $this->server_model->addNewServer2($serverInfo);
                
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
        }
    }

    
    /**
     * This function is server load server edit information
     * @param number $id : Optional : This is server id
     */
    function editOldServer($id = NULL)
    {
        if($this->isAdmin() == TRUE )
        {
            $this->loadThis();
        }
        else
        {
            if($id == null)
            {
                redirect('servers');
            }
            
           // $data['roles'] = $this->user_model->getUserRoles();
            $data['serverInfo'] = $this->server_model->getServerInfo($id);
            $data['clients'] = $this->server_model->getClients();
            $this->global['pageTitle'] = 'Orion eSolutions : Edit Server';
           //print_r($data);
            $this->loadViews("editOldServer", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the server information
     */
    function editServer()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
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
                $this->editOldServer($id);
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
        }
    }


    /**
     * This function is used to delete the server using id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteServer()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $id = $this->input->post('id');
            $serverInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->server_model->deleteServer($id, $serverInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }
    
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Orion eSolutions : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

?>