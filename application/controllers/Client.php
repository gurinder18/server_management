<?php
ob_start();
if(!defined('BASEPATH')) exit('No direct script access allowed');

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
         date_default_timezone_set('Asia/Kolkata');
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
        if($this->isAdmin() == FALSE)
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
            $data['clientsUsers'] = $this->client_model->getClientsUsers(); 
            $this->global['pageTitle'] = 'Orion eSolutions : Clients Listing';
            $this->loadViews("clients", $this->global, $data, NULL);
        }
    }
 
    /**
     * This function is used to load the add new form
     */
    function addClient()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        elseif(isset($_POST['add_client']) != 'Submit')
        {
            $this->load->model('client_model');
            $data['users'] = $this->client_model->getUsers();
            
            $this->global['pageTitle'] = 'Orion eSolutions : Add New Client';

            $this->loadViews("addNewClient", $this->global, $data, NULL);
        }
        elseif(isset($_POST['add_client']) == 'Submit'){
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('name','Name','trim|required|max_length[50]|xss_clean');
            $this->form_validation->set_rules('phone','Phone','min_length[10]|numeric');
            $this->form_validation->set_rules('email','Email','trim|valid_email|xss_clean|max_length[100]');
            $this->form_validation->set_rules('address','Address','trim|xss_clean');
            $this->form_validation->set_rules('city','City','trim|max_length[50]|xss_clean');
            $this->form_validation->set_rules('state','State','trim|max_length[50]|xss_clean');
            $this->form_validation->set_rules('country','Country','trim|max_length[50]|xss_clean');
            $this->form_validation->set_rules('zip','Zip','trim|max_length[50]|xss_clean');
            $this->form_validation->set_rules('organisation','Organisation','trim|xss_clean');
            $this->form_validation->set_rules('contacts','Contacts','trim|xss_clean');
            $this->form_validation->set_rules('status','Status','trim|numeric');
           // $this->form_validation->set_rules('user[]','User','required');
           
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
                $country = $this->input->post('country');
                $zip = $this->input->post('zip');
                $status = $this->input->post('status');
                $organisation = $this->input->post('organisation');
                $contacts = $this->input->post('contacts');
                $userId = $this->input->post('user');
               
                if($status == "")
                {
                    $status = 1;
                }
                        date_default_timezone_set('Asia/Kolkata');
                        $clientInfo = array('name'=>$name, 'phone'=>$phone, 'email'=>$email, 'address'=>$address,
                        'city'=>$city, 'state'=>$state,'country'=>$country, 'zip'=>$zip, 'status'=>$status,
                        'organisation'=>$organisation,'contacts'=>$contacts,'createdBy'=>$this->vendorId, 
                        'createdDtm'=>date('Y-m-d H:i:s'));
                        
                        $this->load->model('client_model');
                        $clientId = $this->client_model->add($clientInfo);
                        
                        if($clientId > 0)
                        {
                            $this->session->set_flashdata('success', 'New Client created successfully');
                        }
                        else
                        {
                            $this->session->set_flashdata('error', 'Client creation failed');
                        }

                        foreach( $userId as $user)
                        {
                            $clientUserInfo = array('clientId'=>$clientId, 'userId'=>$user, 
                            'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));

                            $result = $this->client_model->addClientsUser($clientUserInfo);
                            if($result > 0)
                            {
                                $this->session->set_flashdata('success', 'New Client'."'".'s User created successfully');
                            }
                            else
                            {
                                $this->session->set_flashdata('error', 'Client'."'".'s User creation failed');
                            }
                        }
                redirect('clients');
            }
        }
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
        if($this->isAdmin() == FALSE )
        {   
            $this->loadThis();
        }
        elseif(isset($_POST['edit_client'])!='Submit')
        {
            if($id == null)
            {
                redirect('clients');
            }
            
            $data['clientInfo'] = $this->client_model->getClientInfo($id);
            $data['clientUsers'] = $this->client_model->getClientsUserInfo($id);
            
            $data['users'] = $this->client_model->getUsers();

            $this->global['pageTitle'] = 'Orion eSolutions : Edit Client';
            
            $this->loadViews("editClient", $this->global, $data, NULL);
        }
        elseif(isset($_POST['edit_client'])=='Submit'){
            $this->load->library('form_validation');
            
            $id = $this->input->post('id');
            
            $this->form_validation->set_rules('name','Name','trim|required|max_length[50]|xss_clean');
            $this->form_validation->set_rules('phone','Phone','min_length[10]|numeric');
            $this->form_validation->set_rules('email','Email','trim|valid_email|xss_clean|max_length[100]');
            $this->form_validation->set_rules('address','Address','trim|xss_clean');
            $this->form_validation->set_rules('city','City','trim|max_length[50]|xss_clean');
            $this->form_validation->set_rules('state','State','trim|max_length[50]|xss_clean');
            $this->form_validation->set_rules('country','Country','trim|max_length[50]|xss_clean');
            $this->form_validation->set_rules('zip','Zip','trim|max_length[50]|xss_clean');
            $this->form_validation->set_rules('status','Status','trim|numeric');
            $this->form_validation->set_rules('organisation','Organisation','trim|xss_clean');
            $this->form_validation->set_rules('contacts','Contacts','trim|xss_clean');
             $this->form_validation->set_rules('user[]','User','required');
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
                $country = $this->input->post('country');
                $zip = $this->input->post('zip');
                $status = $this->input->post('status');
                $organisation = $this->input->post('organisation');
                $contacts = $this->input->post('contacts');
                $userId = $this->input->post('user');
                
                $clientInfo = array();
                $clientInfo = array('name'=>$name, 'phone'=>$phone, 'email'=>$email, 'address'=>$address,
                'city'=>$city, 'state'=>$state, 'country'=>$country, 'zip'=>$zip, 'status'=>$status,
                'updatedBy'=>$this->vendorId, 'organisation'=>$organisation,'contacts'=>$contacts,
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
               
                if($userId != null)
                {
                $data['clientUsers'] = $this->client_model->getClientsUserInfo($id);
                $userCount = count( $data['clientUsers']);
                $editUserCount = count($userId);
               
                foreach($data['clientUsers'] as $usId)
                {
                   $users[] =  $usId->userId;
                }
                
                if($userCount == '')
                { 
                    foreach($userId as $UserId)
                    { 
                        $clientUserInfo = array('clientId'=>$id, 'userId'=>$UserId, 
                        'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
        
                        $result = $this->client_model->addClientsUser($clientUserInfo);
                    }
                }
                if($userCount == $editUserCount)
                {
                    $User = array_diff( $users,$userId);
                  
                    if(!empty($User))
                    {
                        foreach($User as $UserId)
                        {
                            $getInfo = $this->client_model->getClientsUser($id, $UserId);
                            if(!empty($getInfo))
                            {
                                $us = array_diff($userId,$users);
                               
                                foreach($us as $uId)
                                { 
                                    foreach($getInfo as $info)
                                    {
                                        $userInfo = $this->client_model->getClientsUser($info->clientId, $uId);
                                         
                                        if(!empty($userInfo))
                                        {
                                            foreach($userInfo as $uInfo)
                                            {
                                                $clientInfo = array('isDeleted'=>0,'updatedBy'=>$this->vendorId,
                                                'updatedDtm'=>date('Y-m-d H:i:s'));
                                            
                                                $res = $this->client_model->deleteClientsUser( $uInfo->userId,$id, $clientInfo);

                                                $clientInfo1 = array('isDeleted'=>1,'updatedBy'=>$this->vendorId,
                                                'updatedDtm'=>date('Y-m-d H:i:s'));
                                            
                                                $res = $this->client_model->deleteClientsUser( $UserId,$id, $clientInfo1);
                                               
                                            }
                                        }
                                        else
                                        {
                                            $clientUserInfo = array('userId'=>$uId, 'updatedBy'=>$this->vendorId,
                                            'updatedDtm'=>date('Y-m-d H:i:s'));

                                            $res = $this->client_model->editClientsUser($clientUserInfo, $info->id);
                                           
                                            $clientUserInfo1 = array('clientId'=>$id, 'userId'=>$uId, 
                                            'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                    
                                            $result = $this->client_model->addClientsUser($clientUserInfo1);
                                          
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                elseif($userCount < $editUserCount)
                {
                    $newUser = array_diff($userId, $users);
                  
                    foreach($newUser as $usId)
                    {
                        $getUserInfo = $this->client_model->getClientsUser($id, $usId);
                        
                        if(empty($getUserInfo))
                        {
                            $clientUserInfo = array('clientId'=>$id, 'userId'=>$usId, 
                            'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
    
                            $result = $this->client_model->addClientsUser($clientUserInfo);
                        }
                        else
                        {
                            foreach($getUserInfo as $infos)
                            {
                                $clientInfos = array('isDeleted'=>0,'updatedBy'=>$this->vendorId,
                                'updatedDtm'=>date('Y-m-d H:i:s'));
                            
                                $result = $this->client_model->deleteClientsUser( $infos->userId,$id, $clientInfos);
                               
                            }
                        }
                    }
                }
                elseif($userCount > $editUserCount)
                {
                    $delUser = array_diff($users , $userId);

                    foreach($delUser as $del)
                    {
                        $clientInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId,
                         'updatedDtm'=>date('Y-m-d H:i:s'));
                    
                        $result = $this->client_model->deleteClientsUser( $del,$id, $clientInfo);
                    }     
                   
                }
                // elseif($editUserCount == "")
                // {
                //     $getUserInfo = $this->client_model->getClientUserInfo($id);
                //     //var_dump($getUserInfo);die;
                //     if(!empty($getUserInfo))
                //     {
                //         foreach($getUserInfo as $infos)
                //         {
                //             $clientInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId,
                //             'updatedDtm'=>date('Y-m-d H:i:s'));
                            
                //             $result = $this->client_model->deleteClientsUser(  $infos->userId,$id, $clientInfo);
                //         }
                //     }
                //     else
                //     {
                //         redirect('edit-client/'.$id);
                //     }
                // }
                redirect('edit-client/'.$id);
            }
        }
    }
    }
     
    /**
     * This function is used to delete the client using id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteClient()
    {
        if($this->isAdmin() == FALSE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        elseif(isset($_POST['delete_client'])!='Delete')
        {
            $id = $this->input->post('id');
            $clientInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->client_model->deleteClient($id, $clientInfo);
            
            if ($result > 0) 
            { 
                echo(json_encode(array('status'=>TRUE))); 
            }
            else 
            { 
                echo(json_encode(array('status'=>FALSE))); 
            }
        }
        elseif(isset($_POST['delete_client'])=='Delete')
        { 
                $del = $this->input->post('delete_clients');
                if($del != null)
                {
                    foreach($del as $id):
                        $clientInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                            
                        $result = $this->client_model->deleteClient($id, $clientInfo);
                    endforeach;
                    if ($result > 0)
                    {  
                        redirect("clients");
                        unset($_POST['delete_client']);
                    }
                }
                else
                {
                    redirect("clients");
                }
        }
    }
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Orion eSolutions : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}
ob_flush();
?>