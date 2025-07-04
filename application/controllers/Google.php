<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Google extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('google_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
        $this->load->library('google_auth');
    }

    public function index($unique_key = null) {
        if ($unique_key != null) {
            $data = array();
            $data['unique_key'] = $unique_key;
            $data['title'] = 'Attach Resume From Cloud Storage';
            $this->load->view('Google/attach_resume_from_google_drive', $data);
        } else {
            echo 'Unique Key Required.';
        }
    }

    public function ajax_responder() {
        if (array_key_exists('perform_action', $_POST)) {
            $perform_action = strtoupper($_POST['perform_action']);

            switch ($perform_action) {
                case 'GETFILECONTENT':
                    $myToken = $_POST['token'];
                    //$downloadUrl = $_POST['url'];
                    $fileId = $_POST['document'];
                    $documentInfo = $_POST['docinfo'];
                    $documentInfo = urldecode($documentInfo);
                    $documentInfo = json_decode($documentInfo);
                    $token = array( 'access_token' => $myToken,
                                    'refresh_token' => $myToken);

                    $json_token = json_encode($token);
                    $myClient = $this->google_auth->Authorize($json_token);
                    $myService = new Google_Service_Drive($myClient);
                    //$fileId = '1ZdR3L3qP4Bkq8noWLJHSr_iBau0DNT4Kli4SxNc2YEo';
                    //$file = $myService->files->get($fileId);
                    $fileType = $documentInfo->mimeType;
                    $fileName = $documentInfo->name;

                    if ($fileType == 'application/vnd.google-apps.document') {
                        $fileType = 'application/rtf';
                    }

                    $fileExtension = explode('/', $fileType);
                    $fileExtension = $fileExtension[1];
                    $fileContent = $myService->files->export($fileId, $fileType, array('alt' => 'media'));
                    //making Directory to store
                    $filePath = FCPATH . "assets/temp_files/";

                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777);
                    }

                    $fileNameWithExt = clean($fileName) . '.' . $fileExtension;
                    //Write Temporary File on Server
                    $tempFile = fopen($filePath . $fileNameWithExt, 'w');
                    fwrite($tempFile, $fileContent);
                    fclose($tempFile);
                    //Upload To Aws
                    $resume = generateRandomString(6) . "_" . $fileNameWithExt;
                    $aws = new AwsSdk();
                    $aws->putToBucket($resume, $filePath . $fileNameWithExt, AWS_S3_BUCKET_NAME);

                    if (isset($_POST['unique_key']) && $_POST['unique_key'] != '') {
                        $unique_key = $_POST['unique_key'];
                        $awsFileName = $resume;
                        $this->google_model->Save(null, $unique_key, $awsFileName);
                    }


                    echo $resume;
                    break;
                case 'DROPBOXGETFILECONTENT':
                    $fileName = $_POST['name'];
                    $fileUrl = $_POST['url'];
                    $fileUrl = str_split($fileUrl, strpos($fileUrl, '?'));
                    $fileUrl = $fileUrl[0] . '?dl=1';
                    $fileContent = downloadFileFromDropbox($fileUrl);
                    // $fileContent = file_get_contents($fileUrl);
                    $filenameArray = str_split($fileName, strpos($fileName, '.'));
                    $fileNameOnly = clean($filenameArray[0]);
                    $fileExtension = $filenameArray[1];
                    $fileNameWithExt = $fileNameOnly . $fileExtension;
                    //making Directory to store
                    $filePath = FCPATH . "assets/temp_files/";

                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777);
                    }

                    //Write Temporary File on Server
                    $tempFile = fopen($filePath . $fileNameWithExt, 'w');
                    fwrite($tempFile, $fileContent);
                    fclose($tempFile);
                    //Upload To Aws
                    $resume = generateRandomString(6) . "_" . $fileNameWithExt;
                    $aws = new AwsSdk();
                    $aws->putToBucket($resume, $filePath . $fileNameWithExt, AWS_S3_BUCKET_NAME);

                    if (isset($_POST['unique_key']) && $_POST['unique_key'] != '') {
                        $unique_key = $_POST['unique_key'];
                        $awsFileName = $resume;
                        $this->google_model->Save(null, $unique_key, $awsFileName);
                    }

                    echo $resume;
                    break;
                default:
                    break;
            }
        }
    }

}
