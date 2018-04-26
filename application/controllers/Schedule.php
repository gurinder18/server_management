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
                $count = $this->schedule_model->schedulesCount($returns["page"], $returns["segment"],null, $current_date,$this->vendorId);
                $returns = $this->paginationCompress ( "schedules/", $count, 5 );
                $data['scheduleRecords'] = $this->schedule_model->schedules( $returns["page"], $returns["segment"],null,$current_date,$this->vendorId);
                $data['clients'] = $this->schedule_model->getClients();
                $data['servers'] = $this->schedule_model->getServers();
                $this->global['pageTitle'] = 'Orion eSolutions : Schedules Listing';
                
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
            $scheduleId = $data['scheduleInfo'];
          
            $data['commentInfo'] = $this->schedule_model->getCommentInfo( $scheduleId['id']);
            //var_dump($data['commentInfo']);
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

        if(isset($_POST['backup_status'])=='Submit')
        {
                $this->load->model('schedule_model');
                $this->load->library('form_validation');
				$id = $this->input->post('status_scheduleId');
				
				$this->form_validation->set_rules('backupStatus','BackupStatus','trim|required|numeric');
				
				if($this->form_validation->run() == FALSE)
				{
                    unset($_POST['backup_status']);
					$this->updateScheduleStatus($id);
				}
				else
				{
                    $id = $this->input->post('status_scheduleId');
                    $status = $this->input->post('backupStatus');
                    
					$scheduleInfo = array();
					
                    $scheduleInfo = array('status'=>$status,'updatedBy'=>$this->vendorId,
                     'updatedDtm'=>date('Y-m-d H:i:s'));
                   
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
   
    function getServers($clientId)
    {
        $data['servers'] = $this->backup_model->getServers($clientId);
        echo json_encode($data);
    }

    function addComment()
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

                if(!empty($_FILES['attachment']['name']))
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
               
            }
            @unlink($_FILES[$file_element_name]);
        }
         if(empty($_FILES['attachment']['name']))
        {
           // die("hi");
           $scheduleId = $this->input->post('scheduleId');
           $statusId = $this->input->post('statusId');
           $comment = $this->input->post('comment');

           $commentInfo = array('scheduleId'=>$scheduleId,'userId'=>$this->vendorId,'statusId'=>$statusId,
           'userComment'=>$comment,'createdDtm'=>date('Y-m-d H:i:s'));

            $file_id = $this->schedule_model->addComment($commentInfo);
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
        redirect('schedule-details/'.$scheduleId);
        //echo json_encode(array('status' => $status, 'msg' => $msg)); 
    }
}
 
?>