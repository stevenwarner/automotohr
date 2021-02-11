<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '../vendor/tpm/Twilio/autoload.php';

use Twilio\Rest\Client;

/**
 * Send & Receive messages through twilio
 * Created on: 12-07-2019
 * 
 * @method
 * Send Message
 * Receive Message
 * Create Message Service
 * Bind Phone Number with Message Service
 * Delete Message Service
 * Fetch available numbers
 * Book available numbers
 * 
 * @version 1.0
 */
class Twilioapp{
	private $mode;
	private $error;
	private $error_array;
	private $account_sid;
	private $account_token;
	private $message_sid;
	private $twilio;
	private $phones;
	private $byPassSandbox;
	private $message_service_name_code;
	private $message_service_name;
	private $country_iso;
	private $alfaSenderName;
	private $filterArray = array();

	/**
	 * Class constructor
	 * Sets the defaults
	 * 
	 * @uses setPhone
	 * @uses setMode
	 * 
	 */
	function __construct(){
		// Set local numbers filter array
		$this->filterArray['smsEnabled'] = true;
		// $this->filterArray['mmsEnabled'] = false;
		// $this->filterArray['voiceEnabled'] = false;
		// Set by pass sand mode phone number check
		$this->byPassSandbox = false;
		// Set default country iso
		$this->country_iso = 'GB';
		// Convert to object
		$this->phones = (object) $this->phones;
		// Set default connection object to false
		$this->twilio = false;
		// Set default message type Array
		$this->message_service_type_array = 
		array(
			'ATS_SERVICE' => ATS_SERVICE,
			'SANDBOX_SERVICE' => SANDBOX_SERVICE
		);
		// Set primary phone
		$this->setPhone('primary', array('sid' => 'PNffe6bb0d38f0bbf4bf2def2cb86d4982', 'number' => '+15597028989'));
		// Set secondary one phone
		$this->setPhone('secondary_one', array('sid' => 'PNcd41479c6145ecb0eab1dcdcf608360e', 'number' => '+19097570288'));
		// Set mode type
		$this->setMode('sandbox');
		// $this->setMode('production');
		$this->error = false;
		$this->error_array = array(
			'Error' => 'Oops! Something went wrong',
			'ErrorCode' => 0
		);
	}

	/**
	 * Set the mode of application
	 * 
	 * @param $mode String ('sandbox', 'production')
	 * 
	 * @uses setAccountSID
	 * @uses setAccountToken
	 * 
	 * @return Instance
	 */
	function setMode($mode){
		if(strtolower($mode) === 'production') $this->mode = 'production';
		else $this->mode = 'sandbox';
		// For production
		if($mode === 'production'){
			$this->setAccountSID(TWILIO_SID);
			$this->setAccountToken(TWILIO_TOKEN);
			return $this;
		}
		// For sandbox
		$this->setAccountSID(TWILIO_SANDBOX_SID);
		$this->setAccountToken(TWILIO_SANDBOX_TOKEN);
		return $this;
	}

	/**
	 * Set the phone numbers
	 * 
	 * @param $type String
	 * @param $data Array
	 * 
	 * @return Instance
	 */
	function setPhone($type, $data){
		// Set phone numbers
		$this->phones->$type = json_decode (json_encode ($data), FALSE);
		return $this;
	}

	/**
	 * Get the phone number
	 * 
	 * @param $type String
	 * @param $index String
	 * @param $is_return Bool Optional
	 * 
	 * @uses setSenderPhone
	 * 
	 * @return String
	 */
	function getPhone($type, $index, $is_return = FALSE){
		// Set phone numbers
		if($is_return) return $this->phones->$type->$index;
		$this->setSenderPhone($this->phones->$type->$index);
	}

	/**
	 * Set the sandbox by pass check
	 * 
	 * @param $check Bool
	 * 
	 * @return Instance
	 */
	function byPassSandbox($check){
		$this->byPassSandbox = $check;
		return $this;
	}


