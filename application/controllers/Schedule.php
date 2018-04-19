<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Server (ServerController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Schedule extends BaseController
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
   
    function schedules($limit = NULL)
    {
        if($this->isMember() == TRUE)
        {
            $this->load->model('schedule_model');
                   
            $this->load->library('pagination');
            $current_date = date('d-m-Y');    
            $count = $this->schedule_model->schedulesCount(null,null,null, $current_date,$this->vendorId);
            $returns = $this->paginationCompress ( "schedules/", $count, 5 );

            if(isset($_GET['search_backup'])!='Submit')
            {
                //$current_date = date('d-m-Y');
                $count = $this->schedule_model->schedulesCount($returns["page"], $returns["segment"],null, $current_date,$this->vendorId);
                $returns = $this->paginationCompress ( "schedules/", $count, 5 );
                $data['scheduleRecords'] = $this->schedule_model->schedules( $returns["page"], $returns["segment"],null,$current_date,$this->vendorId);
                $data['clients'] = $this->schedule_model->schedules( $returns["page"], $returns["segment"],null,$current_date,$this->vendorId);
                $data['servers'] = $this->schedule_model->schedules( $returns["page"], $returns["segment"],null,$current_date,$this->vendorId);
                $this->global['pageTitle'] = 'Orion eSolutions : Schedules Listing';
                //print_r($data);
              
                $this->loadViews("schedules", $this->global, $data, NULL);
            }
            elseif(isset($_GET['search_backup'])=='Submit')
            {
                $search_data['serverId'] = $this->input->get('server');
                $search_data['serverIP'] = $this->input->get('serverIP');
                $search_data['hostname'] = $this->input->get('hostname');
                $search_data['clientId'] = $this->input->get('client');
                $search_data['status'] = $this->input->get('status');
               
                $count = $this->schedule_model->schedulesCount($returns["page"], $returns["segment"],$search_data, $current_date,$this->vendorId);
                $returns = $this->paginationCompress ( "schedules/", $count, 5 );
                $data['scheduleRecords'] = $this->schedule_model->schedules( $returns["page"], $returns["segment"],$search_data,$current_date,$this->vendorId);
                $data['clients'] = $this->schedule_model->schedules( $returns["page"], $returns["segment"],null,$current_date,$this->vendorId);
                $data['servers'] = $this->schedule_model->schedules( $returns["page"], $returns["segment"],null,$current_date,$this->vendorId);
                $this->global['pageTitle'] = 'Orion eSolutions : Schedule Listing';
                //print_r($data);
                $this->loadViews("schedules", $this->global, $data, NULL);
               
            }else{}
        }
    }
    function scheduleDetails($id)
    {
        if($this->isAdmin() == FALSE  && $this->isMember() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            if($id == null)
            {
                redirect('schedules');
            }
            $this->load->model('schedule_model');
            
            $data['scheduleInfo'] = $this->schedule_model->getScheduleInfo($id);
           
            $this->global['pageTitle'] = 'Orion eSolutions : Schedule Details';
         
            $this->loadViews("scheduleDetails", $this->global, $data, NULL);
        }
    }

      /**
     * This function is server update schedule  status
     * @param number $id : Optional : This is user id
     */
    function updateScheduleStatus($id = NULL)
    {
        if($this->isMember() == FALSE )
        {
            $this->loadThis();
        }
        if(isset($_POST['backup_status'])=='Submit'){
                 $this->load->model('schedule_model');
                $this->load->library('form_validation');
				$id = $this->input->post('id');
				
				$this->form_validation->set_rules('backupStatus','BackupStatus','trim|required|numeric');
				
				if($this->form_validation->run() == FALSE)
				{
                    unset($_POST['backup_status']);
					$this->updateScheduleStatus($id);
				}
				else
				{
                    $id = $this->input->post('scheduleId');
                    $status = $this->input->post('backupStatus');
                    
					$scheduleInfo = array();
					
					$scheduleInfo = array('status'=>$status,
					 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
					
					$result = $this->schedule_model->updateScheduleStatus($scheduleInfo, $id);
					
					if($result == true)
					{
						$this->session->set_flashdata('success', 'Schedule status updated successfully');
					}
					else
					{
						$this->session->set_flashdata('error', 'Schedule status updation failed');
					}
					
					redirect('schedule-details/'.$id);
				}
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
        $this->current_fulldate = date('d-m-Y');
       
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
                    redirect("backups");
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
                        redirect("backups");
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
                        redirect("backups");
                    }
                }
            }
        endforeach;
       
    }
}

?>