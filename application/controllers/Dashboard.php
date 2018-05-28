<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Server (ServerController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Dashboard extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
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
   
    function dashboardSchedule()
    {
        $current_date = date('d-m-Y');
        $userId = 2;
        $data['users'] = $this->dashboard_model->getUsers();
        $data['servers'] = $this->dashboard_model->getServers($userId);
        if($this->isMember() == TRUE)
        {
            $this->load->model('dashboard_model');
            $data['pendingBackupCount'] = $this->dashboard_model->todaysPendingBackupCount( $current_date,$this->vendorId);
            $this->global['pageTitle'] = 'Orion eSolutions : Dashboard';
                
            $this->loadViews("dashboard", $this->global, $data, NULL);
        }
        elseif($this->isAdmin() == TRUE)
        {
            $this->load->model('dashboard_model');
           
            $data['pendingBackupCount'] = $this->dashboard_model->todaysPendingBackupCount( $current_date); 
            $data['todayBackupCount'] = $this->dashboard_model->todaysBackupCount( $current_date); 
            //print_r($data);

            $this->global['pageTitle'] = 'Orion eSolutions : Dashboard';
            $this->loadViews("dashboard", $this->global,  $data, NULL);
        }   
    }
    function userBackupDetails()
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
  
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Orion eSolutions : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
     
    /**
     * This function is used to load the backup status list
     */
    function getdata() 
    { 
            $data1 = $this->dashboard_model->userBackupstatus(1); 
            $data['Pending'] = $data1;
            
            $data2 = $this->dashboard_model->userBackupstatus(2); 
            $data['Inprogress'] = $data2;
           
            $data3 = $this->dashboard_model->userBackupstatus(3); 
            $data['Completed'] = $data3;
            
            $data4 = $this->dashboard_model->userBackupstatus(4); 
            $data['Failed'] = $data4;
        
        //         //data to json 
 
        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Topping", 
            "pattern" => "", 
            "type" => "string" 
        ); 
        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Total", 
            "pattern" => "", 
            "type" => "number" 
        ); 
        foreach($data as  $key => $value)
        { 
            $responce->rows[]["c"] = array( 
                array( 
                    "v" => $key, 
                    "f" => null 
                ) , 
                array( 
                    "v" => (int)$value, 
                    "f" => null 
                ) 
            ); 
        } 
        echo json_encode($responce); 
        } 
   /**
     * This function is used to load the backup status list
     */
    function getdata2() 
    { 
            $user = $this->input->get('user');
            $server = $this->input->get('server');
            $month = $this->input->get('month');
       
            $searchInfo = array();
                    
            $searchInfo = array('user'=>$user,'server'=>$server,'month'=>$month);
           
           
                $data1 = $this->dashboard_model->userBackupstatus(1, $searchInfo); 
                $data['Pending'] = $data1;
                
                $data2 = $this->dashboard_model->userBackupstatus(2, $searchInfo); 
                $data['Inprogress'] = $data2;
            
                $data3 = $this->dashboard_model->userBackupstatus(3, $searchInfo); 
                $data['Completed'] = $data3;
                
                $data4 = $this->dashboard_model->userBackupstatus(4, $searchInfo); 
                $data['Failed'] = $data4;
       
        //         //data to json 

        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Topping", 
            "pattern" => "", 
            "type" => "string" 
        ); 
        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Total", 
            "pattern" => "", 
            "type" => "number" 
        ); 
        foreach($data as  $key => $value)
        { 
            $responce->rows[]["c"] = array( 
                array( 
                    "v" => $key, 
                    "f" => null 
                ) , 
                array( 
                    "v" => (int)$value, 
                    "f" => null 
                ) 
            ); 
        } 
 
        echo json_encode($responce); 
        } 
}

?>