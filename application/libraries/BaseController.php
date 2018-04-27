<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

/**
 * Class : BaseController
 * Base Class to control over all the classes
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class BaseController extends CI_Controller {
	protected $role = '';
	protected $vendorId = '';
	protected $name = '';
	protected $roleText = '';
	protected $global = array ();
	
	/**
	 * Takes mixed data and optionally a status code, then creates the response
	 *
	 * @access public
	 * @param array|NULL $data
	 *        	Data to output to the user
	 *        	running the script; otherwise, exit
	 */
	public function response($data = NULL) {
		$this->output->set_status_header ( 200 )->set_content_type ( 'application/json', 'utf-8' )->set_output ( json_encode ( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) )->_display ();
		exit ();
	}
	
	/**
	 * This function used to check the user is logged in or not
	 */
	function isLoggedIn() {
		$isLoggedIn = $this->session->userdata ( 'isLoggedIn' );
		
		if (! isset ( $isLoggedIn ) || $isLoggedIn != TRUE) {
			redirect ( 'login' );
		} else {
			$this->role = $this->session->userdata ( 'role' );
			$this->vendorId = $this->session->userdata ( 'userId' );
			$this->name = $this->session->userdata ( 'name' );
			$this->roleText = $this->session->userdata ( 'roleText' );
			$this->roleSlug = $this->session->userdata ( 'slug' );
			
			$this->global ['name'] = $this->name;
			$this->global ['role'] = $this->role;
			$this->global ['role_text'] = $this->roleText;
			$this->global ['role_slug'] = $this->roleSlug;
		}
	}
	
	/**
	 * This function is used to check the access
	 */
	function isAdmin() {
		if ($this->roleSlug == 'sys.admin') {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * This function is used to check the access
	 */
	function isMember() {
		if ($this->roleSlug == 'member') {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * This function is used to check the access
	 */
	function isTicketter() {
		if ($this->role != ROLE_ADMIN || $this->role != ROLE_MANAGER) {
			return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 * This function is used to load the set of views
	 */
	function loadThis() {
		$this->global ['pageTitle'] = 'Orion eSolutions : Access Denied';
		
		$this->load->view ( 'includes/header', $this->global );
		$this->load->view ( 'access' );
		$this->load->view ( 'includes/footer' );
	}
	
	/**
	 * This function is used to logged out user from system
	 */
	function logout() {
		$this->session->sess_destroy ();
		
		redirect ( 'login' );
	}

	/**
     * This function used to load views
     * @param {string} $viewName : This is view name
     * @param {mixed} $headerInfo : This is array of header information
     * @param {mixed} $pageInfo : This is array of page information
     * @param {mixed} $footerInfo : This is array of footer information
     * @return {null} $result : null
     */
    function loadViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){

        $this->load->view('includes/header', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('includes/footer', $footerInfo);
    }
	
	/**
	 * This function used provide the pagination resources
	 * @param {string} $link : This is page link
	 * @param {number} $count : This is page count
	 * @param {number} $perPage : This is records per page limit
	 * @return {mixed} $result : This is array of records and pagination data
	 */
	function paginationCompress($link, $count, $perPage = 10) {
		$this->load->library ( 'pagination' );
	
		$config ['base_url'] = base_url () . $link;
		$config ['total_rows'] = $count;
		$config ['uri_segment'] = SEGMENT;
		$config ['per_page'] = $perPage;
		$config ['num_links'] = 5;
		
		$config ['full_tag_open'] = '<div class="pagination">';
		$config ['full_tag_close'] = '</div>';
		
		$config ['first_tag_open'] = '<span class="arrow">';
		$config ['first_link'] = 'First';
		$config ['first_tag_close'] = '</span>';
		
		$config ['prev_link'] = 'Previous';
		$config ['prev_tag_open'] = '<span class="arrow">';
		$config ['prev_tag_close'] = '</span>';
		
		$config ['next_link'] = 'Next';
		$config ['next_tag_open'] = '<span class="arrow">';
		$config ['next_tag_close'] = '</span>';
		
		$config ['cur_tag_open'] = '<span class="curlink">';
		$config ['cur_tag_close'] = '</span>';
		
		$config ['num_tag_open'] = '<span class="numlink">';
		$config ['num_tag_close'] = '</span>';
		
		$config ['last_tag_open'] = '<span class="lastlink">';
		$config ['last_link'] = 'Last';
		$config ['last_tag_close'] = '</span>';
	
		$this->pagination->initialize ( $config );
		$page = $config ['per_page'];
		$segment = $this->uri->segment ( SEGMENT );
		//$segment = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		return array (
				"page" => $page,
				"segment" => $segment
		);
	}

	function sendEmail($to, $subject, $from_name, $from, $body){
		
		
		// The mail sending protocol.
		$config['protocol'] = 'smtp';
		// SMTP Server Address for Gmail.
		$config['smtp_host']='ssl://smtp.googlemail.com';
		// SMTP Port - the port that you is required
		$config['smtp_port'] = 465;
		// SMTP Username like. (abc@gmail.com)
		$config['smtp_user'] = 'gurinderjeetkaur01@gmail.com';
		// SMTP Password like (abc***##)
		$config['smtp_pass'] = 'redhat123';
		
		$config['smtp_crypto']      = 'ssl';

		$config['mailtype'] = 'html';
		//$config['charset'] = 'iso-8859-1';
		$this->load->library('email',$config);
		// Sender email address
		$this->email->from('gurinderjeetkaur01@gmail.com', $from_name);
		// Receiver email address.for single email
		$this->email->to($to);
		//send multiple email
		
		// Subject of email
		$this->email->subject($subject);
		// Message in email
		$this->email->message($body);
		
		$this->email->send(); 
		show_error($this->email->print_debugger());
		// It returns boolean TRUE or FALSE based on success or failure
		
	}
}