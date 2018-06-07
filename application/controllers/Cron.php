<?php 
ob_start();
if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Server (ServerController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Cron extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
    }
  
    /**
     * This function used to load the first screen of the server
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Orion eSolutions : Dashboard';
        
       $this->loadViews("test", $this->global, NULL, NULL);
        
    }
    
     /**
     * This function is used to add todays backup schedule
     */
    function scheduleBackups()
    {
        $this->load->model('backup_model');
        date_default_timezone_set('Asia/Kolkata');
        $cronInfo = array('type'=>'Create_Schedule','startTime'=>date('Y-m-d H:i:s'));
        $cronId = $this->backup_model->cronStartTime($cronInfo);
        date_default_timezone_set('Asia/Kolkata');
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
                                    <a href="'. base_url() .'schedules">
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
                        <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                            <a href="'. base_url() .'schedule-details/'.$rec['id'].'">
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
        // $config['mailtype'] = 'html';
        // $this->email->initialize($config);
        
        // $result = $this->email
        //     ->from('gurinderjeetkaur01@gmail.com','Orion Esolutions')
        //     // ->reply_to('')    // Optional, an account where a human being reads.
        //     ->to($rec["UserEmail"])
        //     ->subject($subject)
        //     ->message($body)
        //     ->send();
        
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <webmaster@example.com>' . "\r\n";
        $headers .= 'Cc: myboss@example.com' . "\r\n";
        $result = mail($rec["UserEmail"],$subject,$body,$headers);
        if($result == TRUE)
        {
            $mailLogInfo = array('email_to'=>$rec["UserEmail"],'email_from'=>"webmaster@example.com",
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
        date_default_timezone_set('Asia/Kolkata');
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
        $adminData= array();
         $adminMailResult = NULL;
         $userMailResult = array();
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
                $backupData = array();
                
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
                                if(($this->checkBackupId != $r->backupId ))
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
                    
                    $data['backupData'][] = array_merge($backupDaily1, $backupDaily2);
                    unset($backupDaily1);
                   
                } 
                $count= 1;
                if( $backups->scheduleType == "Weekly")
                {
                    for($d = 1; $d <= 10; $d++ )
                    {
                        $this->day =  date('d-m-Y',strtotime("-$d week"));
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
                    $data['backupData'][] = array_merge($backupWeekly1, $backupWeekly2);
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
                if($backupMonthly1 != null)
                {
                    $backupMonthly2 =array('count' => $count);
                    $data['backupData'][] = array_merge($backupMonthly1, $backupMonthly2);
                    unset($backupMonthly1);
                }
                $count=1;
            }
            $data['backupInfo'] =   $data['backupData'];
          
            foreach( $data['backupData'] as $d)
            { 
                $adminData[] =   $d;
            } 
            // empty the array of pending backups of users to store another users backups
            unset( $data['backupData']);
            
            $data['userInfo'] =  array('userId'=>$UserId, 'userEmail'=>$UserEmail, 'userName'=>$UserName);

            // call to function to send mail to user for their pending backup
           $userMailResult[] = $this->sendEmailPendingBackupUser($data);
         
        endforeach; 
        if(!empty($userMailResult) )
        {
            // call to function to send mail to admin for pending backup list
            $adminMailResult = $this->sendEmailPendingBackupAdmin($adminData);
        }
        if( $adminMailResult != NULL)
        {
            $cronInfo2 = array('endTime'=>date('Y-m-d H:i:s'));
            $result = $this->backup_model->cronEndTime($cronInfo2 , $cronId);
        }
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
                    if($pendingBackup['count'] >= 2)
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
                                <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;"><a href="'. base_url() .'schedule-details/'. $pendingBackup['scheduleId'].'">
                                    View
                                        </a>
                                </td>
                            </tr>';    
                    $row_count= count($row);
                    if($row_count > 5)
                    {
                        $row_view_more = '<tr>
                                                <td  colspan="4" style="padding: 10px 12px;">
                                                <a href="'. base_url() .'schedules">
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
                                         No. of times
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
            
            // $result = $this->email
            //     ->from('gurinderjeetkaur01@gmail.com','Orion eSolutions')
            //     // ->reply_to('')    // Optional, an account where a human being reads.
            //     ->to($toEmail)
            //     ->subject($subject)
            //     ->message($body)
            //     ->send();
                
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
            // More headers
            $headers .= 'From: <webmaster@example.com>' . "\r\n";
            $headers .= 'Cc: myboss@example.com' . "\r\n";
            if($message != "")
            {
                $result = mail($toEmail,$subject,$body,$headers);
            
                if($result == 1)
                {
                    $mailLogInfo = array('email_to'=>$toEmail,'email_from'=>"webmaster@example.com",
                    'email_subject'=>$subject ,'email_body'=>$body,'type_email'=>"Pending_backup_user_mail");
                    $res = $this->backup_model->addMailLog($mailLogInfo);
                }
                var_dump($result);
                echo '<br />';
                echo $this->email->print_debugger();
              
            }
        }return $result;
      }
      else
      {
          return 0;
      }   
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
                                 No. of times
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
                                <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;"><a href="'. base_url() .'schedule-details/'. $pendingBackup['scheduleId'].'">
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
                            <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;"><a href="'. base_url() .'schedule-details/'. $pendingBackup['scheduleId'].'">
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
                                        <a href="'. base_url() .'schedules">
                                            View More
                                        </a>
                                    </td>
                                 </tr>';
            }else{
                $row_view_more = '';
            }
            $rows=  $row . $row_view_more;
            $msg .= $rowelse;
                
        
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

        //   $result = $this->email
        //       ->from('gurinderjeetkaur01@gmail.com','Orion eSolutions')
        //       // ->reply_to('')    // Optional, an account where a human being reads.
        //       ->to($adminEmail)
        //       ->subject($subject)
        //       ->message($adminMailBody)
        //       ->send();
               
             // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
            // More headers
            $headers .= 'From: <webmaster@example.com>' . "\r\n";
            $headers .= 'Cc: myboss@example.com' . "\r\n";
            
            $result = mail($adminEmail,$subject,$adminMailBody,$headers);
           if($result == 1)
           {
               $mailLogInfo = array('email_to'=>$adminEmail,'email_from'=>"webmaster@example.com",
               'email_subject'=>$subject ,'email_body'=>$adminMailBody,'type_email'=>"Pending_backup_admin_mail");
               $res = $this->backup_model->addMailLog($mailLogInfo);
           }
           var_dump($result);
           echo '<br />';
           echo $this->email->print_debugger();
           return $result;
        }
        else
        {
            return 0;
        }   
    }
     /**
     * This function is used to Check IP Blacklist 
     */
     function dnsbllookup($ip, $isList)
    {
        // Add your preferred list of DNSBL's
        $dnsbl_lookup = [
            "dnsbl-1.uceprotect.net",
            "dnsbl-2.uceprotect.net",
            "dnsbl-3.uceprotect.net",
            "dnsbl.dronebl.org",
            "dnsbl.sorbs.net",
            "zen.spamhaus.org",
            "bl.spamcop.net",
            "list.dsbl.org",
            "sbl.spamhaus.org",
            "xbl.spamhaus.org",
            "b.barracudacentral.org"
        ];
        $listed = "";
        $listing = array();
        if ($ip) {
            $reverse_ip = implode(".", array_reverse(explode(".", $ip)));
            foreach ($dnsbl_lookup as $host) {
                if (checkdnsrr($reverse_ip . "." . $host . ".", "A")) { 
                    $listed .= '</br>'.$reverse_ip . '.' . $host ;
                     $listing[] = array('ip'=>$ip,
                                      'host'=>$host,
                                      'listed'=>'Listed'    
                             );
                }
                else
                {
                     $listing[] = array('ip'=>$ip,
                                      'host'=>$host,
                                      'listed'=>'Not Listed'    
                             );
                }
            }
        }
        if($isList == TRUE)
        {
            return $listing;
        }
        if($isList == FALSE)
        {
            if(empty($listed) ) {
                $message = 'Record was not found';
                redirect("check-ip-blacklist?status=".$message);
            } else {
                redirect("check-ip-blacklist?listed=".$listed);
            }
        }
        
    }
    
     /**
     * This function is used to load the Check daily- IP Blacklist 
     */
    function checkBlacklist()
    {
        $this->load->model('server_model');
        $data['IP_List'] = $this->server_model->ipListing();

        $data['server_ip'] = $this->server_model->serverListing( null, null);
        $list = array();
        $ip_list = array_merge($data['IP_List'], $data['server_ip']);
        $statusList = array();
        foreach( $ip_list as $servers)
        {
            if(isset($servers->server))
            {
                $serverId = $servers->id;
                $ip = $servers->server;
                $serverName = $servers->name;
            }
            else
            {
                $serverId = "";
                $ip =$servers->ip;
                $serverName = "";
            }
            $data["listing"][] = $this->dnsbllookup($ip, TRUE);
            foreach( $data["listing"] as $datalist)
            {
                foreach( $datalist as $data)
                {
                    $ip = $data['ip'];
                    $host = $data['host'];
                    if($data['listed'] == "Listed")
                    {
                        $isListed = 1;
                    }
                    elseif($data['listed'] == "Not Listed")
                    {
                        $isListed = 0;
                    }
                    if($serverId != "")
                    {
                        $ipInfo = array('ip'=>$ip,'serverId'=>$serverId,'host'=>$host,'isListed'=>$isListed);
                    }
                    else
                    {
                        $ipInfo = array('ip'=>$ip,'host'=>$host,'isListed'=>$isListed);
                    }
                    $result = $this->server_model->addIPBlacklist($ipInfo);
                   
                }
            } 
        }
        foreach( $ip_list as $ipList)
        {
            if(isset($ipList->server))
            {
                $serverId = $ipList->id;
                $ip = $ipList->server;
                $serverName = $ipList->name;
            }
            else
            {
                $serverId = "";
                $ip =$ipList->ip;
                $serverName = "";
            }
           $data['blacklist'] = $this->server_model->getIPBlacklist();
           $id = "";
           $isBlacklisted = "Not Checked";
           if(!empty($data['blacklist']))
           {
               foreach($data["blacklist"] as  $list => $li)
               { 
                   if($li->ip == $ip)  
                   {   
                       if($li->isListed == "1")  
                       {
                           $id = $li->id;
                           $isBlacklisted = "Listed";
                           break;
                       }
                       else
                       {
                           $id = $li->id;
                           $isBlacklisted = "Not Listed"; 
                       }
                   }
               }
            }
            $statusList[] = array("id"=>$id,
                              "ip"=> $ip,
                              "isListed"=> $isBlacklisted
                            );
        }
        $data['status'] = $statusList;
        $this->blacklistMail($data['status']);
    }
     /**
     * This function is used to send mail for IP Blacklist
     */
    function blacklistMail($data)
    {
        $row = "";
        $userInfo = $this->server_model->userListing();
       
        if(!empty($data))
        {
            foreach($data as $list)
            { 
                if($list['isListed'] == "Listed")
                {
                    $row .= '<tr   style="border-bottom: 1px solid #e8e8e8;text-align:center;" >
                            <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                            '.$list['ip'].'
                            </td>
                            <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                            '. $list['isListed'].'
                            </td>
                        </tr>'; 
                }
            }
        }  
        if($row != "")
        {
            $message = '<table style="border:1px solid #e8e8e8;border-spacing:0         ;width:100%;">
                            <tr style="background:#D1F2EB;  border-bottom: 1px solid #e8e8e8;">
                                <th   style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                        IP Address
                                </th>
                                <th  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                        is Listed?
                                    </th>
                            </tr>
                                    '.$row.'
                            <tr>
                                <td  style="border-bottom: 1px solid #e8e8e8;border-left: 1px solid #e8e8e8; padding: 10px 12px;">
                                    <a href="'.base_url().'/check-ip-blacklist">
                                        View
                                    </a>
                                </td>
                            </tr>
                        </table>';
            if(!empty($userInfo))
            {
                foreach($userInfo as $user)
                { 
                    $name = $user->name;
                    $email = $user->email;
                    $body = '<div style="text-align:center;">
                            <img src="'. base_url() .'assets/dist/img/logo.png" alt="" /></div>
                            <p>Hi '.$name.'</p>
                            <p>IP Blacklist: </p> 
                            '.$message.'
                            ';
           
                    $config['mailtype'] = 'html';
                    //$this->email->initialize($config);
                    $subject = "IP Blacklist";
                    // $result = $this->email
                    //     ->from('webmaster@example.com','Orion eSolutions')
                    //     // ->reply_to('')    // Optional, an account where a human being reads.
                    //     ->to($email)
                    //     ->subject($subject)
                    //     ->message($body)
                    //     ->send();
                    
                    // Always set content-type when sending HTML email
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            
                    // More headers
                    $headers .= 'From: <webmaster@example.com>' . "\r\n";
                    $headers .= 'Cc: myboss@example.com' . "\r\n";
                    $result = mail($email,$subject,$body,$headers);
                    if($result == TRUE)
                    {
                        $mailLogInfo = array('email_to'=>$email,'email_from'=>"webmaster@example.com",
                        'email_subject'=>$subject ,'email_body'=>$body,'type_email'=>"ip_blacklist");
                        $res = $this->server_model->addMailLog($mailLogInfo);
                    }
                    //var_dump($result);
                    //echo '<br />';
                    //echo $this->email->print_debugger();
                }
            }   
        }
    }
    
    
}
ob_flush();
?>