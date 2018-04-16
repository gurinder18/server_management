<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Client (ClientController)
 * Client Class to control all client related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Client extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('client_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the client
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Orion eSolutions : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the client list
     */
    function clients()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('client_model');
        
            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->client_model->clientListingCount($searchText);

			$returns = $this->paginationCompress ( "clients/", $count, 5 );
            
            $data['clientRecords'] = $this->client_model->clients($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Orion eSolutions : Clients Listing';
            $this->loadViews("clients", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addClient()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        elseif(isset($_POST['add_client'])!='Submit')
        {
            $this->load->model('client_model');
            //$data['roles'] = $this->user_model->getUserRoles();
            
            $this->global['pageTitle'] = 'Orion eSolutions : Add New Client';

            $this->loadViews("addNewClient", $this->global, NULL, NULL);
        }
        elseif(isset($_POST['add_client'])=='Submit'){
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('name','Name','trim|required|max_length[50]|xss_clean');
            $this->form_validation->set_rules('phone','Phone','required|max_length[10]|numeric');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|max_length[100]');
            $this->form_validation->set_rules('address','Address','trim|required|xss_clean');
            $this->form_validation->set_rules('city','City','trim|required|max_length[50]|xss_clean');
            $this->form_validation->set_rules('state','State','trim|required|max_length[50]|xss_clean');
            $this->form_validation->set_rules('zip','Zip','trim|required|max_length[50]|numeric');
            $this->form_validation->set_rules('status','Status','trim|required|numeric');
           
            if($this->form_validation->run() == FALSE)
            {
                unset($_POST['add_client']);
                $this->addClient();
            }
            else
            {
                $name = ucwords(strtolower($this->input->post('name')));
                $phone = $this->input->post('phone');
                $email = $this->input->post('email');
                $address = $this->input->post('address');
                $city = $this->input->post('city');
                $state = $this->input->post('state');
                $zip = $this->input->post('zip');
                $status = $this->input->post('status');
                
                $clientInfo = array('name'=>$name, 'phone'=>$phone, 'email'=>$email, 'address'=>$address,
                'city'=>$city, 'state'=>$state, 'zip'=>$zip, 'status'=>$status,  'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('client_model');
                $result = $this->client_model->add($clientInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Client created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Client creation failed');
                }
                
                redirect('clients');
            }
        }else{}
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
   
    /**
     * This function is used load client edit information
     * @param number $id : Optional : This is client id
     */
    function editClient($id = NULL)
    {
        if($this->isAdmin() == TRUE )
        {   
            $this->loadThis();
        }
        elseif(isset($_POST['edit_client'])!='Submit')
        {
            if($id == null)
            {
                redirect('clients');
            }
            
            //$data['roles'] = $this->user_model->getUserRoles();
            $data['clientInfo'] = $this->client_model->getClientInfo($id);
            
            $this->global['pageTitle'] = 'Orion eSolutions : Edit Client';
            
            $this->loadViews("editClient", $this->global, $data, NULL);
        }
        elseif(isset($_POST['edit_client'])=='Submit'){
            $this->load->library('form_validation');
            
            $id = $this->input->post('id');
            
            $this->form_validation->set_rules('name','Name','trim|required|max_length[50]|xss_clean');
            $this->form_validation->set_rules('phone','Phone','required|max_length[10]|numeric');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|max_length[100]');
            $this->form_validation->set_rules('address','Address','trim|required|xss_clean');
            $this->form_validation->set_rules('city','City','trim|required|max_length[50]|xss_clean');
            $this->form_validation->set_rules('state','State','trim|required|max_length[50]|xss_clean');
            $this->form_validation->set_rules('zip','Zip','trim|required|max_length[50]|numeric');
            $this->form_validation->set_rules('status','Status','trim|required|numeric');
            
            if($this->form_validation->run() == FALSE)
            {
                unset($_POST['edit_client']);
                $this->editClient($id);
            }
            else
            {
                $name = ucwords(strtolower($this->input->post('name')));
                $phone = $this->input->post('phone');
                $email = $this->input->post('email');
                $address = $this->input->post('address');
                $city = $this->input->post('city');
                $state = $this->input->post('state');
                $zip = $this->input->post('zip');
                $status = $this->input->post('status');
                
                $clientInfo = array();
                
                 $clientInfo = array('name'=>$name, 'phone'=>$phone, 'email'=>$email, 'address'=>$address,
                    'city'=>$city, 'state'=>$state, 'zip'=>$zip, 'status'=>$status, 'updatedBy'=>$this->vendorId, 
                        'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->client_model->edit($clientInfo, $id);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Client updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Client updation failed');
                }
                
                redirect('edit-client/'.$id);
            }
        }else{}
    }
    
    /**
     * This function is used to delete the client using id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteClient()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $id = $this->input->post('id');
            $clientInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->client_model->deleteClient($id, $clientInfo);
            
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