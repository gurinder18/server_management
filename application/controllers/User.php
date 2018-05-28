<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class User extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->isLoggedIn(); 
        // load Pagination library
        $this->load->library('pagination'); 
        $this->load->library('email');
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Orion eSolutions : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the user list
     */
    function userListing()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('user_model');
        
            $count = $this->user_model->userListingCount();
			$returns = $this->paginationCompress ( "users/", $count, 5 );
            
            $data['userRecords'] = $this->user_model->userListing($returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Orion eSolutions : User Listing';
          
            $this->loadViews("users", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addUser()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        elseif(isset($_POST['add_user'])!='Submit')
        {
            $this->load->model('user_model');
            $data['roles'] = $this->user_model->getUserRoles();
            
            $this->global['pageTitle'] = 'Orion eSolutions : Add New User';

            $this->loadViews("addNewUser", $this->global, $data, NULL);
        }
        elseif(isset($_POST['add_user'])=='Submit'){
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('name','Full Name','trim|required|max_length[128]|xss_clean');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|max_length[128]');
            $this->form_validation->set_rules('password','Password','required|min_length[4]');
            $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]|xss_clean');
            $this->form_validation->set_rules('status','Status','trim|required|numeric');

            if($this->form_validation->run() == FALSE)
            {
                unset($_POST['add_user']);
                $this->addUser();
            }
            else
            {
                $name = ucwords(strtolower($this->input->post('name')));
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->input->post('mobile');
                $status = $this->input->post('status');
                
                $userInfo = array( 'name'=> $name,'email'=>$email, 'password'=>getHashedPassword($password),'mobile'=>$mobile,
                'roleId'=>$roleId, 'status'=>$status,'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('user_model');
                $result = $this->user_model->addUser($userInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New User created successfully');
                    echo $body = '
                        <div style="text-align:center;"><img src="'. base_url() .'assets/dist/img/logo.png" alt="" /></div>
                        <p>Hi '.$name.'</p>
                        <h2>Welcome</h2>
                        <p>An Account has been created for you.You can login with the following credentials:</p> 
                        <p><b>Login Page:</b> <a href="http://backuptool.customerdemourl.com/login" >http://backuptool.customerdemourl.com/login</a> </p>
                        <p><b>Username:</b> "'.$email.'" (Without quotes)</p>
                        <p><b>Password:</b> "'.$password.'" (Without quotes)</p>
                        ';
                        $subject = "An Account has been created for you";

                        // $config['mailtype'] = 'html';
                        // $this->email->initialize($config);
                        
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
                            'email_subject'=>$subject ,'email_body'=>$body,'type_email'=>"new_user_account_created");
                            $res = $this->user_model->addMailLog($mailLogInfo);
                        }
                        var_dump($result);
                        echo '<br />';
                        echo $this->email->print_debugger();

                }
                else
                {
                    $this->session->set_flashdata('error', 'User creation failed');
                }
                
                redirect('users');
            }
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
   
    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editUser($userId = NULL)
    {
       
        if($this->isAdmin() == FALSE )
        {
            $this->loadThis();
        }
        $input = $this->input->post();
      
        if(isset($input['name']))
        {
            $this->load->library('form_validation');
            
            $userId = $this->input->post('userId');
           
            $this->form_validation->set_rules('name','Name','trim|required|max_length[128]|xss_clean');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|max_length[128]');
            $this->form_validation->set_rules('password','Password','min_length[4]');
           // $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile','required|min_length[10]|xss_clean');
            $this->form_validation->set_rules('status','Status','trim|required|numeric');

            if($this->form_validation->run() == FALSE)
            {
                //print_r($input); die;
                //unset($input['name']);
                //unset($_POST['name']);
                //$this->editUser($userId); 
            }
            else
            {
                $userId = $this->input->post('userId');
                $name = ucwords(strtolower($this->input->post('name')));
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->input->post('mobile');
                $status = $this->input->post('status');

                $userInfo = array();
               
                if(empty($password))
                {
                    $userInfo = array('name'=>$name,'email'=>$email, 'roleId'=>$roleId,  'mobile'=>$mobile, 
                    'status'=>$status, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                else
                {
                    $userInfo = array('name'=>ucwords($name),'email'=>$email, 'password'=>getHashedPassword($password), 
                    'mobile'=>$mobile, 'roleId'=>$roleId, 'status'=>$status, 'updatedBy'=>$this->vendorId, 
                        'updatedDtm'=>date('Y-m-d H:i:s'));
                echo $body = '
                        <div style="text-align:center;"><img src="'. base_url() .'assets/dist/img/logo.png" alt="" /></div>
                        <p>Hi '.$name.'</p>
                        <p>Your Password has changed by Administrator.</p> 
                        <p>Your new password is "'.$password.'" (Without quotes)</p>
                        ';
                }
                
                $result = $this->user_model->editUser($userInfo, $userId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'User updated successfully');
                   if($body != "")
                   {
                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);
                        $subject = "Your password changed by Administrator";
                        $result = $this->email
                            ->from('gurinderjeetkaur01@gmail.com','Orion Esolutions')
                            // ->reply_to('')    // Optional, an account where a human being reads.
                            ->to($email)
                            ->subject($subject)
                            ->message($body)
                            ->send();
                       
                        if($result == TRUE)
                        {
                            $mailLogInfo = array('email_to'=>$email,'email_from'=>"gurinderjeetkaur01@gmail.com",
                            'email_subject'=>$subject ,'email_body'=>$body,'type_email'=>"user_password_changed");
                            $res = $this->user_model->addMailLog($mailLogInfo);
                        }
                        var_dump($result);
                        echo '<br />';
                        echo $this->email->print_debugger();
                    }
                }
                else
                {
                    $this->session->set_flashdata('error', 'User updation failed');
                }
                
                redirect('edit-user/'.$userId);
            }
        }
        //elseif(isset($_POST['edit_user'])!='Submit')
       // {
            if($userId == null)
            {
                redirect('users');
            }
            
            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            
            $this->global['pageTitle'] = 'Orion eSolutions : Edit User';
            $this->loadViews("editUser", $this->global, $data, NULL);
        //}
    }
    
    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser()
    {
        if($this->isAdmin() == FALSE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        elseif(isset($_POST['delete_user'])!='Delete')
        {
            $userId = $this->input->post('userId');
            $userInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->user_model->deleteUser($userId, $userInfo);
            
            if ($result > 0) 
            { 
                echo(json_encode(array('status'=>TRUE))); 
            }
            else 
            { 
                echo(json_encode(array('status'=>FALSE))); 
            }
        }
        elseif(isset($_POST['delete_user'])=='Delete')
        {
            $del = $this->input->post('delete_users');
            if($del!=null)
            {
                foreach($del as $userId):
                   
                    $userInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                    
                    $result = $this->user_model->deleteUser($userId, $userInfo);
                endforeach;
                if ($result > 0)
                {  
                    redirect("users");
                    unset($_POST['delete_user']);
                }
            }else
            {
                redirect("users");
            }
        }
    }
    
    /**
     * This function is used to load the change password screen
     */
    function loadChangePass()
    {
        $this->global['pageTitle'] = 'CodeInsect : Change Password';
        
        $this->loadViews("changePassword", $this->global, NULL, NULL);
    }
    
    /**
     * This function is used to change the password of the user
     */
    function changePassword()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('oldPassword','Old password','required|max_length[20]');
        $this->form_validation->set_rules('newPassword','New password','required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword','Confirm new password','required|matches[newPassword]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->loadChangePass();
        }
        else
        {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');
            
            $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);
            
            if(empty($resultPas))
            {
                $this->session->set_flashdata('nomatch', 'Your old password not correct');
                redirect('loadChangePass');
            }
            else
            {
                $usersData = array('password'=>getHashedPassword($newPassword), 'updatedBy'=>$this->vendorId,
                                'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->user_model->changePassword($this->vendorId, $usersData);
                
                if($result > 0) { 
                   $this->session->userdata['updatedBy'] = $this->vendorId;
                    
                    $this->session->set_flashdata('success', 'Password updation successful'); }
                else { $this->session->set_flashdata('error', 'Password updation failed'); }
                
                redirect('loadChangePass');
            }
        }
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
     /**
     * This function is used to load the user list
     */
    function assignDuties()
    {
        if($this->isMember() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('user_model');
            $data['users'] = $this->user_model->userListing(NULL, NULL, "member", $this->vendorId);
            $this->global['pageTitle'] = 'Orion eSolutions : Assign Duties';
          
            $this->loadViews("assignDuties", $this->global, $data, NULL);
        }
    }
     /**
     * This function is used to load the add new form
     */
    function requestUser()
    {
       if(isset($_POST['request_user'])=='Submit'){
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('user','User','required');
            $this->form_validation->set_rules('startDate','StartDate','required');
            $this->form_validation->set_rules('endDate','EndDate','required');
           
            if($this->form_validation->run() == FALSE)
            {
                unset($_POST['request_user']);
                $this->requestUser();
            }
            else
            {
                $requestedUser = $this->input->post('user');
                $startDate = $this->input->post('startDate');
                $endDate = $this->input->post('endDate');
                $start = new DateTime($startDate);
                $end = new DateTime($endDate);
                $diff= date_diff($start,$end);
                $numDays = $diff->format("%a ");
               
                $userInfo = array( 'userId'=> $this->vendorId,'startDate'=>$startDate, 'endDate'=>$endDate,
                'numDays'=>$numDays, 'requestedUser'=> $requestedUser,
                 'createdAt'=>date('Y-m-d H:i:s'));
                
                $this->load->model('user_model');
                $result = $this->user_model->requestUser($userInfo);
               
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'Request send successfully');
                    $userInfo = $this->user_model->getUserInfo($requestedUser);
                    foreach( $userInfo as $user)
                    {
                        $userEmail = $user->email;
                        $userName = $user->name;
                    }
                    echo $body = '
                        <div style="text-align:center;"><img src="'. base_url() .'assets/dist/img/logo.png" alt="" /></div>
                        <p>Hi '.$userName.'</p>
                        <p>'.$this->name.' is requesting you to accept his  assigned backup-schedules
                         for '.$numDays.' i.e. from '.$startDate.' to '.$endDate.'</p> 
                         Request <a href="" >Accepted</a> or <a href="">Rejected</a>. 
                        ';
                        $subject = "Request for assigning duties";

                        $config['mailtype'] = 'html';
                        $this->email->initialize($config);
                        
                         $res = $this->email
                            ->from('webmaster@example.com','Orion eSolutions')
                            // ->reply_to('')    // Optional, an account where a human being reads.
                            ->to($userEmail)
                            ->subject($subject)
                            ->message($body)
                            ->send();
                        
                        // // Always set content-type when sending HTML email
                        // $headers = "MIME-Version: 1.0" . "\r\n";
                        // $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                
                        // // More headers
                        // $headers .= 'From: <webmaster@example.com>' . "\r\n";
                        // $headers .= 'Cc: myboss@example.com' . "\r\n";
                        // $result = mail($email,$subject,$body,$headers);
                        if($res == TRUE)
                        {
                            $mailLogInfo = array('email_to'=>$userEmail,'email_from'=>"webmaster@example.com",
                            'email_subject'=>$subject ,'email_body'=>$body,'type_email'=>"request_user_to_assign_duties");
                            $res1 = $this->user_model->addMailLog($mailLogInfo);
                        }
                        var_dump($result);
                        echo '<br />';
                        echo $this->email->print_debugger();

                }
                else
                {
                    $this->session->set_flashdata('error', 'Request failed');
                }
                
                redirect('assign-duties');
            }
        }
    }

}

?>