	/**
	 * Set alfa sender name
	 * 
	 * @param $alfaSenderName String
	 * 
	 * @return Instance
	 */
	function setAlfaSenderName($alfaSenderName){
		$this->alfaSenderName = $alfaSenderName;
		return $this;
	}

	/**
	 * Set the message service
	 * 
	 * @param $message_service_type String
	 * 
	 * @uses getMessageServiceSidByType
	 * 
	 * @return Instance
	 */
	function setMessageService($message_service_type){
		$this->message_service = $this->getMessageServiceSidByType($message_service_type); break;
		return $this;
	}

	/**
	 * Set the message service
	 * 
	 * @param $message_service_type String
	 * @param $by_key Bool Optional
	 * Default is FALSE
	 * 
	 * @return Instance
	 */
	function getMessageServiceSidByType($message_service_type, $by_key = FALSE){
		// Letters to upper case
		if($by_key === FALSE ) $message_service_type = strtoupper($message_service_type);
		// Check the size of array
		if(!is_array($this->message_service_type_array) || $this->message_service_type_array == '') return false;
		// Check if fetch value by index
		if($by_key === FALSE){
			return isset($this->message_service_type_array[$message_service_type]) ?
				$this->message_service_type_array[$message_service_type] :
				SANDBOX_SERVICE;
		}else{ // Check if fetch index by value
			// Loop through data
			foreach ($this->message_service_type_array as $k0 => $v0) {
				// Check for value match
				if($v0 == $message_service_type) return strtoupper($k0);
			}
			// Return default i.e. sandbox
			return 'SANDBOX_SERVICE';
		}
	}

	/**
	 * Set the account sid
	 * 
	 * @param $sid String
	 * 
	 * @return Instance
	 */
	function setAccountSID($sid){
		$this->account_sid = $sid;
		return $this;
	}

	/**
	 * Set the account token
	 * 
	 * @param $token String
	 * 
	 * @return Instance
	 */
	function setAccountToken($token){
		$this->account_token = $token;
		return $this;
	}

	/**
	 * Set the country iso for fetching
	 * phone numbers
	 * 
	 * @param $iso String
	 * 
	 * @return Instance
	 */
	function setCountryISO($iso){
		$this->country_iso = $iso;
		return $this;
	}

	/**
	 * Set the phonenumber to reserve
	 * 
	 * @param $phone_number String
	 * 
	 * @return Instance
	 */
	function setReservePhone($phone_number){
		$this->reserve_phone_number = $phone_number;
		return $this;
	}

	/**
	 * Set default message service number
	 * 
	 * @param $token String
	 * 
	 * @return Instance
	 */
	function setMessageServicePhoneSid($sid){
		$this->message_service_phone_sid_tmp = $sid;
		return $this;
	}

	/**
	 * Set the message service sid
	 * Messages send useing message service will belong to 
	 * used message service. For testing 'SANDBOX_SERVICE'
	 * is used
	 * 
	 * @param $sid String
	 * 
	 * @return Instance
	 */
	function setMessageServiceSID($sid){
		$this->message_sid = $sid;
		return $this;
	}

	/**
	 * Set the receiving person phone number
	 * 
	 * @param $phone_number String
	 * Only E164 format is allowed 
	 * i.e. +XXXXXXXXXXX
	 * 
	 * @uses resetPhonenumber
	 * 
	 * @return Instance
	 */
	function setReceiverPhone($phone_number){
		$this->receiver_phone = $this->resetPhonenumber($phone_number);
		return $this;
	}

	/**
	 * Set the sender person phone number
	 * 
	 * @param $phone_number String
	 * Only E164 format is allowed 
	 * i.e. +XXXXXXXXXXX
	 * 
	 * @uses resetPhonenumber
	 * 
	 * @return Instance
	 */
	function setSenderPhone($phone_number){
		$this->sender_phone = $this->resetPhonenumber($phone_number);
		return $this;
	}

	/**
	 * Set the content of the message
	 * 
	 * @param $message String
	 * Cannot be empty
	 * 
	 * @return Instance
	 */
	function setMessage($message){
		$this->body = trim($message);
		return $this;
	}

