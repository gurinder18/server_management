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
   
    function backups($limit = NULL)
    {
        if($this->isMember() == TRUE)
        {
            $this->load->model('backup_model');
            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;
                    
            $this->load->library('pagination');
                    
            $count = $this->backup_model->backupListingCount($searchText);
            $returns = $this->paginationCompress ( "backups/", $count, 5 );

            if(isset($_GET['search_backup'])!='Submit')
            {
                $data['backupRecords'] = $this->backup_model->membersBackups($searchText, $returns["page"], $returns["segment"],$this->vendorId);
                $data['clients'] = $this->backup_model->getClients();
                $data['users'] = $this->backup_model->getUsers();
                $this->global['pageTitle'] = 'Orion eSolutions : Backup Listing';
                //print_r($data);
              
               
                $this->loadViews("backups", $this->global, $data, NULL);
            }
            elseif(isset($_GET['search_backup'])=='Submit')
            {
                $search_data['userId'] = $this->input->get('user');
                $search_data['clientId'] = $this->input->get('client');
                $search_data['serverId'] = $this->input->get('server');
                $search_data['scheduleType'] = $this->input->get('scheduleType');
                $search_data['scheduleTimings'] = $this->input->get('scheduleTimings');

                $data['backupRecords'] = $this->backup_model->membersBackups($searchText, $returns["page"], $returns["segment"],$this->vendorId,$search_data);
                $data['clients'] = $this->backup_model->getClients();
                $data['users'] = $this->backup_model->getUsers();
                $this->global['pageTitle'] = 'Orion eSolutions : Backup Listing';
                //print_r($data);
                $this->loadViews("backups", $this->global, $data, NULL);
               
            }else{}
           
        }
        elseif($this->isAdmin() == TRUE)
        {
            $this->load->model('backup_model');
        
            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->backup_model->backupListingCount($searchText);

            $returns = $this->paginationCompress ( "backups/", $count, 5 );
            if(isset($_GET['search_backup'])!='Submit')
            {
                $data['backupRecords'] = $this->backup_model->backups($searchText, $returns["page"], $returns["segment"]);
                $data['clients'] = $this->backup_model->getClients();
                $data['users'] = $this->backup_model->getUsers();
                $this->global['pageTitle'] = 'Orion eSolutions : Backup Listing';
                //print_r($data);
               // $data["links"] = $this->pagination->create_links();
                $this->loadViews("backups", $this->global, $data, NULL);
            }
            elseif(isset($_GET['search_backup'])=='Submit')
            {
                $search_data['userId'] = $this->input->get('user');
                $search_data['clientId'] = $this->input->get('client');
                $search_data['serverId'] = $this->input->get('server');
                $search_data['scheduleType'] = $this->input->get('scheduleType');
                $search_data['scheduleTimings'] = $this->input->get('scheduleTimings');

                $data['backupRecords'] = $this->backup_model->searchBackups($searchText, $returns["page"], $returns["segment"],$search_data);
                $data['clients'] = $this->backup_model->getClients();
                $data['users'] = $this->backup_model->getUsers();
                $this->global['pageTitle'] = 'Orion eSolutions : Backup Listing';
                //print_r($data);
                $this->loadViews("backups", $this->global, $data, NULL);
               
            }else{}
        }else{} 

        
    }
    function backupDetails($id)
    {
        if($this->isAdmin() == FALSE  && $this->isMember() == FALSE)
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
            foreach($data as $backup)
            {
                $clientId=$backup['clientId'];
                $serverId=$backup['serverId'];
            }
            $data['clients'] = $this->backup_model->getClientById($clientId);
			//print_r($data);
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
     * This function is used to load the add new form and to add new backup
     */
    function addBackup()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        elseif(isset($_POST['add_backup'])!='Submit'){
			
            $this->load->model('backup_model');
            $data['clients'] = $this->backup_model->getClients();
            $data['users'] = $this->backup_model->getUsers();
            $this->global['pageTitle'] = 'Orion eSolutions : Add New Backup';
           
            $this->loadViews("addNewBackup", $this->global, $data, NULL);
        }
       elseif(isset($_POST['add_backup'])=='Submit'){
			$this->load->library('form_validation');
            
            $this->form_validation->set_rules('user','User','trim|required|numeric');
            $this->form_validation->set_rules('client','Client','trim|required|numeric');
            $this->form_validation->set_rules('server','Server','trim|required|numeric');
            $this->form_validation->set_rules('scheduleType','ScheduleType','trim|required|max_length[100]|xss_clean');
            $this->form_validation->set_rules('scheduleTimings','ScheduleTimings','trim|required|max_length[100]|xss_clean');
            $this->form_validation->set_rules('information','Information','trim|xss_clean');
           
            if($this->form_validation->run() == FALSE)
            {
                unset($_POST['add_backup']);
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
		}else{}
    }
    function getServers($clientId)
    {
        $data['servers'] = $this->backup_model->getServers($clientId);
        echo json_encode($data);
    }

    /**
     * This function is server load server edit information
     * @param number $id : Optional : This is user id
     */
    function editBackup($id = NULL)
    {
        if($this->isAdmin() == FALSE )
        {
            $this->loadThis();
        }
        elseif(isset($_POST['edit_backup'])!='Submit')
        {
            if($id == null)
            {
                redirect('backups');
            }
           
           
            $data['backupInfo'] = $this->backup_model->getBackupInfo($id);
            $data['clients'] = $this->backup_model->getClients();
            $data['users'] = $this->backup_model->getUsers();
            $this->global['pageTitle'] = 'Orion eSolutions : Edit Backup';
           
            $this->loadViews("editBackup", $this->global, $data, NULL);

           }elseif(isset($_POST['edit_backup'])=='Submit'){
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
                    unset($_POST['edit_backup']);
					$this->editBackup($id);
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
			}else{}
    }
    
    /**
     * This function is used to delete the server using id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteBackup()
    {
        if($this->isAdmin() == FALSE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        elseif(isset($_POST['delete_backup'])!='Delete')
        {
            $id = $this->input->post('id');
            $backupInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
          
            $result = $this->backup_model->deleteBackup($id, $backupInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
        elseif(isset($_POST['delete_backup'])=='Delete')
        {
            $del = $this->input->post('delete_backups');
            if($del!=null)
            {
                foreach($del as $id):
                   
                    $backupInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
          
                    $result = $this->backup_model->deleteBackup($id, $backupInfo);
                endforeach;
                if ($result > 0)
                {  
                    redirect("backups");
                }
            }else
            {
                redirect("backups");
            }
        }
        else{}
    }
    
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Orion eSolutions : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }

     /**
     * This function is used to add the backup schedule information
     */
    function scheduleBackups()
    {
        $this->load->model('backup_model');

        $result = $this->backup_model->getBackups();

        $date = cal_to_jd(CAL_GREGORIAN,date("m"),date("d"),date("Y"));
        $this->current_day = (jddayofweek($date,1));
        $this->current_date = date("d");
        $this->current_fulldate = date('d-m-Y');;
       
        foreach($result as $res):
            $clientId =  $res->clientId;
            $backupId =  $res->id;
            $userId =  $res->userId;
            $status =  1;
            $scheduleType = $res->scheduleType;
            $scheduleTimings = $res->scheduleTimings;

            if($scheduleType=='Daily')
            {
                $scheduleInfo = array('date'=>$this->current_fulldate,'clientId'=>$clientId,
                'backupId'=>$backupId,'userId'=>$userId,'status'=>$status,);
                $result = $this->backup_model->addBackupSchedule($scheduleInfo);
                if($result==1)
                {
                    echo "Schedule successfully added";
                }
            }
            if($scheduleType=='Weekly')
            {
                if($scheduleTimings == $this->current_day)
                {
                    $scheduleInfo = array('date'=>$this->current_fulldate,'clientId'=>$clientId,
                    'backupId'=>$backupId,'userId'=>$userId,'status'=>$status,);
                    $result = $this->backup_model->addBackupSchedule($scheduleInfo);
                    if($result==1)
                    {
                        echo "Schedule successfully added";
                    }
                }
            }
            if($scheduleType=='Monthly')
            {
                if($scheduleTimings == $this->current_date)
                {
                    $scheduleInfo = array('date'=>$this->current_fulldate,'clientId'=>$clientId,
                    'backupId'=>$backupId,'userId'=>$userId,'status'=>$status,);
                    $result = $this->backup_model->addBackupSchedule($scheduleInfo);
                    if($result==1)
                    {
                        echo "Schedule successfully added";
                    }
                }
            }
        endforeach;
       
       // print_r($result);
        //$this->loadViews("backups", $this->global, NULL, NULL);
    }
}

?>