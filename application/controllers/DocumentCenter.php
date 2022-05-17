<?php
/**
 * Document center
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @author  Mubashir Ahmed <mubashar.ahmed@egenienext.com>
 * @version 1.0
 */
class DocumentCenter extends Public_Controller{
    
    /**
     * Constructor
     * @method __construct
     */
    public function __construct(){
        // Call the parent class constructor
        parent::__construct();
        // Loads the model
        $this->load->model('2022/DocumentCenter_model', 'dc');
        
    }

    /**
     * 
     */
    public function index(){
        //
        $documentId = 4399;
        $userId = 15785;
        $userType = 'employee';
        $assignerId = 15710;
        $companyId = 15708;
        //
        $rt = $this->dc->assignDocument(
            $documentId,
            $userId,
            $userType,
            $assignerId,
            $companyId
        );
        //
        _e($rt, true, true);
    }
}