	/**
	 * Send the message to the recipient
	 * 
	 * @uses validate_phonenumber 
	 * to validate the phonenumber format
	 * @uses connect 
	 * to create an instance of a connection with twilio
	 * @uses resetException
	 *
	 * @return Array|String
	 * Message sid wiull be send in case of success
	 * A error array will e sent back in case any error with
	 * description and error code. For system errors the 
	 * error code will be '0'.
	 */
	function sendMessage(){
		// For sandbox always use preset phonenumbers
		if($this->mode === 'sandbox' && !$this->byPassSandbox){
			$this->sender_phone = '+15005550006';
			$this->receiver_phone = '+5571981265131';
		}
		//
		$this->validatePhonenumber($this->receiver_phone, 'receiver');
		if($this->error) return $this->error_array;
		$this->validatePhonenumber($this->sender_phone, 'sender');
		if($this->error) return $this->error_array;
		// Check required fields
		if($this->receiver_phone == '') {
			$this->error_array['Error'] = 'Receiver phone number is missing.';
			$this->error = true;
		}

		if($this->sender_phone == '') {
			$this->error_array['Error'] = 'Sender phone number is missing.';
			$this->error = true;
		}

		if($this->body == '') {
			$this->error_array['Error'] = 'Message body is missing.';
			$this->error = true;
		}
		//
		if($this->error) return $this->error_array;
		// Create connection
		$this->connect();
		// Return error if connection failed
		if($this->error) return $this->error_array;
		// 
		$data = array(
            "body" => $this->body,
            "from" => $this->sender_phone
        );
        //
        if($this->message_sid != '') $data["messagingServiceSid"] = $this->message_sid;
        //
		try {
			$return_obj = 
			$this
			->twilio
			->messages
	        ->create($this->receiver_phone, $data);
        	return array(
        		'MessageSID' => $return_obj->sid, 
        		'DataArray'  => array(
        			'sender_phone_number' => $this->sender_phone,
        			'receiver_phone_number' => $this->receiver_phone,
        			'message_body' => $this->body,
        			'message_mode' => $this->mode,
        			'message_sid' => $return_obj->sid
        		)
        	);
		 	
		 } catch(Exception $e) { return $this->resetException($e); }
	}

	/**
	 * TODO
	 * Receive the callback request from twilio
	 * 
	 * @uses getMessageServiceSidByType
	 * @uses get_instance
	 * @uses parse
	 * 
	 * @return Instance
	 */
	function receiveRequest(){
		// Load cI instance
		$_this = &get_instance();
		// Parse the incoming data
		$parsed_array = $this->parse(
			$_this->uri->segment(
				preg_match('/localhost/', base_url()) ? 3 : 3
			)
		);
		// TOBE removed after testing
		// $_POST['To'] = '+15005550006';
		// $_POST['From'] = '+5571981265131';
		// $_POST['Body'] = 'testing reply.';
		// $_POST['MessageSid'] = 'SM92f881ff7ac347a2bc86dd0d8861f1e1';
		// $_POST['MessagingServiceSid'] = 'MG359e34ef1e42c763d3afc96c5ff28eaf';
		// Actual Code
		// $message_sid = isset($_POST['MessageSid']) ? trim($_POST['MessageSid']) : false;
		// $sender_phone = isset($_POST['From'])      ? trim($_POST['From'])       : false;
		// $receiver_phone = isset($_POST['To'])      ? trim($_POST['To'])         : false;
		// $message_body   = isset($_POST['Body'])    ? trim($_POST['Body'])       : false;
		// $message_service_sid = isset($_POST['MessagingServiceSid']) ? trim($_POST['MessagingServiceSid']) : false;

		$message_sid = $_this->input->post('MessageSid', TRUE) ? $_this->input->post('MessageSid', TRUE) : false;
		$sender_phone = $_this->input->post('From', TRUE)      ? $_this->input->post('From', TRUE) : false;
		$receiver_phone = $_this->input->post('To', TRUE)      ? $_this->input->post('To', TRUE)   : false;
		$message_body   = $_this->input->post('Body', TRUE)    ? $_this->input->post('Body', TRUE) : false;
		$message_service_sid = $_this->input->post('MessagingServiceSid', TRUE) ? $_this->input->post('MessagingServiceSid', TRUE) : false;
		// if message sid is not set then ignore the request
		if(!$message_sid || $message_sid == '') return false;
		// Set data
		$data = array(
			'MessageSid' => $message_sid,
			'DataArray'  => array(
				'sender_phone_number' => $sender_phone,
				'receiver_phone_number' => $receiver_phone,
				'message_body' => $message_body,
				'message_sid' => $message_sid,
				'message_service_sid' => $message_service_sid
			)
		);
		// Set data
		if($message_service_sid != '' && !$message_service_sid) $data['MessagingServiceSid'] = $message_service_sid;
		// Merge data raray with url array
		$data = array_merge($data, $parsed_array);
		//
		return $data;
	}

