<?php 
ob_start();
if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Backup (BackupController)
 * Backup Class to control all user related operations.
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
     * This function used to load the first screen of the backup
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Orion eSolutions : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the backup list
     */
   
    function backups($limit = NULL)
    {
        if($this->isMember() == TRUE)
        {
            $this->load->model('backup_model');
                   
            $this->load->library('pagination');
                    
            $count = $this->backup_model->memberbackupCount(null,null,$this->vendorId,null);
            $returns = $this->paginationCompress ( "backups/", $count, 5 );

            if(isset($_GET['search_backup'])!='Search')
            {
                $count = $this->backup_model->memberbackupCount($returns["page"], $returns["segment"],$this->vendorId);
                $returns = $this->paginationCompress ( "backups/", $count, 5 );
                $data['backupRecords'] = $this->backup_model->membersBackups( $returns["page"], $returns["segment"],$this->vendorId);
                $data['clients'] = $this->backup_model->getClients();
                $data['users'] = $this->backup_model->getUsers();
                $this->global['pageTitle'] = 'Orion eSolutions : Backup Listing';
              
               
                $this->loadViews("backups", $this->global, $data, NULL);
            }
            elseif(isset($_GET['search_backup'])=='Search')
            {
                $search_data['userId'] = $this->input->get('user');
                $search_data['clientId'] = $this->input->get('client');
                $search_data['serverId'] = $this->input->get('server');
                $search_data['scheduleType'] = $this->input->get('scheduleType');
                $search_data['scheduleTimings'] = $this->input->get('scheduleTimings');

                $count = $this->backup_model->memberbackupCount($returns["page"], $returns["segment"],$this->vendorId, $search_data);
                $returns = $this->paginationCompress ( "backups/", $count, 5 );
                $data['backupRecords'] = $this->backup_model->membersBackups($returns["page"], $returns["segment"],$this->vendorId,$search_data);
                $data['clients'] = $this->backup_model->getClients();
                $data['users'] = $this->backup_model->getUsers();
                $this->global['pageTitle'] = 'Orion eSolutions : Backup Listing';
               
                $this->loadViews("backups", $this->global, $data, NULL);
               
            }else{}
           
        }
        elseif($this->isAdmin() == TRUE)
        {
            $this->load->model('backup_model');
        
            
            $this->load->library('pagination');
            
            $count = $this->backup_model->backupListingCount(null,null,null);
            $returns = $this->paginationCompress ( "backups/", $count, 5 );
            if(isset($_GET['search_backup'])!='Search')
            {
                $data['backupRecords'] = $this->backup_model->backups( $returns["page"], $returns["segment"],null);
                $data['clients'] = $this->backup_model->getClients();
                $data['users'] = $this->backup_model->getUsers();
                $this->global['pageTitle'] = 'Orion eSolutions : Backup Listing';
                //print_r($data);
               // $data["links"] = $this->pagination->create_links();
                $this->loadViews("backups", $this->global, $data, NULL);
            }
            elseif(isset($_GET['search_backup'])=='Search')
            {
                $search_data['userId'] = $this->input->get('user');
                $search_data['clientId'] = $this->input->get('client');
                $search_data['serverId'] = $this->input->get('server');
                $search_data['scheduleType'] = $this->input->get('scheduleType');
                $search_data['scheduleTimings'] = $this->input->get('scheduleTimings');

                $count = $this->backup_model->backupListingCount(null,null, $search_data);
                $returns = $this->paginationCompress ( "backups/", $count, 5 );
                $data['backupRecords'] = $this->backup_model->backups( $returns["page"], $returns["segment"],$search_data);
                $data['clients'] = $this->backup_model->getClients();
                $data['users'] = $this->backup_model->getUsers();
                $this->global['pageTitle'] = 'Orion eSolutions : Backup Listing';
                //print_r($data);
                $this->loadViews("backups", $this->global, $data, NULL);
               
            }
        }
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
			
            $data['servers'] = $this->backup_model->getServerById($serverId);
            
            $this->global['pageTitle'] = 'Orion eSolutions : Backup Details';
          
            $this->loadViews("backupDetails", $this->global, $data, NULL);
        }
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
                foreach( $serverId as $ser)
                {
                    $backupInfo = array('userId'=>$userId,'clientId'=>$clientId,'serverId'=>$ser,
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
       
    }
    
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Orion eSolutions : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }

     /**
     * This function is used to add todays backup schedule
     */
    function scheduleBackups()
    {
        $this->load->model('backup_model');

        $cronInfo = array('type'=>'Create_Schedule','startTime'=>date('Y-m-d H:i:s'));
        $cronId = $this->backup_model->cronStartTime($cronInfo);

        $date = cal_to_jd(CAL_GREGORIAN,date("m"),date("d"),date("Y"));
        $this->current_day = (jddayofweek($date,1));
        $this->current_date = date("j");
        $this->current_fulldate = date('d-m-Y');
        $timing = array('day'=> $this->current_day,'date'=>$this->current_date);

        $result = $this->backup_model->getBackups( $timing);

        foreach($result as $res):
            $clientId =  $res->clientId;
            $backupId =  $res->id;
            $userId =  $res->userId;
            $status =  1;
            $scheduleType = $res->scheduleType;
            $scheduleTimings = $res->scheduleTimings;
            $serverName = $res->ServerName;
            $serverIP = $res->ServerIP;
            $serverHostname = $res->ServerHostname;
            $clientName = $res->ClientName;
            $userName = $res->UserName;
            $userEmail = $res->UserEmail;

            $scheduleEmailData = array('serverName'=>$serverName,'serverIP'=>$serverIP,
            'serverHostname'=>$serverHostname,'clientName'=>$clientName,
            'userName'=>$userName,'userEmail'=>$userEmail);

            $scheduleInfo = array('date'=>$this->current_fulldate,'clientId'=>$clientId,
            'backupId'=>$backupId,'userId'=>$userId,'status'=>$status,);
            $result = $this->backup_model->addBackupSchedule($scheduleInfo);
            if($result > 0)
            {
               echo   "Schedule successfully added";
            }
        endforeach;
        $curr_date = $this->current_fulldate = date('d-m-Y');
        $emails = $this->backup_model->getBackupsUserEmail( $curr_date);
        
        foreach($emails as $email):
            $userEmail = $email->UserEmail;
            $scheduleInfo = array('email'=>$userEmail,'date'=>$curr_date);
            $data = $this->backup_model->getTodaysBackup($scheduleInfo);

            $MailResult =  $this->sendEmailTodayBackup($data);
        endforeach; 
        if( $MailResult == True)
        {
            $cronInfo2 = array('endTime'=>date('Y-m-d H:i:s'));
            $result = $this->backup_model->cronEndTime($cronInfo2 , $cronId);
        }

        redirect("backups");
        
    }
    /**
     * This function is used to send mail to users for todays backup schedule
     */
    function sendEmailTodayBackup($data)
    {
        $this->load->library('email');
        $row = '';
      
        foreach($data as $record):
           $rec = (array)$record;
           $row_count= count($data);
           if($row_count > 10)
           {
               $row_view_more = '<tr>
                                    <td  colspan="5" style="padding: 10px 12px;">
                                    <a href="http://localhost:8080/server-m/schedules">
                                            View More
                                        </a>
                                    </td>
                                </tr>';
           }else{
                $row_view_more = '';
           }
           
            $row .= '<tr   style="border-bottom: 1px solid #e8e8e8;text-align:center;">
                        <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                        '.$rec['ServerName'].'
                        </td>
                        <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                        '.$rec['ServerIP'].'
                        </td>
                        <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                        '.$rec['ServerHostname'].'
                        </td>
                        <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                        '.$rec['ClientName'].'
                        </td>
                        <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;"><a href="http://localhost:8080/server-m/schedule-details/'.$rec['id'].'">
                                 View
                            </a>
                        </td>
                   </tr>';
            $rows=  $row . $row_view_more;
            $subject = 'Today'."'".'s backups list('. date("d-m-Y").')';
            $message = '<table style="border:1px solid #e8e8e8;border-spacing:0;width:100%;">
                            <tr style="background:#D1F2EB;  border-bottom: 1px solid #e8e8e8;">
                                <th   style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                    Server
                                </th>
                                <th  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                    Server IP
                                </th>
                                <th  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                    Hostname
                                </th>
                                <th  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                    Client
                                </th>
                                <th  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                    Options
                                </th>
                            </tr>
                                '.$rows.'
                        </table>';
        endforeach;
        // Get full html:
        echo $body = '
                <div style="text-align:center;"><img src="'. base_url() .'assets/dist/img/logo.png" alt="" /></div>
                <p>Hi '.$rec["UserName"].'</p>
                <p>Your Today'."'".'s backups</p> 
                '.$message.'
                ';
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        
        $result = $this->email
            ->from('gurinderjeetkaur01@gmail.com','Orion Esolutions')
            // ->reply_to('')    // Optional, an account where a human being reads.
            ->to($rec["UserEmail"])
            ->subject($subject)
            ->message($body)
            ->send();
        if($result == TRUE)
        {
            $mailLogInfo = array('email_to'=>$rec["UserEmail"],'email_from'=>"gurinderjeetkaur01@gmail.com",
            'email_subject'=>$subject ,'email_body'=>$body,'type_email'=>"daily_backup_notification");
            $res = $this->backup_model->addMailLog($mailLogInfo);
        }
        var_dump($result);
        echo '<br />';
        echo $this->email->print_debugger();
        return $result;
    }
    
     /**
     * This function is used to send mail for pending backup schedule 
     */
    function pendingScheduleBackups()
    {
        $this->load->model('backup_model');

        $cronInfo = array('type'=>'Send_Pending_Mail','startTime'=>date('Y-m-d H:i:s'));
            
        $cronId = $this->backup_model->cronStartTime($cronInfo);
       
        $date = cal_to_jd(CAL_GREGORIAN,date("m"),date("d"),date("Y"));
        $this->current_day = (jddayofweek($date,1));
        $this->current_date = date("j");
        
        $this->current_fulldate = date('d-m-Y');
        
        $count = 1;
        $this->checkBackupId ='';
        // get all users of todays pending backup 
        $users = $this->backup_model->getBackupsUserEmail( $this->current_fulldate);
       
        foreach($users as $user):
            $UserId = $user->UserId;
            $UserEmail = $user->UserEmail;
            $UserName = $user->UserName;
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
            
            foreach($result as $backups)
            {
                $scheduleId = $backups->scheduleId;
                $backupId = $backups->id;
                $backupDaily1 = array();
                $backupWeekly1 = array();
                $backupMonthly1 = array();
                if( $backups->scheduleType == "Daily")
                { 
                    for($d = 1; $d <= 10; $d++ )
                    {
                        $this->d1 =  date('d-m-Y',strtotime("-$d days"));
                        $timing = array(
                            'fullDate'=>$this->d1,
                            'user'=>$UserId,
                            'backupId'=>$backupId,
                            'daily' => "daily"
                        );
                        // check each backup was pending last time or not
                        $res = $this->backup_model->getPendingBackups($timing);
                        if(!empty($res))
                        {   
                            $count++;
                            foreach($res as $r)
                            {
                                if(!($this->checkBackupId == $r->backupId ))
                                {
                                    // create array of each pending tasks of user
                                    $backupDaily1 = array(
                                        'scheduleId' => $scheduleId,
                                        'backupId'=>$backupId,
                                        'user'=>$r->UserName,
                                        'server'=>$r->ServerName,
                                        'type'=> $r->scheduleType,
                                        'timings'=>$r->scheduleTimings,
                                        'userName'=>$UserName
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
                    for($d = 1; $d <= 10; $d++ )
                    {
                        $this->day =  date('d-m-Y',strtotime("-$d week"));echo "<br>";
                        $timing = array(
                            'fullDate'=> $this->day,
                            'user'=>$UserId,
                            'backupId'=>$backupId,
                            'daily' => ''
                        );
                        // check each backup was pending last time or not
                        $res = $this->backup_model->getPendingBackups($timing);
                      
                        if(!empty($res))
                        {
                            $count++;
                            var_dump($res);
                            foreach($res as $r)
                            { 
                                if($this->checkBackupId != $r->backupId )
                                {
                                    // create array of each pending tasks of user
                                    $backupWeekly1 = array(
                                        'scheduleId' => $scheduleId,
                                        'backupId'=>$backupId,
                                        'user'=>$r->UserName,
                                        'server'=>$r->ServerName,
                                        'type'=> $r->scheduleType,
                                        'timings'=>$r->scheduleTimings,
                                        'userName'=>$UserName
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
                    for($d = 1; $d <= 10; $d++ )
                    {
                        $this->monthDate =  date('d-m-Y',strtotime("-$d month"));
                      
                        $timing = array(
                            'fullDate'=>$this->monthDate,
                            'user'=>$UserId,
                            'backupId'=>$backupId,
                            'daily' => ''
                        );
                        // check each backup was pending last time or not
                        $res = $this->backup_model->getPendingBackups($timing);
                        if(!empty($res))
                        {
                            $count++;
                            foreach($res as $r)
                            {
                                if($this->checkBackupId != $r->backupId )
                                {
                                    // create array of each pending tasks of user
                                    $backupMonthly1 = array(
                                        'scheduleId' => $scheduleId,
                                        'backupId'=>$backupId,
                                        'user'=>$r->UserName,
                                        'server'=>$r->ServerName,
                                        'type'=> $r->scheduleType,
                                        'timings'=>$r->scheduleTimings,
                                        'userName'=>$UserName
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
            } 
            if($backupMonthly1 != null)
            {
                $backupMonthly2 =array('count' => $count);
                $backupData[] = array_merge($backupMonthly1, $backupMonthly2);
                unset($backupMonthly1);
            }
            $count=1;
            $data['backupInfo'] =  $backupData;
           
            foreach( $backupData as $d)
            { 
                $adminData[] =   $d;
            } 
            // empty the array of pending backups of users to store another users backups
            unset($backupData);
            
            $data['userInfo'] =  array('userId'=>$UserId, 'userEmail'=>$UserEmail, 'userName'=>$UserName);

            // call to function to send mail to user for their pending backup
           $userMailResult = $this->sendEmailPendingBackupUser($data);
         
        endforeach;
        if($userMailResult == TRUE )
        {
            // call to function to send mail to admin for pending backup list
            echo $adminMailResult = $this->sendEmailPendingBackupAdmin($adminData);
        }
        if( $adminMailResult == True)
        {
            $cronInfo2 = array('endTime'=>date('Y-m-d H:i:s'));
            $result = $this->backup_model->cronEndTime($cronInfo2 , $cronId);
        }
       
     redirect("backups");
    }

    /**
     * This function is used to send mail to user for pending backup schedule
     */
    function sendEmailPendingBackupUser($data)
    {
        $this->load->library('email');
        $row = '';
        $message = '';
        $this->userName = '';
        $this->messageAdmin  = '';
       
        if(!empty($data['backupInfo']))
            {
                foreach($data['backupInfo'] as $pendingBackup)
                {
                    if($pendingBackup['count'] >= 3)
                    {
                    $row .= '<tr   style="border-bottom: 1px solid #e8e8e8;text-align:center;">
                                <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                '.$pendingBackup['server'].'
                                </td>
                                <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                '.$pendingBackup['count'].'
                                </td>
                                <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                '.$pendingBackup['type'].'/'.$pendingBackup['timings'].'
                                </td>
                                <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;"><a href="http://localhost:8080/server-m/schedule-details/'. $pendingBackup['scheduleId'].'">
                                    View
                                        </a>
                                </td>
                            </tr>';    
                    $row_count= count($row);
                    if($row_count > 5)
                    {
                        $row_view_more = '<tr>
                                                <td  colspan="4" style="padding: 10px 12px;">
                                                <a href="http://localhost:8080/server-m/schedules">
                                                        View More
                                                    </a>
                                                </td>
                                            </tr>';
                    }else{
                            $row_view_more = '';
                    }
                    $rows=  $row . $row_view_more;
                    $message = '<table style="border:1px solid #e8e8e8;border-spacing:0;width:100%;">
                                <tr style="background:#D1F2EB;  border-bottom: 1px solid #e8e8e8;">
                                    <th   style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                        Server
                                    </th>
                                    <th  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                        Time
                                    </th>
                                    <th  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                        Type
                                    </th>
                                    <th  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                        Options
                                    </th>
                                </tr>
                                    '.$rows.'
                            </table>';
                    }
                }
            }
            // Get full html:
            if(!empty($data['userInfo']))
            {
                $toEmail = $data['userInfo']['userEmail'];
                $this->userName = $data['userInfo']['userName'];;
               
                $subject = 'Pending backups list('. date("d-m-Y").')';
                 $body = '
                    <div style="text-align:center;"><img src="'. base_url() .'assets/dist/img/logo.png" alt="" /></div>
                    <p>Hi '.$pendingBackup["user"].'</p>
                    <p>You are not checking your Backups</p> 
                    '.$message.'
                    ';
               
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            
            $result = $this->email
                ->from('gurinderjeetkaur01@gmail.com','Orion eSolutions')
                // ->reply_to('')    // Optional, an account where a human being reads.
                ->to($toEmail)
                ->subject($subject)
                ->message($body)
                ->send();
            if($result== TRUE)
            {
                $mailLogInfo = array('email_to'=>$toEmail,'email_from'=>"gurinderjeetkaur01@gmail.com",
                'email_subject'=>$subject ,'email_body'=>$body,'type_email'=>"Pending_backup_user_mail");
                $res = $this->backup_model->addMailLog($mailLogInfo);
            }
            var_dump($result);
            echo '<br />';
            echo $this->email->print_debugger();
        }
      return $result;
    }
    /**
     * This function is used to send mail to admin for pending backup schedule
     */
    function sendEmailPendingBackupAdmin($data)
    {
        $this->load->library('email');
        $row = '';
        $rowelse ='';
        $message = '';
        $this->userName = '';
        $this->messageAdmin  = '';
        $msg = '';
        if(!empty($data))
        {
            $username = null;
            foreach($data as $pendingBackup)
            {  
                if($pendingBackup['count'] >= 2) 
                {
                    if($username != $pendingBackup['user'])
                    { 
                        $row = '';
                        $row .= '<table style="width:100%;"><tr><td><p>'.$pendingBackup["user"].'</p><table style="border:1px solid #e8e8e8;border-spacing:0;width:100%;">
                        <tr style="background:#D1F2EB;  border-bottom: 1px solid #e8e8e8;">
                            <th   style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                Server
                            </th>
                            <th  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                Time
                            </th>
                            <th  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                Type
                            </th>
                            <th  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                Options
                            </th>
                        </tr>
                        <tr   style="border-bottom: 1px solid #e8e8e8;text-align:center;">
                                <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                '.$pendingBackup['server'].'
                                </td>
                                <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                '.$pendingBackup['count'].'
                                </td>
                                <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                '.$pendingBackup['type'].'/'.$pendingBackup['timings'].'
                                </td>
                                <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;"><a href="http://localhost:8080/server-m/schedule-details/'. $pendingBackup['scheduleId'].'">
                                    View
                                        </a>
                                </td>
                            </tr>';    
                        $username = $pendingBackup['user'];
                        $rowelse .= $row; 
                    }
                    else
                    {
                         $rowelse .= '<tr   style="border-bottom: 1px solid #e8e8e8;text-align:center;">
                            <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                            '.$pendingBackup['server'].'
                            </td>
                            <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                            '.$pendingBackup['count'].'
                            </td>
                            <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                            '.$pendingBackup['type'].'/'.$pendingBackup['timings'].'
                            </td>
                            <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;"><a href="http://localhost:8080/server-m/schedule-details/'. $pendingBackup['scheduleId'].'">
                                View
                                    </a>
                            </td>
                        </tr>';  
                    }
                }
            }
            if($rowelse != '')
            {
                $row = $rowelse;
            }
            $row_count= count($row);
            if($row_count > 5)
            {
                $row_view_more = '<tr>
                                    <td  colspan="4" style="padding: 10px 12px;">
                                        <a href="http://localhost:8080/server-m/schedules">
                                            View More
                                        </a>
                                    </td>
                                 </tr>';
            }else{
                $row_view_more = '';
            }
            $rows=  $row . $row_view_more;
            $msg .= $rowelse;
                
        }
        $full_message = $msg;

        if(!empty($data['user']))
        {
            foreach($data['user'] as $user)
            {
            $toEmail = $user;
            $this->userName = $user;
            } 
        }
        // get mail of admin
         $adminMail = $this->backup_model->getAdminEmail();
         foreach($adminMail As $email)
         {
           $adminEmail = $email->email;
         }
     
           // admin mail for user's pending backup
           $subject = 'Pending Backups list of users';
           echo $adminMailBody = '
               <div style="text-align:center;"><img src="'. base_url() .'assets/dist/img/logo.png" alt="" /></div>
               <p>Hi Admin</p>
               <p>Pending Backups list </p> 
               '.$full_message.'
               ';
       
           $config['mailtype'] = 'html';
           $this->email->initialize($config);

           $result = $this->email
               ->from('gurinderjeetkaur01@gmail.com','Orion eSolutions')
               // ->reply_to('')    // Optional, an account where a human being reads.
               ->to($adminEmail)
               ->subject($subject)
               ->message($adminMailBody)
               ->send();
           if($result== TRUE)
           {
               $mailLogInfo = array('email_to'=>$adminEmail,'email_from'=>"gurinderjeetkaur01@gmail.com",
               'email_subject'=>$subject ,'email_body'=>$adminMailBody,'type_email'=>"Pending_backup_admin_mail");
               $res = $this->backup_model->addMailLog($mailLogInfo);
           }
           var_dump($result);
           echo '<br />';
           echo $this->email->print_debugger();
       return $result;
    }
}
ob_flush();
?>