<?php defined('BASEPATH') || exit('No direct script access allowed');

class Webhook extends Public_controller
{
    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
        // Call the model
        $this->load->model("v1/Webhook_model", "webhook_model");
    }


    public function listen(string $type)
    {
        // check if there is incoming json
        $json = file_get_contents("php://input");
        //
        if (!$json) {
            return SendResponse(
                400,
                [
                    "errors" => [
                        "Missing data."
                    ]
                ]
            );
        }
        // set function name
        $func = "process" . (ucwords($type));
        // check if function exists
        if (!in_array($func, get_class_methods($this->webhook_model))) {
            return SendResponse(
                400,
                [
                    "errors" => [
                        "Type is not allowed."
                    ]
                ]
            );
        }
        // let's log the call
        $this->webhook_model->saveWebhookCall(
            $type,
            $json
        );
        // call the function
        $this->webhook_model->$func(
            json_decode($json, true)
        );
        // send response
        return SendResponse(
            200,
            [
                "message" => "Processed."
            ]
        );
    }
}