	/**
	 * Create message service
	 * 
	 * @uses connect
	 * @uses setMessageServicePhone
	 * @uses deleteMessageService
	 * @uses updateMessageService
	 * @uses setMessageAlfaSenderName
	 * @uses resetException
	 * 
	 * @return String
	 */
	function createMessageService(){
		// Set the callback url
		$callback_url = base_url('twilio/callback/'.($this->message_service_name_code).'');
		// Create connection
		$this->connect();
		// Create the message service
		try{
			$obj = $this
			->twilio
			->messaging
			->v1
			->services
			->create(array(
				'friendlyName' => $this->message_service_name
			));
			//
			$this->setMessageServicePhone($obj->sid, $this->message_service_phone_sid_tmp);
			// Return error if phone number assigned is failed
			if($this->error){
				$this->deleteMessageService($obj->sid);
				return $this->error_array;
			}
			//
			$this->updateMessageService($obj->sid, $callback_url);
			// Return error if update callback failed
			if($this->error) {
				$this->deleteMessageService($obj->sid);
				return $this->error_array;
			}
			//
			$alfa = $this->setMessageAlfaSenderName($obj->sid);
			// Return error if alfa sender failed to set
			if($this->error) {
				$this->deleteMessageService($obj->sid);
				return $this->error_array;
			}
			// Fetch the message service code
			return array( 
				'Sid' => $obj->sid, 
				'MessageServiceCode' => $this->message_service_name_code, 
				'CallbackURL' => $callback_url,
				'AlfaSid' => $alfa['Sid'],
				'AlfaName' => $alfa['AlfaName']
			);
		} catch(Exception $e) { return $this->resetException($e); }
	}


	/**
	 * Update message service
	 * 
	 * @param $sid          String
	 * @param $callback_url String
	 * 
	 * @uses resetException
	 * 
	 * @return String
	 */
	function updateMessageService($sid, $callback_url){
		// Create the message service
		try{
			$obj = $this
			->twilio
			->messaging
			->v1
			->services($sid)
			->update(array(
				'inboundRequestUrl' => $callback_url,
				'inboundMethod' => 'POST'
			));
			return true;
		} catch(Exception $e) { return $this->resetException($e); }
	}

	/**
	 * Creates message service code
	 * 
	 * @param $input_array Array
	 * 
	 * @uses safeEncode
	 * 
	 * @return Instance
	 */
	function setMessageServiceCode($input_array){
		if(!sizeof($input_array)) return $this;
		$this->message_service_name = $this->message_service_name_code = '';
		foreach ($input_array as $k0 => $v0) { $this->message_service_name_code .= "$k0=$v0:"; $this->message_service_name .= "$v0:"; };
		$this->message_service_name_code = rtrim($this->message_service_name_code, ':');
		$this->message_service_name = rtrim($this->message_service_name, ':');
		$this->message_service_name = $this->message_service_name  == 0 ? 'Admin' : $this->message_service_name;
		$this->message_service_name_code = $this->safeEncode(strtolower($this->message_service_name_code));
		//
		$this->message_service_phone_sid_tmp = $this->phones->primary->sid;
		return $this;
	}

