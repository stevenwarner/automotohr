<?php defined("BASEPATH") or exit("No direct access allowed.");

/**
 * Fillable Documents
 */
class Fillable_documents_model extends CI_Model
{
    /**
     * main function
     */
    public function __construct()
    {
        parent::__construct();
        // load the library
        $this->load->library(
            "Lb_fillable_documents",
            "lb_fillable_documents"
        );
    }

    /**
     * Check and assign documents
     *
     * @param int $companyId
     * @return array
     */
    public function checkAndAddFillableDocuments(int $companyId): array
    {
        // get the documents list
        $documents = $this->lb_fillable_documents->getDocumentsList();
        // get the slugs
        $documentSlugs = array_keys(
            $documents
        );
        // check it with table
        if (
            $this->db
            ->where("company_sid", $companyId)
            ->where_in("fillable_document_slug", $documentSlugs)
            ->count_all_results("documents_management")
            == count($documentSlugs)
        ) {
            return ["message" => "Already exists."];
        }
        // get the admin id
        $companyAdminId = getCompanyAdminSid($companyId);
        // current date time
        $dateTime = getSystemDate();
        //
        foreach ($documents as $v0) {
            // check if current document already exists
            if ($this->db
                ->where("company_sid", $companyId)
                ->where("fillable_document_slug", $v0["slug"])
                ->count_all_results("documents_management")
            ) {
                continue;
            }
            // add to the table
            $this->db
                ->insert(
                    "documents_management",
                    [
                        "company_sid" => $companyId,
                        "fillable_document_slug" => $v0["slug"],
                        "employer_sid" => $companyAdminId,
                        "document_title" => $v0["name"],
                        "document_description" => $v0["description"],
                        "document_type" => "generated",
                        "date_created" => $dateTime,
                        "onboarding" => 0,
                        "signature_required" => 1,
                        "isdoctolibrary" => 1,
                        "is_required" => 1,
                    ]
                );
        }
        return ["message" => "Inserted the fillable documents."];
    }
}
