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
                $data['clients'] = $this->schedule_model->getClients();
                $data['servers'] = $this->schedule_model->getServers();
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
                $data['clients'] = $this->schedule_model->getClients();
                $data['servers'] = $this->schedule_model->getServers();
               $this->global['pageTitle'] = 'Orion eSolutions : Schedule Listing';
                //print_r($data);
                $this->loadViews("schedules", $this->global, $data, NULL);
               
            }
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
            //print_r($data);
            $scheduleId = $data['scheduleInfo'];
           // print_r($scheduleId);die;
            $data['commentInfo'] = $this->schedule_model->getCommentInfo( $scheduleId['id']);

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
        elseif(isset($_POST['backup_status'])=='Submit'){
                 $this->load->model('schedule_model');
                $this->load->library('form_validation');
				$id = $this->input->post('scheduleId');
				
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
     /**
     * This function is used to load the add new form and to add new comment
     */
    function addComment($id)
    {
        if($this->isMember() == FALSE)
        {
            $this->loadThis();
        }
       elseif(isset($_POST['add_comment'])=='Submit')
       {
			//$this->load->library('form_validation');
            
           // $this->form_validation->set_rules('scheduleId','ScheduleId','trim|required|numeric');
           // $this->form_validation->set_rules('userId','UserId','trim|required|numeric');
           // $this->form_validation->set_rules('statusId','StatusId','trim|required|numeric');
           // $this->form_validation->set_rules('comment','Comment','trim|required|xss_clean');
           
         //   if($this->form_validation->run() == FALSE)
           // {
            //    unset($_POST['add_comment']);
            //    $this->addComment($id);
          //  }
          //  else
          //  {
            $status = "";
            $msg = "";
            $file_element_name = 'comment';
                if (empty($_POST['comment']))
                {
                    $status = "error";
                    $msg = "Please enter a title";
                }
                if ($status != "error")
                {
                    $config['upload_path'] = './files/';
                    $config['allowed_types'] = 'gif|jpg|png|doc|txt';
                    $config['max_size'] = 1024 * 8;
                   // $config['encrypt_name'] = TRUE;
             
                    $this->load->library('addComment', $config);
                    if (!$this->upload->do_upload($file_element_name))
                    {
                        $status = 'error';
                        $msg = $this->upload->display_errors('', '');
                    }
                    else
                    {
                        $data = $this->upload->data();
                        $file_id = $this->files_model->insert_file($_POST['scheduleId'], $_POST['userId'], 
                        $_POST['statusId'], $_POST['comment'],$data['attachment']);
                        if($file_id)
                        {
                            $status = "success";
                            $msg = "File successfully uploaded";
                        }
                        else
                        {
                            unlink($data['full_path']);
                            $status = "error";
                            $msg = "Something went wrong when saving the file, please try again.";
                        }
                    }
                    @unlink($_FILES[$file_element_name]);
                }
                echo json_encode(array('status' => $status, 'msg' => $msg));
             
                /*
                $attachment = "attachment";
                $scheduleId = $this->input->post('scheduleId');
                //$userId = $this->input->post('userId');
                $statusId = $this->input->post('statusId');
                $comment = $this->input->post('comment');
               // $attachment = $this->input->post('attachment');
              
                $commentInfo = array('scheduleId'=>$scheduleId,'userId'=>$this->vendorId,'statusId'=>$statusId,
                'userComment'=>$comment,'createdDtm'=>date('Y-m-d H:i:s'));
                $attachmentInfo = array('scheduleId'=>$scheduleId,'filePath'=>'','createdDtm'=>date('Y-m-d H:i:s'));
               
                $this->load->model('schedule_model');
                $result = $this->schedule_model->addComment($commentInfo,$attachmentInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New schedule created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Schedule creation failed');
                }
                
                redirect('schedule-details/'.$id);*/
            
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

    function fileupload()
    {
        $status = "";
        $msg = "";
        $file_element_name = 'attachment';
        $this->load->model('schedule_model');
        if (empty($_POST['comment']))
        {
            $status = "error";
            $msg = "Please enter a title";
        }
         
        if ($status != "error")
        {
            $path = realpath(APPPATH . '../assets/files');
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'gif|jpg|png|doc|txt';
            $config['max_size'] = 1024 * 8;
            $config['encrypt_name'] = TRUE;
     
            $this->load->library('upload', $config);
     
            if (!$this->upload->do_upload($file_element_name))
            {
                $status = 'error';
                $msg = $this->upload->display_errors('', '');
            }
            else
            {
                $data = $this->upload->data();
                $scheduleId = $this->input->post('scheduleId');
                $statusId = $this->input->post('statusId');
                $comment = $this->input->post('comment');

                $commentInfo = array('scheduleId'=>$scheduleId,'userId'=>$this->vendorId,'statusId'=>$statusId,
                'userComment'=>$comment,'createdDtm'=>date('Y-m-d H:i:s'));
                if(!$data['file_name']=="")
                {
                    $attachmentInfo = array('scheduleId'=>$scheduleId,'filePath'=>$data['file_name'],
                    'createdDtm'=>date('Y-m-d H:i:s'));
               
                    $file_id = $this->schedule_model->addComment($commentInfo, $attachmentInfo);
                    if($file_id>0)
                    {
                        $status = "success";
                        $msg = "File successfully uploaded";
                    }
                    else
                    {
                        unlink($data['full_path']);
                        $status = "error";
                        $msg = "Something went wrong when saving the file, please try again.";
                    }
                }
                else
                {
                    $file_id = $this->schedule_model->addComment($commentInfo, null);
                    if($file_id>0)
                    {
                        $status = "success";
                        $msg = "File successfully uploaded";
                    }
                    else
                    {
                        unlink($data['full_path']);
                        $status = "error";
                        $msg = "Something went wrong when saving the file, please try again.";
                    }
                }
            }
            @unlink($_FILES[$file_element_name]);
        }
        echo json_encode(array('status' => $status, 'msg' => $msg));
    }
}

?>