	/**
	 * Get message service code
	 * 
	 * @return String|Bool
	 */
	function getMessageServiceCode(){
		return $this->message_service_name_code;
	}

	/**
	 * Fetch phone sid
	 * 
	 * @param $message_service_code String
	 * 
	 * @uses connect
	 * @uses resetException
	 * 
	 * @return Bool|String
	 */
	function getMessageServicePhoneSid($message_service_code){
		// Connect to twilio server
		$this->connect();
		// Return error if connection failed
		if($this->error) return $this->error_array;
		// Pin the API
		try{
			$obj = 
			$this
			->twilio
			->messaging
			->v1
			->services($message_service_code)
          	->phoneNumbers
          	->read(array(), 1);
          	// Return data
          	return isset($obj[0]) ? $obj[0]->sid : false;
		} catch(Exception $e){ return $this->resetException($e); }
	}

	/**
	 * Bind number with message service
	 * 
	 * @param $message_service_code String
	 * @param $phone_number_sid Strin
	 * 
	 * @uses connect
	 * @uses resetException
	 * 
	 * @return VOID
	 */
	function setMessageServicePhone($message_service_code, $phone_number_sid){
		// Connect to twilio server
		$this->connect();
		// Return error if connection failed
		if($this->error) return $this->error_array;
		// Pin the API
		try{
			$obj =
			$this
			->twilio
			->messaging
			->v1
			->services($message_service_code)
          	->phoneNumbers
          	->create($phone_number_sid);
          	// Return data
          	return $obj->sid;
		} catch(Exception $e) { return $this->resetException($e); }
	}

	/**
	 * Delete message service
	 * 
	 * @param $message_service_code String
	 * 
	 * @uses connect
	 * @uses resetException
	 * 
	 * @return Integer
	 */
	function deleteMessageService($message_service_code){
		// Connect to twilio server
		$this->connect();
		// Return error if connection failed
		if($this->error) return $this->error_array;
		// Pin the API
		try{
			$obj = 
			$this
			->twilio
			->messaging
			->v1
			->services($message_service_code)
            ->delete();
          	return $obj;
		} catch(Exception $e) { return $this->resetException($e); }
	}

	/**
	 * Fetch numbers
	 *  
	 * @uses connect
	 * @uses resetException
	 * 
	 * @return Integer
	 */
	function availablePhoneNumbers(){
		// Connect to twilio server
		$this->connect();
		// Return error if connection failed
		if($this->error) return $this->error_array;
		// Pin the API
		try{
			$obj = 
			$this
			->twilio
			->availablePhoneNumbers($this->country_iso)
            ->mobile
            ->read(array(), 20);
          	return $obj;
		} catch(Exception $e) { return $this->resetException($e); }
	}


	/**
	 * Fetch local numbers
	 *  
	 * @param $filterArray Array
	 * @param $limit       Integer Optional
	 * Default is '1'
	 *  
	 * @uses connect
	 * @uses resetException
	 * 
	 * @return Integer
	 */
	function availableLocalPhoneNumbers($filterArray, $limit = 1){
		// Connect to twilio server
		$this->connect();
		// Return error if connection failed
		if($this->error) return $this->error_array;
		// Pin the API
		try{
			$obj = 
			$this
			->twilio
			->availablePhoneNumbers($this->country_iso)
            ->local
            ->read(array_merge($this->filterArray, $filterArray), $limit);
            //
            if(!sizeof($obj)){
            	$this->error_array['Error'] = 'Failed to fetch local number for country "'.$this->country_iso.'".';
            	$this->error_array['ErrorCode'] = 0;
            	return $this->error_array;
            }
            if($limit == 1){
	            return array(
	            	'FriendlyName' => trim($obj[0]->friendlyName),
	            	'Number'       => trim($obj[0]->phoneNumber),
	            	'PostalCode'   => trim($obj[0]->postalCode),
	            	'countryISO'   => trim($obj[0]->isoCountry)
	            );
            }

            $number_list = array();
            foreach ($obj as $k0 => $v0) {
            	$number_list[] = array(
	            	'FriendlyName' => trim($v0->friendlyName),
	            	'Number'       => trim($v0->phoneNumber),
	            	'PostalCode'   => trim($v0->postalCode),
	            	'countryISO'   => trim($v0->isoCountry)
	            );
            }

            return $number_list;
		} catch(Exception $e) { return $this->resetException($e); }
	}

