<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * ComplyNet
 *
 * @version 1.0
 */
class Complynet_cron extends CI_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        //
        parent::__construct();
        //
        $this
            ->load
            ->model(
                '2022/Complynet_cron_model',
                'complynet_cron_model'
            );
    }

    /**
     * sync companies with ComplyNet
     */
    public function syncStoreToComplyNet()
    {
        $this->complynet_cron_model->syncStoreToComplyNet();
    }
    
    
    /**
     * sync companies with ComplyNet
     */
    public function syncEmployeesToComplyNet()
    {
        $this->complynet_cron_model->syncEmployeesToComplyNet();
    }
}
