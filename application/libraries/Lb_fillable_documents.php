<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lb_fillable_documents
{
    /**
     * Fillable document list
     * @var array
     */
    private $documentList = [];

    /**
     * main
     */
    public function __construct()
    {
        $this
            ->addDocumentToList("notice of separation")
            ->addDocumentToList("notice of termination of employment")
            ->addDocumentToList("oral employee counselling report form")
            ->addDocumentToList("status and payroll change")
            ->addDocumentToList("written disciplinary action form")
            ;
    }

    /**
     * Get the document data
     *
     * @return array
     */
    public function getDocumentsList(): array
    {
        return $this->documentList;
    }

    /**
     * Set the document data
     *
     * @param string $documentName
     * @return reference
     */
    private function addDocumentToList(string $documentName)
    {
        // convert to slug
        $slug = stringToSlug($documentName, "_");
        // add to list
        $this->documentList[$slug] = [
            "slug" => $slug,
            "name" => ucwords($documentName),
            "description" => get_instance()->load->view("v1/documents/fillable/{$slug}", [], true),
        ];
        return $this;
    }

}
