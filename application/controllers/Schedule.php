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
        $this->load->model('schedule_model');
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
            $this->load->model('backup_model');
                   
            $this->load->library('pagination');
            $current_date = date('d-m-Y');    
            $count = $this->schedule_model->schedulesCount(null,null,null, $current_date,$this->vendorId);
            $returns = $this->paginationCompress ( "schedules/", $count, 5 );
            $assigneeId = $this->schedule_model->getAssigneeId($this->vendorId);
            $userIds[] = $this->vendorId;
            if(!empty($assigneeId))
            {
                foreach($assigneeId as $id)
                {
                    $userIds[] = $id['userId'];
                }
            }
            if(isset($_GET['search_backup'])!='Search')
            {
                foreach($userIds as $user)
                { 
                    $count = $this->schedule_model->schedulesCount($returns["page"], $returns["segment"],NULL, $current_date,  $user);
                    $returns = $this->paginationCompress ( "schedules/", $count, 5 );
                    $data1 = $this->schedule_model->schedules( $returns["page"], $returns["segment"],NULL,$current_date, $user);
                   
                    foreach($data1 as $d)
                    {
                    $data['scheduleRecords'][] = $d;
                    }
                }
                $date = cal_to_jd(CAL_GREGORIAN,date("m"),date("d"),date("Y"));
                $this->current_day = (jddayofweek($date,1));
                $this->current_date = date("j");
                
                $this->current_fulldate = date('d-m-Y');
                $this->checkBackupId ='';
                // get all users of todays pending backup 
                
                foreach($userIds as $user):
                
                    $count = $this->schedule_model->schedulesCount($returns["page"], $returns["segment"],NULL, $current_date,  $user);
                    $returns = $this->paginationCompress ( "schedules/", $count, 5 );
                    $data['scheduleRecords'] = $this->schedule_model->schedules( $returns["page"], $returns["segment"],NULL,$current_date,  $user);
               
                    $UserId = $user;
                    $scheduleInfo = array(
                        'fullDate'=>$this->current_fulldate,
                        'user'=>$UserId,
                        'backupId'=>'' ,
                        'daily' => '',
                        'day'=> '',
                        'date' => ''
                    );
                    // get all todays pending backups of user 
                    $result = $this->backup_model->getPendingBackups($scheduleInfo);
                    $backupData = array();
                    foreach($result as $backups)
                    {
                        $scheduleId = $backups->scheduleId;
                        $backupId = $backups->id;
                        
                        $backupDaily1 = array();
                        $backupWeekly1 = array();
                        $backupMonthly1 = array();
                        if( $backups->scheduleType == "Daily")
                        { 
                            for($d = 1; $d <= 1; $d++ )
                            {
                                $this->d1 =  date('d-m-Y',strtotime("-$d days"));
                                $timing = array(
                                    'fullDate'=>$this->d1,
                                    'user'=>$UserId,
                                    'backupId'=>$backupId,
                                    'daily' => "daily"
                                ); 
                                // check each backup was pending last time or not
                                $res = $this->schedule_model->getPendingBackups($timing);
                               
                                if(!empty($res))
                                {   
                                    $count++;
                                    foreach($res as $r)
                                    {
                                        if(!($this->checkBackupId == $r->backupId ))
                                        {
                                            // create array of each pending tasks of user
                                            $backupDaily1 = array(
                                                'scheduleId' =>$r->scheduleId,
                                                'backupId'=>$backupId,
                                                'user'=>$r->UserName,
                                                'server'=>$r->ServerName,
                                                'serverIP'=> $r->ServerIP,
                                                'hostname'=>$r->ServerHostname,
                                                'type'=> $r->scheduleType,
                                                'timings'=>$r->scheduleTimings,
                                                'clientName'=>$r->ClientName,
                                                'status'=>$r->ScheduleStatus
                                            );
                                        }  
                                        $this->checkBackupId = $r->backupId;
                                    } 
                                }
                                else{
                                    break;
                                }
                            }
                        } 
                        if($backupDaily1 != null)
                        {
                            $backupDaily2 =array('count' => $count);
                            $backupData[] = array_merge($backupDaily1, $backupDaily2);
                            unset($backupDaily1);
                        } 
                        $count= 1;
                        if( $backups->scheduleType == "Weekly")
                        {
                            for($d = 1; $d <= 1; $d++ )
                            {
                                $this->day =  date('d-m-Y',strtotime("-$d week"));echo "<br>";
                                $timing = array(
                                    'fullDate'=> $this->day,
                                    'user'=>$UserId,
                                    'backupId'=>$backupId,
                                    'daily' => ''
                                );
                                // check each backup was pending last time or not
                                $res = $this->schedule_model->getPendingBackups($timing);
                            
                                if(!empty($res))
                                {
                                    $count++;
                                    foreach($res as $r)
                                    { 
                                        if($this->checkBackupId != $r->backupId )
                                        {
                                            // create array of each pending tasks of user
                                            $backupWeekly1 = array(
                                                'scheduleId' => $r->scheduleId,
                                                'backupId'=>$backupId,
                                                'user'=>$r->UserName,
                                                'server'=>$r->ServerName,
                                                'serverIP'=> $r->ServerIP,
                                                'hostname'=>$r->ServerHostname,
                                                'type'=> $r->scheduleType,
                                                'timings'=>$r->scheduleTimings,
                                                'clientName'=>$r->ClientName,
                                                'status'=>$r->ScheduleStatus
                                            );
                                        }
                                        $this->checkBackupId = $r->backupId;
                                    } 
                                }
                                else{
                                    break;
                                }
                            }
                        } 
                        if($backupWeekly1 != null)
                        {
                            $backupWeekly2 =array('count' => $count);
                            $backupData[] = array_merge($backupWeekly1, $backupWeekly2);
                            unset($backupWeekly1);
                        }
                        $count=1;
                        if( $backups->scheduleType == "Monthly")
                        {
                            for($d = 1; $d <= 1; $d++ )
                            {
                                $this->monthDate =  date('d-m-Y',strtotime("-$d month"));
                            
                                $timing = array(
                                    'fullDate'=>$this->monthDate,
                                    'user'=>$UserId,
                                    'backupId'=>$backupId,
                                    'daily' => ''
                                );
                                // check each backup was pending last time or not
                                $res = $this->schedule_model->getPendingBackups($timing);
                                if(!empty($res))
                                {
                                    $count++;
                                    foreach($res as $r)
                                    {
                                        if($this->checkBackupId != $r->backupId )
                                        {
                                            // create array of each pending tasks of user
                                            $backupMonthly1 = array(
                                                'scheduleId' =>$r->scheduleId,
                                                'backupId'=>$backupId,
                                                'user'=>$r->UserName,
                                                'server'=>$r->ServerName,
                                                'serverIP'=> $r->ServerIP,
                                                'hostname'=>$r->ServerHostname,
                                                'type'=> $r->scheduleType,
                                                'timings'=>$r->scheduleTimings,
                                                'clientName'=>$r->ClientName,
                                                'status'=>$r->ScheduleStatus
                                            );
                                        }
                                        $this->checkBackupId = $r->backupId;
                                    } 
                                }
                                else{
                                    break;
                                }
                            }
                        }
                    if($backupMonthly1 != null)
                    {
                        $backupMonthly2 =array('count' => $count);
                        $backupData[] = array_merge($backupMonthly1, $backupMonthly2);
                        unset($backupMonthly1);
                    }
                } 
                $count=1;
                $data['backupInfo'] =  $backupData;
                    
                endforeach;
                $data['clients'] = $this->schedule_model->getClients();
                $data['servers'] = $this->schedule_model->getServers();
                $this->global['pageTitle'] = 'Orion eSolutions : Schedules Listing';
                
                $this->loadViews("schedules", $this->global, $data, NULL);
            }
            elseif(isset($_GET['search_backup'])=='Search')
            {
                $search_data['serverId'] = $this->input->get('server');
                $search_data['serverIP'] = $this->input->get('serverIP');
                $search_data['hostname'] = $this->input->get('hostname');
                $search_data['clientId'] = $this->input->get('client');
                $search_data['status'] = $this->input->get('status');
               
                $date = cal_to_jd(CAL_GREGORIAN,date("m"),date("d"),date("Y"));
                $this->current_day = (jddayofweek($date,1));
                $this->current_date = date("j");
                
                $this->current_fulldate = date('d-m-Y');
                $this->checkBackupId ='';
                // get all users of todays pending backup 
                
                foreach($userIds as $user):
                
                    $count = $this->schedule_model->schedulesCount($returns["page"], $returns["segment"],$search_data, $current_date,  $user);
                    $returns = $this->paginationCompress ( "schedules/", $count, 5 );
                    $data['scheduleRecords'] = $this->schedule_model->schedules( $returns["page"], $returns["segment"],$search_data,$current_date,  $user);
               
                    $UserId = $user;
                    $scheduleInfo = array(
                        'fullDate'=>$this->current_fulldate,
                        'user'=>$UserId,
                        'backupId'=>'' ,
                        'daily' => '',
                        'day'=> '',
                        'date' => ''
                    );
                    // get all todays pending backups of user 
                    $result = $this->backup_model->getPendingBackups($scheduleInfo);
                    $backupData = array();
                    foreach($result as $backups)
                    {
                        $scheduleId = $backups->scheduleId;
                        $backupId = $backups->id;
                        
                        $backupDaily1 = array();
                        $backupWeekly1 = array();
                        $backupMonthly1 = array();
                        if( $backups->scheduleType == "Daily")
                        { 
                            for($d = 1; $d <= 1; $d++ )
                            {
                                $this->d1 =  date('d-m-Y',strtotime("-$d days"));
                                $timing = array(
                                    'fullDate'=>$this->d1,
                                    'user'=>$UserId,
                                    'backupId'=>$backupId,
                                    'daily' => "daily"
                                ); 
                                // check each backup was pending last time or not
                                $res = $this->schedule_model->getPendingBackups($timing);
                               
                                if(!empty($res))
                                {   
                                    $count++;
                                    foreach($res as $r)
                                    {
                                        if(!($this->checkBackupId == $r->backupId ))
                                        {
                                            // create array of each pending tasks of user
                                            $backupDaily1 = array(
                                                'scheduleId' =>$r->scheduleId,
                                                'backupId'=>$backupId,
                                                'user'=>$r->UserName,
                                                'server'=>$r->ServerName,
                                                'serverIP'=> $r->ServerIP,
                                                'hostname'=>$r->ServerHostname,
                                                'type'=> $r->scheduleType,
                                                'timings'=>$r->scheduleTimings,
                                                'clientName'=>$r->ClientName,
                                                'status'=>$r->ScheduleStatus
                                            );
                                        }  
                                        $this->checkBackupId = $r->backupId;
                                    } 
                                }
                                else{
                                    break;
                                }
                            }
                        } 
                        if($backupDaily1 != null)
                        {
                            $backupDaily2 =array('count' => $count);
                            $backupData[] = array_merge($backupDaily1, $backupDaily2);
                            unset($backupDaily1);
                        } 
                        $count= 1;
                        if( $backups->scheduleType == "Weekly")
                        {
                            for($d = 1; $d <= 1; $d++ )
                            {
                                $this->day =  date('d-m-Y',strtotime("-$d week"));echo "<br>";
                                $timing = array(
                                    'fullDate'=> $this->day,
                                    'user'=>$UserId,
                                    'backupId'=>$backupId,
                                    'daily' => ''
                                );
                                // check each backup was pending last time or not
                                $res = $this->schedule_model->getPendingBackups($timing);
                            
                                if(!empty($res))
                                {
                                    $count++;
                                    foreach($res as $r)
                                    { 
                                        if($this->checkBackupId != $r->backupId )
                                        {
                                            // create array of each pending tasks of user
                                            $backupWeekly1 = array(
                                                'scheduleId' => $r->scheduleId,
                                                'backupId'=>$backupId,
                                                'user'=>$r->UserName,
                                                'server'=>$r->ServerName,
                                                'serverIP'=> $r->ServerIP,
                                                'hostname'=>$r->ServerHostname,
                                                'type'=> $r->scheduleType,
                                                'timings'=>$r->scheduleTimings,
                                                'clientName'=>$r->ClientName,
                                                'status'=>$r->ScheduleStatus
                                            );
                                        }
                                        $this->checkBackupId = $r->backupId;
                                    } 
                                }
                                else{
                                    break;
                                }
                            }
                        } 
                        if($backupWeekly1 != null)
                        {
                            $backupWeekly2 =array('count' => $count);
                            $backupData[] = array_merge($backupWeekly1, $backupWeekly2);
                            unset($backupWeekly1);
                        }
                        $count=1;
                        if( $backups->scheduleType == "Monthly")
                        {
                            for($d = 1; $d <= 1; $d++ )
                            {
                                $this->monthDate =  date('d-m-Y',strtotime("-$d month"));
                            
                                $timing = array(
                                    'fullDate'=>$this->monthDate,
                                    'user'=>$UserId,
                                    'backupId'=>$backupId,
                                    'daily' => ''
                                );
                                // check each backup was pending last time or not
                                $res = $this->schedule_model->getPendingBackups($timing);
                                if(!empty($res))
                                {
                                    $count++;
                                    foreach($res as $r)
                                    {
                                        if($this->checkBackupId != $r->backupId )
                                        {
                                            // create array of each pending tasks of user
                                            $backupMonthly1 = array(
                                                'scheduleId' =>$r->scheduleId,
                                                'backupId'=>$backupId,
                                                'user'=>$r->UserName,
                                                'server'=>$r->ServerName,
                                                'serverIP'=> $r->ServerIP,
                                                'hostname'=>$r->ServerHostname,
                                                'type'=> $r->scheduleType,
                                                'timings'=>$r->scheduleTimings,
                                                'clientName'=>$r->ClientName,
                                                'status'=>$r->ScheduleStatus
                                            );
                                        }
                                        $this->checkBackupId = $r->backupId;
                                    } 
                                }
                                else{
                                    break;
                                }
                            }
                        }
                    if($backupMonthly1 != null)
                    {
                        $backupMonthly2 =array('count' => $count);
                        $backupData[] = array_merge($backupMonthly1, $backupMonthly2);
                        unset($backupMonthly1);
                    }
                } 
                $count=1;
                $data['backupInfo'] =  $backupData;
                    
                endforeach;
                
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
            $oldId = "";
            //var_dump($data['commentInfo']);
            foreach($data['commentInfo'] as $ds)
            {
                $id = $ds->id;
                $scheduleId = $ds->scheduleId;
                $userComment = $ds->userComment;
                $data['info'][] = array("id"=>$id, "scheduleId"=>$scheduleId, "userComment"=>$userComment);
                
                if($oldId != $id)
                {
                    $data['attachment'][] = $this->schedule_model->getAttachmentsInfo($id);
                    
                    $oldId = $ds->id;
                }
            }
          
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
       
            $scheduleId = $this->input->post('scheduleId');
            $statusId = $this->input->post('statusId');
            $comment = $this->input->post('comment');
            $scheduleInfo = array();
					
            $scheduleInfo = array('status'=>$statusId,'updatedBy'=>$this->vendorId,
            'updatedDtm'=>date('Y-m-d H:i:s'));
                   
		    $result = $this->schedule_model->updateScheduleStatus($scheduleInfo, $scheduleId);

            $commentInfo = array('scheduleId'=>$scheduleId,'userId'=>$this->vendorId,
            'statusId'=>$statusId,'userComment'=>$comment,'createdDtm'=>date('Y-m-d H:i:s'));

            $comment_id = $this->schedule_model->addComment($commentInfo);
        if(!empty($_FILES['attachment']))
        {
            $filesCount = count($_FILES['attachment']['name']);
            for($i = 0; $i < $filesCount; $i++)
            {
                $_FILES['file']['name']     = $_FILES['attachment']['name'][$i];
                $_FILES['file']['type']     = $_FILES['attachment']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
                $_FILES['file']['error']     = $_FILES['attachment']['error'][$i];
                $_FILES['file']['size']     = $_FILES['attachment']['size'][$i];

                $path = realpath(APPPATH . '../assets/files');
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'gif|jpg|png|doc|txt';
                $config['max_size'] = 1024 * 8;
                $config['encrypt_name'] = TRUE;
     
                 $this->load->library('upload', $config);
     
                if (!$this->upload->do_upload('file'))
                {
                    $status = 'error';
                    $msg = $this->upload->display_errors('', '');
                }
                else
                {
                    $data = $this->upload->data();
                   
                    $attachmentInfo = array('scheduleId'=>$scheduleId,'commentId'=>$comment_id,
                    'filePath'=>$data['file_name'], 'createdDtm'=>date('Y-m-d H:i:s'));
                    $res = $this->schedule_model->addAttachment( $attachmentInfo);

                    if($comment_id>0)
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
        }
        if(empty($_FILES['attachment']['name']))
        {
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