	/**
	 * Reserve a number
	 *  
	 * @uses connect
	 * @uses resetException
	 * 
	 * @return Integer
	 */
	function incomingPhoneNumbers(){
		// Connect to twilio server
		$this->connect();
		// Return error if connection failed
		if($this->error) return $this->error_array;
		// Pin the API
		try{
			$obj = 
			$this
			->twilio
			->incomingPhoneNumbers
            ->create(array(
            	"phoneNumber" => $this->reserve_phone_number
            ));
            // Return phone number and it's SID
          	return array( 'Sid' => $obj->sid, 'Number' => $this->reserve_phone_number );
		} catch(Exception $e) { return $this->resetException($e); }
	}

	/**
	 * Get single message by message sid
	 *
	 * @param $sid String
	 *
	 * @uses connect
	 * @uses resetException
	 *
	 * @return Array
	 */
	function fetchMessageBySid($sid){
		// Connect to twilio server
		$this->connect();
		// Return error if connection failed
		if($this->error) return $this->error_array;
		//
		try{
			$resp = $this->twilio->messages($sid)->fetch();
			$return_array = array(
				'Sid' => $resp->sid,
				'To' => $resp->to,
				'From' => $resp->from,
				'Body' => $resp->body,
				'Direction' => $resp->direction,
				'Status' => $resp->status,
				'Price' => $resp->price
			);
			return $return_array;
		} catch(Exception $e) { return $this->resetException($e); }
	}

	/**
	 * Get single message by message sid
	 *
	 * @param $filterArray Array
	 * 'datesent' String
	 * 'from' String e164
	 * 'to' String e164
	 *
	 * @param $limit Integer
	 * Default is 5
	 *
	 * @uses connect
	 * @uses resetException
	 *
	 * @return Array
	 */
	function fetchMessagesList($filterArray = array(), $limit = 5){
		// Connect to twilio server
		$this->connect();
		// Return error if connection failed
		if($this->error) return $this->error_array;
		// Ping API
		try{
			//
			$resp = $this->twilio->messages->read($filterArray, $limit);
			// For error handling
			if(!sizeof($resp)) {
				$this->error_array['Error'] = 'No record found matching the searched criteria';
				$this->error_array['ErrorCode'] = 0;
				return $this->error_array;
			}
			//
			$messages_array = array();
			//
			foreach ($resp as $record) {
				//
				$messages_array[] = array(
					'Sid' => trim($record->sid),
					'To' => trim($record->to),
					'From' => trim($record->from),
					'Body' => trim($record->body),
					'Price' => trim(ltrim($record->price, '-')),
					'PriceUnit' => strtoupper(trim($record->priceUnit)),
					'SentAt' => trim($record->dateSent->format('Y-m-d H:i:s')),
					'MessageServiceSid' => trim($record->messagingServiceSid)
				);
			}
			//
			return $messages_array;
		} catch(Exception $e) { return $this->resetException($e); }
	}


	/**
	 * Set AlphaSender name
	 * 
	 * @param $messageServiceSid  String
	 *
	 * @uses resetException
	 *
	 * @return Array
	 */
	function setMessageAlfaSenderName($messageServiceSid){
		// Connect to twilio server
		$this->connect();
		// Return error if connection failed
		if($this->error) return $this->error_array;
		// Ping API
		try{
			$this->alfaSenderName = ucwords(substr($this->alfaSenderName, 0, 10));
			$resp = $this
			->twilio
			->messaging
			->v1
			->services($messageServiceSid)
	        ->alphaSenders
	        ->create($this->alfaSenderName);
	        //
			return array( 'Sid' => $resp->sid, 'AlfaName' => $this->alfaSenderName );
		} catch(Exception $e) { return $this->resetException($e); }
	}

