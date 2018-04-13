<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Server (ServerController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Backup extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backup_model');
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
   
    function backups()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('backup_model');
        
            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->backup_model->backupListingCount($searchText);

			$returns = $this->paginationCompress ( "servers/", $count, 5 );
            
            $data['backupRecords'] = $this->backup_model->backups($searchText, $returns["page"], $returns["segment"]);
            $this->global['pageTitle'] = 'Orion eSolutions : Backup Listing';
            //print_r($data);
            $this->loadViews("backups", $this->global, $data, NULL);
        }
    }
    function backupDetails($id)
    {
        if($this->isAdmin() == TRUE )
        {
            $this->loadThis();
        }
        else
        {
            if($id == null)
            {
                redirect('backups');
            }
            
           // $data['roles'] = $this->user_model->getUserRoles();
            $data['backupInfo'] = $this->backup_model->getBackupInfo($id);
            foreach($data['backupInfo'] as $backup)
            {
                $clientId=$backup->clientId;
                $serverId=$backup->serverId;
            }
            $data['clients'] = $this->backup_model->getClientById($clientId);
            $data['servers'] = $this->backup_model->getServerById($serverId);
            
            $this->global['pageTitle'] = 'Orion eSolutions : Backup Details';
          
            $this->loadViews("backupDetails", $this->global, $data, NULL);
        }
    }
    function checkUserExists()
    {
        $id = $this->input->post("id");
        $username = $this->input->post("username");

        if(empty($id)){
            $result = $this->backup_model->checkUserExists($username);
        } else {
            $result = $this->backup_model->checkUserExists($username, $id);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * This function is used to load the add new form
     */
    function addBackup()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('backup_model');
            $data['clients'] = $this->backup_model->getClients();
            $data['users'] = $this->backup_model->getUsers();
            $this->global['pageTitle'] = 'Orion eSolutions : Add New Backup';
           
            $this->loadViews("addNewBackup", $this->global, $data, NULL);
        }
    }
    function getServers($clientId)
    {
        $data['servers'] = $this->backup_model->getServers($clientId);
        echo json_encode($data);
    }
    
    /**
     * This function is used to add new server to the system
     */
    function addBackup2()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('user','User','trim|required|numeric');
            $this->form_validation->set_rules('client','Client','trim|required|numeric');
            $this->form_validation->set_rules('server','Server','trim|required|numeric');
            $this->form_validation->set_rules('scheduleType','ScheduleType','trim|required|max_length[100]|xss_clean');
            $this->form_validation->set_rules('scheduleTimings','ScheduleTimings','trim|required|max_length[100]|xss_clean');
            $this->form_validation->set_rules('information','Information','trim|xss_clean');
           
            if($this->form_validation->run() == FALSE)
            {
                $this->addBackup();
            }
            else
            {
                $userId = $this->input->post('user');
                $clientId = $this->input->post('client');
                $serverId = $this->input->post('server');
                $scheduleType = $this->input->post('scheduleType');
                $scheduleTimings = $this->input->post('scheduleTimings');
                $information = $this->input->post('information');
                
                $backupInfo = array('userId'=>$userId,'clientId'=>$clientId,'serverId'=>$serverId,
                'scheduleType'=>$scheduleType,'scheduleTimings'=>$scheduleTimings,'information'=>$information,
                'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('backup_model');
                $result = $this->backup_model->addBackup($backupInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New schedule created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Schedule creation failed');
                }
                
                redirect('backups');
            }
        }
    }

    
    /**
     * This function is server load server edit information
     * @param number $id : Optional : This is server id
     */
    function edit($id = NULL)
    {
        if($this->isAdmin() == TRUE )
        {
            $this->loadThis();
        }
        else
        {
            if($id == null)
            {
                redirect('backups');
            }
            
           // $data['roles'] = $this->user_model->getUserRoles();
            $data['backupInfo'] = $this->backup_model->getBackupInfo($id);
            
            $data['clients'] = $this->backup_model->getClients();
            $data['users'] = $this->backup_model->getUsers();
            $this->global['pageTitle'] = 'Orion eSolutions : Edit Backup';
           //print_r($data);
            $this->loadViews("editBackup", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the server information
     */
    function editBackup()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id = $this->input->post('id');
            
            $this->form_validation->set_rules('user','User','trim|required|numeric');
            $this->form_validation->set_rules('client','Client','trim|required|numeric');
            $this->form_validation->set_rules('server','Server','trim|required|numeric');
            $this->form_validation->set_rules('scheduleType','ScheduleType','trim|required|max_length[100]|xss_clean');
            $this->form_validation->set_rules('scheduleTimings','ScheduleTimings','trim|required|max_length[100]|xss_clean');
            $this->form_validation->set_rules('information','Information','trim|xss_clean');
           
            if($this->form_validation->run() == FALSE)
            {
                $this->edit($id);
            }
            else
            {
                $userId = $this->input->post('user');
                $clientId = $this->input->post('client');
                $serverId = $this->input->post('server');
                $scheduleType = $this->input->post('scheduleType');
                $scheduleTimings = $this->input->post('scheduleTimings');
                $information = $this->input->post('information');
                
                $backupInfo = array();
                
                $backupInfo = array('userId'=>$userId,'clientId'=>$clientId,'serverId'=>$serverId,
                'scheduleType'=>$scheduleType,'scheduleTimings'=>$scheduleTimings,'information'=>$information,
                 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->backup_model->editBackup($backupInfo, $id);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Backup updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Backup updation failed');
                }
                
                redirect('edit-backup/'.$id);
            }
        }
    }


    /**
     * This function is used to delete the server using id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteBackup()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $id = $this->input->post('id');
            $backupInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
          
            $result = $this->backup_model->deleteBackup($id, $backupInfo);
            
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