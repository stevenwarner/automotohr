<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_file extends CI_Controller {

    /**
     * Holds the file
     */
    private $file;

    /**
     * Holds the filtered post
     * request
     */
    private $post;

    /**
     * Holds server response
     */
    private $resp;
    
    /**
     * Holds the name of the
     * file
     */
    private $fileName;
    
    /**
     * By default 30 mb file size is 
     * allowed
     */
    private $allowedSize = 30 * 1000000;

    /**
     * Holds the name of the library table
     */
    private $table_name = 'files_library';
    
    /**
     * Constructor function calls when the class
     * object is created
     * 
     * @method redirect
     * @method res
     * @method base_url
     * @method is_ajax_request
     * @method userdata
     * @method post
     */
    public function __construct() {
        //
        parent::__construct();
        // Check user session
        if (!$this->session->userdata('logged_in')) {
            return redirect(base_url('login'));
        }
        // Set default response
        $this->resp = ['Status' => false, 'Response' => 'Invalid Request'];
        // Check if proper request was not set
        if (
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post(NULL, TRUE)) ||
            empty($_FILES)
        ) {
            return res($this->resp);
        }
        // Save the filetered post
        $this->post = $this->input->post(NULL, TRUE);
        // Save the incoming file to local
        // variable
        $this->file = $_FILES['file'];
    }

    /**
     * Upload the file onto
     * S3 bucket
     */
    function UploadFile(){
        // Check for errors
        $this->CheckForErrors();
        // Rename image
        $this->RenameImage();
        // Upload to S3
        $this->MoveToS3();
        // Save file to library
        $this->SaveToLibrary();
    }

    /**
     * Upload file to S3
     * 
     * @method getMimeType
     * @method res
     * @method put_object
     * 
     * @return
     */
    private function MoveToS3(){
        //
        $this->load->library('aws_lib');
        //
        $options = [
            'Bucket' => AWS_S3_BUCKET_NAME,
            'Key' => $this->fileName,
            'Body' => file_get_contents($this->file['tmp_name']),
            'ACL' => 'public-read',
            'ContentType' => $this->getMimeType()
        ];
        //
        $result = $this->aws_lib->put_object($options);
        //
        if(isset($result['ObjectURL'])){
            //
            $this->resp['Status'] = true;
            $this->resp['File'] = [
                'Name' => $this->fileName,
                'URL' => $result['ObjectURL']
            ];
            $this->resp['Response'] = 'Proceed';
            return;
        }
        //
        $this->resp['Response'] = 'Failed to upload file.';
        //
        return res($this->resp);
    }

    /**
     * Saves the uploaded file to
     * file library for displaying
     */
    private function SaveToLibrary(){
        //
        $this->db->insert(
            $this->table_name, [
                'user_sid' => $this->post['user_sid'],
                'user_type' => $this->post['user_type'],
                'file_name' => $this->fileName,
                'creator_sid' => $this->post['creator_sid'],
                'module_name' => $this->post['module_name'],
                'created_at' => date('Y-m-d H:i:s', strtotime('now'))
            ]
        );
        //
        $insertId = $this->db->insert_id();
        //
        if($insertId){
            $this->resp['File']['Id'] = $insertId;
        }
        //
        return res($this->resp);
    }

    /**
     * Validate; 
     * Incoming file has errors
     * Incoming file size is higher than allowed
     * Incoming file extension is allowed
     * @return
     */
    private function CheckForErrors(){
        //
        if($this->file['error'] != 0){
            //
            $this->resp['Response'] = $this->file['error'];
            //
            return res($this->resp);
        }
        // Allowed formats
        if(!$this->Extension()){
            //
            $this->resp['Response'] = 'File extension not allowed.';
            //
            return res($this->resp);
        }
        // allowedsize
        if($this->file['size'] > $this->allowedSize){
            //
            $this->resp['Response'] = 'File size exceeded '.(ceil($this->allowedSize/1000000)).'MB';
            //
            return res($this->resp);
        }
    }

    /**
     * Check file extension
     * @return
     */
    private function Extension(){
        //
        $listFormats = [
            ".aac" => "audio/aac",
            ".abw" => "application/x-abiword",
            ".arc" => "application/x-freearc",
            ".avi" => "video/x-msvideo",
            ".azw" => "application/vnd.amazon.ebook",
            ".bin" => "application/octet-stream",
            ".bmp" => "image/bmp",
            ".bz" => "application/x-bzip",
            ".bz2" => "application",
            ".csh" => "application/x-csh",
            ".css" => "text/css",
            ".csv" => "text/csv",
            ".doc" => "application/msword",
            ".docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            ".eot" => "application/vnd.ms-fontobject",
            ".epub" => "application/epub+zip",
            ".gz" => "application/gzip",
            ".gif" => "image/gif",
            ".html" => "text/html",
            ".ico" => "image/vnd.microsoft.icon",
            ".ics" => "text/calendar",
            ".jar" => "application/java-archive",
            ".jpeg" => "image/jpeg",
            ".js" => "text/javascript",
            ".json" => "application/json",
            ".jsonld" => "application/ld+json",
            ".mid" => "audio/x-midi",
            ".mjs" => "text/javascript",
            ".mp3" => "audio/mpeg",
            ".mpeg" => "video/mpeg",
            ".mpkg" => "application/vnd.apple.installer+xml",
            ".odp" => "application/vnd.oasis.opendocument.presentation",
            ".ods" => "application/vnd.oasis.opendocument.spreadsheet",
            ".odt" => "application/vnd.oasis.opendocument.text",
            ".oga" => "audio/ogg",
            ".ogv" => "video/ogg",
            ".ogx" => "application/ogg",
            ".opus" => "audio/opus",
            ".otf" => "font/otf",
            ".png" => "image/png",
            ".pdf" => "application/pdf",
            ".php" => "application/x-httpd-php",
            ".ppt" => "application/vnd.ms-powerpoint",
            ".pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation",
            ".rar" => "application/vnd.rar",
            ".rtf" => "application/rtf",
            ".sh" => "application/x-sh",
            ".svg" => "image/svg+xml",
            ".swf" => "application/x-shockwave-flash",
            ".tar" => "application/x-tar",
            ".tif" => "image/tiff",
            ".ts" => "video/mp2t",
            ".ttf" => "font/ttf",
            ".txt" => "text/plain",
            ".vsd" => "application/vnd.visio",
            ".wav" => "audio/wav",
            ".weba" => "audio/webm",
            ".webm" => "video/webm",
            ".webp" => "image/webp",
            ".woff" => "font/woff",
            ".woff2" => "font/woff2",
            ".xhtml" => "application/xhtml+xml",
            ".xls" => "application/vnd.ms-excel",
            ".xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            ".xml" => "application/xml",
            ".xul" => "application/vnd.mozilla.xul+xml",
            ".zip" => "application/zip",
            ".3gp" => "audio/3gpp",
            ".3g2" => "audio/3gpp2",
            ".7z" => "application/x-7z-compressed"
        ];
        //
        $newList = array_flip($listFormats);
        //           
        return isset($newList[strtolower($this->file['type'])]) ? $newList[strtolower($this->file['type'])] : false;
    }

    /**
     * Rename the uploaded file name
     * 
     * @method StringToName
     * @method getFileNameWithoutExtension
     * @method generateRandomString
     */
    private function RenameImage(){
        //
        $this->fileName = $this->StringToName(
            (array_key_exists('postfix', $this->post) ? ($this->post['postfix']).'_' : '')
            .$this->getFileNameWithoutExtension()
            .'_'.(generateRandomString(5))
        ).$this->Extension();
    }

    /**
     * Convert string to _
     * @param String $str
     * @return
     */
    private function StringToName($str){
        return preg_replace('/[^a-zA-Z0-9_]/', '_', preg_replace('/\s+/', ' ', strtolower($str)));
    }
    
    /**
     * Get filename without extension
     * @return
     */
    private function getFileNameWithoutExtension(){
        //
        return explode('.', $this->file['name'])[0];
    }

    /**
     * Get the mime type of the file
     * @return
     */
    private function getMimeType(){
        //
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'docx' => 'application/msword',
            'xlsx' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.ms-powerpoint',


            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        //
        $ext = str_replace('.', '', $this->Extension());
        //
        $mimetype = '';
        if (function_exists('mime_content_type')) {
            $mimetype = @mime_content_type($this->fileName);
        }
        if (empty($mimetype) && function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = @finfo_file($finfo, $this->fileName);
            finfo_close($finfo);
        }
        if (empty($mimetype) && array_key_exists($ext, $mime_types)) {
            $mimetype = $mime_types[$ext];
        }
        if (empty($mimetype)) {
            $mimetype = 'application/octet-stream';
        }
        return $mimetype;
    }
}