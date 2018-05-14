<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Server (ServerController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Testing extends BaseController
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
     * This function is used to load the server list
     */
   
    function testingpage()
    {
         $this->loadViews("test", $this->global, NULL, NULL);
    }  
}