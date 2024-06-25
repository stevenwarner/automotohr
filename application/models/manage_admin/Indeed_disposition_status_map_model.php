<?php

use function PHPSTORM_META\map;

defined('BASEPATH') || exit('No direct script access allowed');

class Indeed_disposition_status_map_model extends CI_Model
{
    public  function __construct()
    {
        parent::__construct();
    }

    public function getDefaultStatus(): array
    {
        $records = $this->db
            ->select("name")
            ->where('company_sid', 0)
            ->get('application_status')
            ->result_array();
        //
        if (!$records) {
            return $records;
        }
        //
        $tmp = [];
        //
        foreach ($records as $v0) {
            //
            $slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $v0["name"]));
            $v0["slug"] = $slug;
            // 
            $tmp[$slug] = $v0;
        }
        return $tmp;
    }

    public function getCustomStatus(array $defaultNameArray): array
    {
        $records = $this->db
            ->select("name")
            ->where('company_sid <>', 0)
            ->where_not_in('LOWER(REGEXP_REPLACE(name, "[^a-zA-Z]", "")) ', $defaultNameArray)
            ->get('application_status')
            ->result_array();
        //
        if (!$records) {
            return $records;
        }
        //
        $tmp = [];
        //
        foreach ($records as $v0) {
            //
            $slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $v0["name"]));
            // 
            if (!$tmp[$slug]) {
                $v0["slug"] = $slug;
                $tmp[$slug] = $v0;
            }
        }
        return $tmp;
    }

    public function handleMap($statuses): array
    {
        //
        $returnArray = [
            "done" => 0,
            "total" => count($statuses)
        ];
        //
        $dateTime = getSystemDate();
        //
        foreach ($statuses as $status) {
            // set the where
            $where = [
                "ats_slug" => $status["ats_name"]
            ];
            // check if already set then update
            if ($this->db->where($where)->count_all_results("indeed_disposition_status_map")) {
                //
                if ($status["indeed_name"] == "0") {
                    $this->db->where($where)->delete("indeed_disposition_status_map");
                } else {
                    //
                    $returnArray["done"]++;
                    // update
                    $this->db
                        ->where($where)
                        ->update(
                            "indeed_disposition_status_map",
                            [
                                "indeed_slug" => $status["indeed_name"],
                                "updated_at" => $dateTime
                            ]
                        );
                }
            } else {
                if ($status["indeed_name"] != "0") {
                    //
                    $returnArray["done"]++;
                    // insert
                    $this->db
                        ->insert(
                            "indeed_disposition_status_map",
                            [
                                "ats_slug" => $status["ats_name"],
                                "indeed_slug" => $status["indeed_name"],
                                "updated_at" => $dateTime,
                                "created_at" => $dateTime
                            ]
                        );
                }
            }
        }
        //
        return $returnArray;
    }

    public function getMappedStatus(): array
    {
        //
        $records = $this->db->select([
            "ats_slug",
            "indeed_slug",
            "updated_at",
        ])
            ->get("indeed_disposition_status_map")
            ->result_array();
        //
        if (!$records) {
            return $records;
        }
        $tmp = [];
        //
        foreach ($records as $v0) {
            $tmp[$v0["ats_slug"]] = $v0;
        }
        //
        return $tmp;
    }
}
