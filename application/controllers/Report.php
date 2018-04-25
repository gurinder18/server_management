<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Report (ReportController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 */
class Report extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('report_model');
        $this->load->library('excel');
        $this->isLoggedIn();   
    }
 
    /**
     * This function used to load the first screen of the reports
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Orion eSolutions : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the report list
     */
   
    function backupReport($limit = NULL)
    {
        $this->load->model('report_model');
        if($this->isAdmin() == TRUE)
        {
            $this->load->library('pagination');
           
            $fromDate=date("Y-m-d", strtotime("-1 month"));
            $toDate=date('Y-m-d');
            $count = $this->report_model->reportCount(null,null,null, $fromDate,$toDate);
            $returns = $this->paginationCompress ( "backup-report/", $count, 5 );

            if(isset($_GET['search_BackupSchedule'])!='Submit')
            {
                $count = $this->report_model->reportCount($returns["page"],$returns["segment"],null, $fromDate,$toDate);
                $returns = $this->paginationCompress ( "backup-report/", $count, 5 );
                
                $data['scheduleRecords'] = $this->report_model->report( $returns["page"], $returns["segment"],null, $fromDate,$toDate);
                $data['clients'] = $this->report_model->getClients();
                $data['servers'] = $this->report_model->getServers();
                $data['users'] = $this->report_model->getUsers();
              
                $this->global['pageTitle'] = 'Orion eSolutions : Schedules Report';
               
                $this->loadViews("reports", $this->global, $data, NULL);
            }
            elseif(isset($_GET['search_BackupSchedule'])=='Submit')
            {
                // converting selected fromDate format(mm/dd/yyyy) into format(yyyy-mm-dd)
                $from =  $this->input->get('fromDate');
                if(!$from==null)
                {
                    $from2[] = explode("/",$from);
                    foreach($from2 AS $fromDate)
                    {
                        $from3[] = $fromDate[2];
                        $from3[] = $fromDate[0];
                        $from3[] = $fromDate[1];
                    }
                    $from4 = implode("-",$from3);
                    $search_data['fromDate'] =  $from4;
                }
                else
                {
                    $search_data['fromDate'] =  null;
                }

                // converting selected toDate format(mm/dd/yyyy) into format(yyyy-mm-dd)
                $to =  $this->input->get('toDate');
                if(!$from==null)
                {
                    $to2[] = explode("/",$to);
                    foreach($to2 AS $t)
                    {
                        $to3[] = $t[2];
                        $to3[] = $t[0];
                        $to3[] = $t[1];
                    }
                    $to4 = implode("-",$to3);
                    $search_data['toDate'] = $to4;
                }
                else
                {
                    $search_data['toDate'] =  null;
                }

                $search_data['clientId'] = $this->input->get('client');
                $search_data['serverId'] = $this->input->get('server');
                $search_data['userId'] = $this->input->get('user');
                $search_data['status'] = $this->input->get('status');
               
                $count = $this->report_model->reportCount($returns["page"], $returns["segment"],$search_data, null,null);
                $returns = $this->paginationCompress ( "backup-report/", $count, 5 );
                $data['scheduleRecords'] = $this->report_model->report( $returns["page"], $returns["segment"],$search_data, null,null);
                $data['clients'] = $this->report_model->getClients();
                $data['servers'] = $this->report_model->getServers();
                $data['users'] = $this->report_model->getUsers();
               
                $this->global['pageTitle'] = 'Orion eSolutions : Schedule Report';
                $this->loadViews("reports", $this->global, $data, NULL);
               
            }
        }
    }
    /**
     * This function is used to load the all backup's report list
     */
   
    function allBackupReport($limit = NULL)
    {
        $this->load->model('report_model');
        if($this->isAdmin() == TRUE)
        {
            $this->load->library('pagination');
           
            $fromDate=date("Y-m-d", strtotime("-1 month"));
            $toDate=date('Y-m-d');
            $count = $this->report_model->reportCount(null,null,null, $fromDate,$toDate);
            $returns = $this->paginationCompress ( "backups-report/", $count, 0 );

            if(isset($_GET['search_BackupSchedule'])!='Submit')
            {
                $count = $this->report_model->reportCount($returns["page"],$returns["segment"],null, $fromDate,$toDate);
                $returns = $this->paginationCompress ( "backups-report/", $count, 0 );
                
                $data['scheduleRecords'] = $this->report_model->report( $returns["page"], $returns["segment"],null, $fromDate,$toDate);
                $data['clients'] = $this->report_model->getClients();
                $data['servers'] = $this->report_model->getServers();
                $data['users'] = $this->report_model->getUsers();
              
                $this->global['pageTitle'] = 'Orion eSolutions : Schedules Report';
               
                $this->loadViews("reports", $this->global, $data, NULL);
            }
            elseif(isset($_GET['search_BackupSchedule'])=='Submit')
            {
                // converting selected fromDate format(mm/dd/yyyy) into format(yyyy-mm-dd)
                $from =  $this->input->get('fromDate');
                if(!$from==null)
                {
                    $from2[] = explode("/",$from);
                    foreach($from2 AS $fromDate)
                    {
                        $from3[] = $fromDate[2];
                        $from3[] = $fromDate[0];
                        $from3[] = $fromDate[1];
                    }
                    $from4 = implode("-",$from3);
                    $search_data['fromDate'] =  $from4;
                }
                else
                {
                    $search_data['fromDate'] =  null;
                }

                // converting selected toDate format(mm/dd/yyyy) into format(yyyy-mm-dd)
                $to =  $this->input->get('toDate');
                if(!$from==null)
                {
                    $to2[] = explode("/",$to);
                    foreach($to2 AS $t)
                    {
                        $to3[] = $t[2];
                        $to3[] = $t[0];
                        $to3[] = $t[1];
                    }
                    $to4 = implode("-",$to3);
                    $search_data['toDate'] = $to4;
                }
                else
                {
                    $search_data['toDate'] =  null;
                }

                $search_data['clientId'] = $this->input->get('client');
                $search_data['serverId'] = $this->input->get('server');
                $search_data['userId'] = $this->input->get('user');
                $search_data['status'] = $this->input->get('status');
               
                $count = $this->report_model->reportCount($returns["page"], $returns["segment"],$search_data, null,null);
                $returns = $this->paginationCompress ( "backups-report/", $count, 0 );
                $data['scheduleRecords'] = $this->report_model->report( $returns["page"], $returns["segment"],$search_data, null,null);
                $data['clients'] = $this->report_model->getClients();
                $data['servers'] = $this->report_model->getServers();
                $data['users'] = $this->report_model->getUsers();
               
                $this->global['pageTitle'] = 'Orion eSolutions : Schedule Report';
                $this->loadViews("reports", $this->global, $data, NULL);
               
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
                redirect('backup-report');
            }
            $this->load->model('report_model');
            
            $data['scheduleInfo'] = $this->schedule_model->getScheduleInfo($id);
            $scheduleId = $data['scheduleInfo'];
            $data['users'] = $this->schedule_model->getUsers();
          
            $data['commentInfo'] = $this->schedule_model->getCommentInfo( $scheduleId['id']);
           // print_r($data);
            $this->global['pageTitle'] = 'Orion eSolutions : Schedule Details';
         
            $this->loadViews("scheduleDetails", $this->global, $data, NULL);
        }
    }

      /**
     * This function is update schedule  status
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
        //echo json_encode(array('status' => $status, 'msg' => $msg));
    }
    public function excel()
        {
                    $this->excel->setActiveSheetIndex(0);
                    //name the worksheet
                    $this->excel->getActiveSheet()->setTitle('Countries');
                    //set cell A1 content with some text
                    $this->excel->getActiveSheet()->setCellValue('A1', 'Backup Report List');
                    $this->excel->getActiveSheet()->setCellValue('A4', 'Date');

                    $this->excel->getActiveSheet()->setCellValue('B4', 'Client');
                    $this->excel->getActiveSheet()->setCellValue('C4', 'Server');
                    $this->excel->getActiveSheet()->setCellValue('D4', 'User');
                    $this->excel->getActiveSheet()->setCellValue('E4', 'Status');
                    $this->excel->getActiveSheet()->setCellValue('F4', 'Day');
                    //merge cell A1 until C1
                    $this->excel->getActiveSheet()->mergeCells('A1:F1');
                    //set aligment to center for that merged cell (A1 to C1)
                    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    //make the font become bold
                    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
                    $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
                    
                    for($col = ord('A'); $col <= ord('F'); $col++){ //set column dimension $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
                        //change the font size
                        $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
                        $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    }
                    //retrive contries table data
                    $fromDate=date("Y-m-d", strtotime("-1 month"));
                    $toDate=date('Y-m-d');
                    $this->load->model('report_model');
                    $count = $this->report_model->reportCount(null,null,null, $fromDate,$toDate);
                    $returns = $this->paginationCompress ( "backups-report/", $count, 0 );
                
                    $data['scheduleRecords'] = $this->report_model->report( $returns["page"], $returns["segment"],null, $fromDate,$toDate);
                   // $data['clients'] = $this->report_model->getClients();
                   // $data['servers'] = $this->report_model->getServers();
                    //$data['users'] = $this->report_model->getUsers();
              
                   // $rs = $this->db->get('countries');
                    $exceldata="";
                    foreach ($data['scheduleRecords'] as $rows){
                        foreach ($rows as $row){
                            $exceldata[] = $row;
                        }

                    }
                    
                    //Fill data
                    $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A4');
                    $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    
                    
                    $filename='PHPExcelDemo.xls'; //save our workbook as this file name
                    header('Content-Type: application/vnd.ms-excel'); //mime type
                    header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                    header('Cache-Control: max-age=0'); //no cache
                    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                    //if you want to save it as .XLSX Excel 2007 format
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
                    //force user to download the Excel file without writing it to server's HD
                    $objWriter->save('php://output');
        }
    
}

?>