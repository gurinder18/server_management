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
            $toDate=date("Y-m-d", strtotime("+1 day"));
            $count = $this->report_model->reportCount(null,null,null, $fromDate,$toDate);
            $returns = $this->paginationCompress ( "backup-report/", $count, 5 );

            if(isset($_GET['search_BackupSchedule'])!='Search')
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
            elseif(isset($_GET['search_BackupSchedule'])=='Search')
            {
                // converting selected fromDate and toDate format(mm/dd/yyyy) into format(yyyy-mm-dd)
               
                $from =  $this->input->get('fromDate');
                $to =  $this->input->get('toDate');
                if($from!=null && $to!=null)
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
                elseif($from!=null || $to!=null)
                {
                    echo "<script>alert('Please select From-date and To-date');</script>";
                    $search_data['fromDate'] =  null;
                    $search_data['toDate'] =  null;
                }else{
                    $search_data['fromDate'] =  null;
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
            $toDate=date("Y-m-d", strtotime("+1 day"));
            $count = $this->report_model->reportCount(null,null,null, $fromDate,$toDate);
            $returns = $this->paginationCompress ( "backups-report/", $count, 0 );

            if(isset($_GET['search_BackupSchedule'])!='Search')
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
            elseif(isset($_GET['search_BackupSchedule'])=='Search')
            {
                // converting selected fromDate format(mm/dd/yyyy) into format(yyyy-mm-dd)
                $from =  $this->input->get('fromDate');
                $to =  $this->input->get('toDate');
                if($from!=null && $to!=null)
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
                elseif($from!=null || $to!=null)
                {
                    echo "<script>alert('Please select From-date and To-date');</script>";
                    $search_data['fromDate'] =  null;
                    $search_data['toDate'] =  null;
                }else{
                    $search_data['fromDate'] =  null;
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
                    //retrive backup report table data
                        
                    $this->load->model('report_model');
                    
                    $this->db->select('BaseTbl.date,User.name As UserName,Client.name As ClientName,
                    Server.name As ServerName,Server.server As ServerIP, Server.hostname As ServerHostname,
                    Backup.scheduleTimings As Day,Status.status As ScheduleStatus');

                    $this->db->from('tbl_backup_schedule as BaseTbl');
                
                    $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
                    $this->db->join('tbl_clients as Client', 'Client.id = BaseTbl.clientId','left');
                    $this->db->join('tbl_backups as Backup', 'Backup.id = BaseTbl.backupId','left');
                    $this->db->join('tbl_servers as Server', 'Server.id = Backup.serverId','left');
                    $this->db->join('tbl_backup_status as Status', 'Status.id = BaseTbl.status','left');

                    $query = $this->db->get();
                
                    $exceldata="";
                    foreach ($query->result_array() as  $value)
                    {
                        $exceldata[] = $value;
                    }
               

                    $this->excel->setActiveSheetIndex(0);
                    //name the worksheet
                    $this->excel->getActiveSheet()->setTitle('Countries');
                    //set cell A1 content with some text
                    $this->excel->getActiveSheet()->setCellValue('A1', 'Backup Report List');
                    $this->excel->getActiveSheet()->setCellValue('A3', 'Date');

                    $this->excel->getActiveSheet()->setCellValue('B3', 'User');
                    $this->excel->getActiveSheet()->setCellValue('C3', 'Client');
                    $this->excel->getActiveSheet()->setCellValue('D3', 'Server');
                    $this->excel->getActiveSheet()->setCellValue('E3', 'Server IP');
                    $this->excel->getActiveSheet()->setCellValue('F3', 'Hostname');
                    $this->excel->getActiveSheet()->setCellValue('G3', 'Day');
                    $this->excel->getActiveSheet()->setCellValue('H3', 'Status');
                    
                    //merge cell A1 until C1
                    $this->excel->getActiveSheet()->mergeCells('A1:H1');
                    //set aligment to center for that merged cell (A1 to C1)
                    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    //make the font become bold,change the font size
                    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);                    
                    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
                    $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
                     
                   
                    $style_pending = array();
                    $style_inprogress = array();
                    $style_completed = array();
                    $style_failed = array();
                   
                        $style_pending = array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb'=>'FF6666'),
                            ),
                            'font' => array(
                                'bold' => true,
                                'size' => 12,
                            )
                        );
                        $style_inprogress = array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb'=>'FFFF99'),
                            ),
                            'font' => array(
                                'bold' => true,
                                'size' => 12,
                            )
                        );
                        $style_completed = array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb'=>'66FF66'),
                            ),
                            'font' => array(
                                'bold' => true,
                                'size' => 12,
                            )
                        );
                        $style_failed = array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb'=>'FF0000'),
                            ),
                            'font' => array(
                                'bold' => true,
                                'size' => 12,
                            )
                        );
                    
                    //make the font become bold,change the font size
                    $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);                    
                    $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(14);
                    $this->excel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('#333');
                    
                      //make the font become bold,change the font size
                    $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);                    
                    $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(14);
                    $this->excel->getActiveSheet()->getStyle('B3')->getFill()->getStartColor()->setARGB('#333');
                    
                    //make the font become bold,change the font size
                    $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);                    
                    $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setSize(14);
                    $this->excel->getActiveSheet()->getStyle('C3')->getFill()->getStartColor()->setARGB('#333');
                     
                    //make the font become bold,change the font size
                    $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);                    
                    $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setSize(14);
                    $this->excel->getActiveSheet()->getStyle('D3')->getFill()->getStartColor()->setARGB('#333');
                    
                    //make the font become bold,change the font size
                    $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);                    
                    $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setSize(14);
                    $this->excel->getActiveSheet()->getStyle('E3')->getFill()->getStartColor()->setARGB('#333');
                    
                    //make the font become bold,change the font size
                    $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);                    
                    $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setSize(14);
                    $this->excel->getActiveSheet()->getStyle('F3')->getFill()->getStartColor()->setARGB('#333');
                     
                    //make the font become bold,change the font size
                    $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);                    
                    $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setSize(14);
                    $this->excel->getActiveSheet()->getStyle('G3')->getFill()->getStartColor()->setARGB('#333');
                    
                    //make the font become bold,change the font size
                    $this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);                    
                    $this->excel->getActiveSheet()->getStyle('H3')->getFont()->setSize(14);
                    $this->excel->getActiveSheet()->getStyle('H3')->getFill()->getStartColor()->setARGB('#333');
                  
                    for($col = ord('A'); $col <= ord('H'); $col++){ //set column dimension $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
                        //change the font size
                        $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
                        $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    }
                  
                    //Fill data
                    $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A4');
                    $this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
             
                        $column = 'H';
                        $coll = 'A';
                        $last_row =count($exceldata)+4;
                        
                        for ($row = 1; $row <= $last_row; $row++) 
                        {
                            if($this->excel->getActiveSheet()->getCell($column.$row)=='Pending')
                            {
                                $this->excel->getActiveSheet()->getStyle($coll.$row.":".$column.$row)->applyFromArray( $style_pending );
                            }
                            elseif($this->excel->getActiveSheet()->getCell($column.$row)=='Inprogress')
                            {
                                $this->excel->getActiveSheet()->getStyle($coll.$row.":".$column.$row)->applyFromArray( $style_inprogress );
                            }
                            elseif($this->excel->getActiveSheet()->getCell($column.$row)=='Completed')
                            {
                                $this->excel->getActiveSheet()->getStyle($coll.$row.":".$column.$row)->applyFromArray( $style_completed );
                            }
                            elseif($this->excel->getActiveSheet()->getCell($column.$row)=='Failed')
                            {
                                $this->excel->getActiveSheet()->getStyle($coll.$row.":".$column.$row)->applyFromArray( $style_failed );
                            }
                           
                        }
                    
                    $filename='Backup_Report.xls'; //save our workbook as this file name
                    header('Content-Type: application/vnd.ms-excel'); //mime type
                    header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                    header('Cache-Control: max-age=0'); //no cache
                    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                    //if you want to save it as .XLSX Excel 2007 format
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
                   //print_r( $objWriter);die;
                    //force user to download the Excel file without writing it to server's HD
                    $objWriter->save('php://output');
        }
        
    
}

?>