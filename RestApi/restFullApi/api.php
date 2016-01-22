<?php
    
	/* 

	 * File : Rest.inc.php
	 * Author : Tarun Gupta (Pran) < tarun.g@dotsquares.com > 
	 * Company : DotSquares

	*/
		
	
	require_once("Rest.inc.php");
	
	class API extends REST {
	
		public $data = "";

		const DB_SERVER = "localhost";
		const DB_USER = "root";
		const DB_PASSWORD = "";
		const DB = "<DB Name>";
		
		private $db = NULL;
	
		public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		
		/*
		 *  Database connection 
		*/
		private function dbConnect(){
			$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
			if($this->db)
				
				mysql_select_db(self::DB,$this->db);
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		/* 
		 *	Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD>
		 */
		
		private function getUserData()
		{

              // define("XAccessID",'9eb16131072cf21bcebb', true);
		     // define("XSecretID","3d0be36c245b55b8e60c73a5eb85cf", true);
			 //header("X-Access-ID: 9eb16131072cf21bcebb");
			 //header("X-Secret-ID: 3d0be36c245b55b8e60c73a5eb85cf");


			$headersData = (object)array();
			$headerData = getallheaders();


			//$this->response($this->json($headerData), 406);
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$error = array('status' => "Failed", "msg" => "Access Denied XX");
				$this->response($this->json($error), 406);
			}
			
			if($this->get_request_method() == "POST")

			{

			//$headerData['X-Access-ID'];
			//$headerData['X-Secret-ID'];

			// Status flag:
					$Successful = false;
					 
					// Check username and password:
					if (isset($headerData['X-Access-ID']) && isset($headerData['X-Secret-ID']))
					{
					 
					    $Access = $headerData['X-Access-ID'];
					    $Secret = $headerData['X-Secret-ID'];
					 
					    if ($Access == '9eb16131072cf21bcebb' && $Secret == '3d0be36c245b55b8e60c73a5eb85cf')
					    {   
					    	// Login passed successful?
					        $Successful = true;
					    }
					}
					else
					{
							$error = array('status' => "Failed", "msg" => "Empty AccessID or SecretID");
						    $this->response($this->json($error), 401);		
					}
					 
						
						if ($Successful)
						{

							//$accessID = $this->_request['accessID'];		
							//$secretID = $this->_request['secretID'];
							$websiteID = $this->_request['website'];
							$userID = $this->_request['user'];
							//$secretID = $this->_request['secretID'];


							if(!empty($websiteID) and !empty($userID))
							{
					
									// Crenditatials  validations
									$query = "SELECT * FROM `piwik_log_visit` WHERE `idsite` =".$websiteID." && HEX(idvisitor)='".$userID."'";
						
									$sql = mysql_query($query);
							
									if(mysql_num_rows($sql) > 0)
									{
										$result = mysql_fetch_array($sql,MYSQL_ASSOC);
										$data = array(
						                        	'status' => "success", 
						                        	"visitID" =>$result['idvisit'],
						                        	"LastVisit"=> $result['visit_last_action_time'],
						                        	"visitorID"=>bin2hex($result['idvisitor']),
						                        	"configid"=>bin2hex($result['config_id']),
						                        	"locationip"=>bin2hex($result['location_ip']),
						                        	"message" =>"result"
	                        						);
										// Successfull Result
										$this->response($this->json($data), 200);
	          						}
							// if invalid userid or websiteid is empty
	          				$error = array('status' => "Failed", "msg" => "Invalid UserId or WebsiteID or Not Content Found");
						    $this->response($this->json($error), 201);

							}
							// if userid or websiteid is empty
							$error = array('status' => "Failed", "msg" => "empty UserId or WebsiteID");
						    $this->response($this->json($error), 201);
					
						}		
							
							$error = array('status' => "Failed", "msg" => "Invalid Key  or Secret key");
						    $this->response($this->json($error), 401);
 
			}

									
 
			
		}
		
			
				
		/*
		 *	Encode array into JSON
		*/
		public function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	// Initiiate Library
	
	$api = new API;
	$api->processApi();
?>