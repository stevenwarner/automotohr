<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(base_url() == 'http://localhost/automotoCI'){
	include_once __DIR__ . '\Google\autoload.php';
}else {
	include_once __DIR__ . '/Google/autoload.php';
}
class Google_auth {

	function Authorize($token){
		$client =  new Google_Client();
		//$client->setAuthConfig('{"web":{"client_id":"499875028110-ocqp59ssk7lbsvfrd1u1mforvl4ebc46.apps.googleusercontent.com","project_id":"automothr","auth_uri":"https://accounts.google.com/o/oauth2/auth","token_uri":"https://accounts.google.com/o/oauth2/token","auth_provider_x509_cert_url":"https://www.googleapis.com/oauth2/v1/certs","client_secret":"d4O1LQ3r_z7grbO7xbm_2TPg","redirect_uris":["http://intranet.dev","http://localhost"],"javascript_origins":["http://localhost","http://intranet.dev"]}}');
		$client->setAuthConfig('{"web":{"client_id":"145015201408-am8hodqg44j3gi5483494mhjp7tikakj.apps.googleusercontent.com","project_id":"automotohr-live","auth_uri":"https://accounts.google.com/o/oauth2/auth","token_uri":"https://accounts.google.com/o/oauth2/token","auth_provider_x509_cert_url":"https://www.googleapis.com/oauth2/v1/certs","client_secret":"0i5bPiF_1orHbguyi05EmvxU","redirect_uris":["'.STORE_FULL_URL_SSL.'","http://intranet.dev","http://localhost"],"javascript_origins":["'.STORE_FULL_URL_SSL.'","http://intranet.dev","http://localhost"]}}');
		//$client->setAuthConfig('{"installed":{"client_id":"145015201408-a6vejo73c3elhodoue3v6a48np7u1nia.apps.googleusercontent.com","project_id":"automotohr-live","auth_uri":"https://accounts.google.com/o/oauth2/auth","token_uri":"https://accounts.google.com/o/oauth2/token","auth_provider_x509_cert_url":"https://www.googleapis.com/oauth2/v1/certs","client_secret":"ZxcOleUSzbGII42ilT0hDUfO","redirect_uris":["urn:ietf:wg:oauth:2.0:oob","http://localhost"]}}');
		//$client->refreshToken($token);
		$client->setAccessToken($token);



		$client->setScopes(array('https://www.googleapis.com/auth/drive'));

		return $client;
	}

	function printFile($service, $fileId) {
		try {
			$file = $service->files->get($fileId);

			//print "Title: " . $file->getName();
			//print "Description: " . $file->getDescription();
			//print "MIME type: " . $file->getMimeType();
			//print "Download URL: : " . $file->getDownloadUrl();

			return $file;
		} catch (Exception $e) {
			print "An error occurred: " . $e->getMessage();
		}
	}

}

/* End of file GoogleAuth.php */