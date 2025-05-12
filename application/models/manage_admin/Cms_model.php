<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class cms_model extends CI_Model
{

    //
    public function get_pages_data($limit = null, $offset = null, $count_only = false)
    {
        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        if ($count_only == true) {
            return $this->db->count_all_results('cms_pages_new');
        } else {
            $query = $this->db
                ->order_by("sid", "ASC")->get('cms_pages_new');
            return $query->result_array();
        }
    }

    //
    public function get_page_data($sid)
    {
        $this->db->where('sid', $sid);
        $query = $this->db->get('cms_pages_new');
        return $query->row_array();
    }

    //
    public function update_page_data($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('cms_pages_new', $data);
    }

    public function getPageById(int $pageId): array
    {
        //
        return $this->db
            ->select("content")
            ->where("sid", $pageId)
            ->get("cms_pages_new")
            ->row_array();
    }

    public function updatePage($pageId, $data)
    {
        $this->db->where("sid", $pageId)
            ->update("cms_pages_new", [
                "content" => $data,
                "updated_at" => getSystemDate()
            ]);
    }

    /**
     * check if slug already exists
     *
     * @param string $pageSlug
     * @return int
     */
    public function isPageExists(string $pageSlug): int
    {
        return $this->db
            ->where("slug", $pageSlug)
            ->count_all_results("cms_pages_new");
    }

    /**
     * check if slug already exists
     *
     * @param string $pageSlug
     * @param int    $pageId
     * @return int
     */
    public function isPageExistsWithId(string $pageSlug, int $pageId): int
    {
        return $this->db
            ->where("slug", $pageSlug)
            ->where("sid <>", $pageId)
            ->count_all_results("cms_pages_new");
    }

    /**
     * create a new page
     *
     * @param array $data
     * @return int
     */
    public function createPage(array $data): int
    {
        $isFooterLink = 0;
        if ($data["is_footer_link"]) {
            $isFooterLink = 1;
        }

        $isDefault = 0;
        if ($data["is_default"]) {
            $isDefault = 1;
        }

        $this->db
            ->insert(
                "cms_pages_new",
                [
                    "title" => $data["title"],
                    "slug" => $data["slug"],
                    "content" => $data["content"],
                    "created_at" => getSystemDate(),
                    "updated_at" => getSystemDate(),
                    "page" => str_replace('-', '_', $data["slug"]),
                    "is_dynamic" => 1,
                    "status" => 0,
                    "is_footer_link" => $isFooterLink,
                    "is_default" => $isDefault,
                ]
            );
        //
        return $this->db->insert_id();
    }

    /**
     * updates a page
     *
     * @param array $data
     * @param int   $pageId
     */
    public function updateDynamicPage(array $data, int $pageId)
    {

        $isFooterLink = 0;
        if ($data["is_footer_link"]) {
            $isFooterLink = 1;
        }

        $this->db
            ->where("sid", $pageId)
            ->update(
                "cms_pages_new",
                [
                    "title" => $data["title"],
                    "slug" => $data["slug"],
                    "updated_at" => getSystemDate(),
                    "page" => str_replace('-', '_', $data["slug"]),
                    "is_footer_link" => $isFooterLink,
                ]
            );
    }

    /**
     * updates a page status
     *
     * @param string $status
     * @param int    $pageId
     */
    public function updatePageStatus(string $status, int $pageId)
    {
        $this->db
            ->where("sid", $pageId)
            ->update(
                "cms_pages_new",
                [
                    "status" => $status,
                    "updated_at" => getSystemDate(),
                ]
            );
    }

    //

    public function updatePageIsFooterLink(string $status, int $pageId)
    {
        $this->db
            ->where("sid", $pageId)
            ->update(
                "cms_pages_new",
                [
                    "is_footer_link" => $status,
                    "updated_at" => getSystemDate(),
                ]
            );
    }
}