	/**
	 * Creates a new instance to twilio
	 * 
	 * @return Instance
	 */
	private function connect(){
		$this->error = false;
		if($this->twilio) return $this;
		if($this->account_sid == '' || $this->account_token == '') {
			$this->error_array['Error'] = 'Either account SID or token is empty.';
			return $this->error_array;
		}
		//
		$this->twilio = new Client($this->account_sid, $this->account_token);
		return $this;
	}

	/**
	 * Validates given phone number
	 * 
	 * @param $phone_number String
	 * @param $prefix String Optional
	 * 
	 * @return Instance
	 */
	private function validatePhonenumber($phone_number, $prefix = ''){
		if(!preg_match('/([+])([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/', $phone_number) && strlen($phone_number) < 12) {
		// if(strlen($phone_number) != 12 || !preg_match('/[+][0-9]/', $phone_number)) {
			$this->error_array['Error'] = 'Provided '.($prefix).' phone number format is not valid. Only E164 formats are allowed e.g. +XXXXXXXXXXX.';
			$this->error = true;
		}
	}

	/**
	 * Reset the phone number
	 * 
	 * @param $phone_number String
	 * 
	 * @return Instance
	 */
	private function resetPhonenumber($phone_number){
		$phone_number = trim(preg_replace('/[^0-9]/', '', $phone_number));
		return '+'.((substr($phone_number, 0, 1) != '1' && $this->mode != 'sandbox' ? '1' : '').$phone_number);
	}

	/**
	 * Encodes data
	 * 
	 * @param $input String
	 * 
	 * @uses get_instance
	 * 
	 * @return String
	 */
	private function safeEncode($input){
		// Get CI 'this' instance
		$_this = &get_instance();
		// Load encrypt library
		$_this->load->library('encryption', 'encrypt');
		// Encrypt the string
		$input = $_this->encrypt->encode($input);
		// Clean encoded string and return
		return str_replace(array('/', '=', '+'), array('$a1$', '$b2$', '$c3$'), $input);
	}

	/**
	 * Decode data
	 * 
	 * @param $input String
	 * 
	 * @uses get_instance
	 * 
	 * @return String
	 */
	private function safeDecode($input){
		// Get CI 'this' instance
		$_this = &get_instance();
		// Load encrypt library
		$_this->load->library('encryption', 'encrypt');
		// Clean encoded string
		$input = str_replace(array('$a1$', '$b2$', '$c3$'), array('/', '=', '+'), $input);
		// Return decoded string
		return $_this->encrypt->decode($input);
	}

	/**
	 * Error Exception handler
	 * 
	 * @param $e String
	 * 
	 * @return Array
	 */
	private function resetException($e){
		$a = @json_decode(@json_decode(str_replace('\u0000*\u0000', '', json_encode((array)$e->getTrace()[0]['args'][0])), true)['content'], true);
	 	$this->error = true;
	 	$this->error_array['Error'] = $a['message'];
	 	$this->error_array['ErrorCode'] = $a['code'];
    	return $this->error_array;
	}

	/**
	 * Parse url argument to get company info
	 * 
	 * @param $input String
	 * 
	 * @uses safeDecode 
	 * 
	 * @return Array
	 */
	private function parse($input){
		$input = trim($input);
		$input = explode(':',$this->safeDecode($input));
		if(!sizeof($input)) return array();
		$rt = array();
		foreach ($input as $k0 => $v0) {
			$tmp = explode('=', $v0);
			$rt[$tmp[0]] = str_replace('_service', '', $tmp[1]);
		}
		return array( 'URL' => $rt );
	}
}