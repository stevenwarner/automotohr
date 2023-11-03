<?php defined('BASEPATH') || exit('No direct script access allowed');
class Benefits_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * get all benefit categories
     *
     * @param bool $makeIndex
     * @return array
     */
    public function getBenefitCategories(bool $makeIndex = false): array
    {
        $records = $this->db
            ->select('
                sid,
                name,
                created_at
            ')
            ->order_by('sid', 'DESC')
            ->get('benefit_categories')
            ->result_array();
        //
        if (!$records) {
            return $records;
        }
        //
        if ($makeIndex) {
            //
            $tmp = [];
            //
            foreach ($records as $record) {
                $tmp[$record['sid']] = $record;
            }
            //
            $records = $tmp;
            //
            unset($tmp);
        }
        //
        return $records;
    }

    /**
     * get benefit category by id
     *
     * @param int $benefitCategoryId
     * @return array
     */
    public function getBenefitCategoryById(int $benefitCategoryId): array
    {
        return $this->db
            ->select('
                sid,
                name,
                description
            ')
            ->where('sid', $benefitCategoryId)
            ->get('benefit_categories')
            ->row_array();
    }

    /**
     * get all benefits
     *
     * @return array
     */
    public function getBenefits(): array
    {
        $records = $this->db
            ->select('
                sid,
                name,
                pretax,
                posttax,
                imputed,
                healthcare,
                retirement,
                yearly_limit,
                benefit_categories_sid,
                created_at
            ')
            ->order_by('sid', 'DESC')
            ->get('benefits')
            ->result_array();
        //
        if (!$records) {
            return $records;
        }
        //
        $categories = $this->getBenefitCategories(true);
        //
        foreach ($records as $key => $value) {
            $records[$key]['benefit_type'] = $categories[$value['benefit_categories_sid']]['name'];
        }
        //
        return $records;
    }

    /**
     * get benefit by id
     *
     * @param int $benefitId
     * @return array
     */
    public function getBenefitById(int $benefitId): array
    {
        return $this->db
            ->select('
                sid,
                name,
                pretax,
                posttax,
                imputed,
                healthcare,
                retirement,
                yearly_limit,
                benefit_categories_sid
            ')
            ->where('sid', $benefitId)
            ->get('benefits')
            ->row_array();
    }

    /**
     * saves the benefit category
     *
     * @param array $data
     * @return array
     */
    public function saveBenefitCategory(array $data): array
    {
        // check if already exists
        if ($this->db->where('name', $data['name'])->count_all_results('benefit_categories')) {
            return ['errors' => ['"' . ($data['name']) . '" already exists']];
        }
        //
        $ins = $data;
        //
        $ins['created_at']
            = $ins['updated_at'] =
            getSystemDate();
        //
        $this->db->insert(
            'benefit_categories',
            $ins
        );
        //
        return ['success' => true, 'msg' => 'You have successfully added the benefit category.'];
    }

    /**
     * updates the benefit category
     *
     * @param array $data
     * @param int   $categoryId
     * @return array
     */
    public function updateBenefitCategory(array $data, int $categoryId): array
    {
        // check if already exists
        if ($this->db->where('name', $data['name'])->where('sid <> ', $categoryId)->count_all_results('benefit_categories')) {
            return ['errors' => ['"' . ($data['name']) . '" already exists']];
        }
        //
        $ins = $data;
        //
        $ins['updated_at'] =
            getSystemDate();
        //
        $this->db
            ->where('sid', $categoryId)
            ->update(
                'benefit_categories',
                $ins
            );
        //
        return ['success' => true, 'msg' => 'You have successfully updated the benefit category.'];
    }

    /**
     * saves the benefit
     *
     * @param array $data
     * @return array
     */
    public function saveBenefit(array $data): array
    {
        // check if already exists
        if ($this->db->where('name', $data['name'])->count_all_results('benefits')) {
            return ['errors' => ['"' . ($data['name']) . '" already exists']];
        }
        //
        $ins = [];
        $ins['name'] = $data['name'];
        $ins['description'] = $data['description'];
        $ins['pretax'] = $data['pretax'] == 'yes' ? true : false;
        $ins['posttax'] = $data['posttax'] == 'yes' ? true : false;
        $ins['imputed'] = $data['imputed'] == 'yes' ? true : false;
        $ins['retirement'] = $data['retirement'] == 'yes' ? true : false;
        $ins['healthcare'] = $data['healthcare'] == 'yes' ? true : false;
        $ins['yearly_limit'] = $data['yearly_limit'] == 'yes' ? true : false;
        $ins['benefit_categories_sid'] = $data['benefit_type'];
        //
        $ins['created_at']
            = $ins['updated_at'] =
            getSystemDate();
        //
        $this->db->insert(
            'benefits',
            $ins
        );
        //
        return ['success' => true, 'msg' => 'You have successfully added a benefit.'];
    }

    /**
     * updates the benefit
     *
     * @param array $data
     * @param int $benefitId
     * @return array
     */
    public function updateBenefit(array $data, int $benefitId): array
    {
        // check if already exists
        if ($this->db->where('name', $data['name'])->where('sid <> ', $benefitId)->count_all_results('benefits')) {
            return ['errors' => ['"' . ($data['name']) . '" already exists']];
        }
        //
        $upd = [];
        $upd['name'] = $data['name'];
        $upd['description'] = $data['description'];
        $upd['pretax'] = $data['pretax'] == 'yes' ? true : false;
        $upd['posttax'] = $data['posttax'] == 'yes' ? true : false;
        $upd['imputed'] = $data['imputed'] == 'yes' ? true : false;
        $upd['retirement'] = $data['retirement'] == 'yes' ? true : false;
        $upd['healthcare'] = $data['healthcare'] == 'yes' ? true : false;
        $upd['yearly_limit'] = $data['yearly_limit'] == 'yes' ? true : false;
        $upd['benefit_categories_sid'] = $data['benefit_type'];
        //
        $upd['updated_at'] =
            getSystemDate();
        //
        $this->db
            ->where('sid ', $benefitId)
            ->update(
                'benefits',
                $upd
            );
        //
        return ['success' => true, 'msg' => 'You have successfully updated a benefit.'];
    }

    /**
     * get all benefit categories
     *
     * @param bool $makeIndex
     * @return array
     */
    public function getBenefitCarriers(bool $makeIndex = false): array
    {
        $records = $this->db
            ->select('
                sid,
                name,
                code,
                created_at
            ')
            ->order_by('sid', 'DESC')
            ->get('benefits_carrier')
            ->result_array();
        //
        if (!$records) {
            return $records;
        }
        //
        if ($makeIndex) {
            //
            $tmp = [];
            //
            foreach ($records as $record) {
                $tmp[$record['sid']] = $record;
            }
            //
            $records = $tmp;
            //
            unset($tmp);
        }
        //
        return $records;
    }

    /**
     * saves the benefit carrier
     *
     * @param array $data
     * @return array
     */
   public function saveBenefitCarrier(array $data): array
   {
       // check if already exists
       if ($this->db->where('ein', $data['ein'])->count_all_results('benefits_carrier')) {
           return ['errors' => ['"' . ($data['ein']) . '" already exists']];
       }
       //
       $ins = $data;
       //
       $ins['created_at']
           = $ins['updated_at'] =
           getSystemDate();
       //
       $this->db->insert(
           'benefits_carrier',
           $ins
       );
       //
       return ['success' => true, 'msg' => 'You have successfully added the benefit carrier.'];
   }

    /**
     * get benefit carrier by id
     *
     * @param int $carrierId
     * @return array
     */
    public function getBenefitCarrierById(int $carrierId): array
    {
        return $this->db
            ->select('
                sid,
                name,
                ein,
                code,
                logo
            ')
            ->where('sid', $carrierId)
            ->get('benefits_carrier')
            ->row_array();
    }

    /**
     * updates the benefit carrier
     *
     * @param array $data
     * @param int $carrierId
     * @return array
     */
    public function updateBenefitCarrier(array $data, int $carrierId): array
    {
        // check if already exists
        if ($this->db->where('ein', $data['ein'])->where('sid <> ', $carrierId)->count_all_results('benefits_carrier')) {
            return ['errors' => ['"' . ($data['ein']) . '" already exists']];
        }
        //
        $ins = $data;
        //
        $ins['updated_at'] =
            getSystemDate();
        //
        $this->db
            ->where('sid', $carrierId)
            ->update(
                'benefits_carrier',
                $ins
            );
        //
        return ['success' => true, 'msg' => 'You have successfully updated the benefit carrier.'];
    }

    /**
    * get all benefit categories
    *
    * @param int $categoryId
    * @return array
    */
   public function getBenefitCategoryPlans(int $categoryId): array
   {
       $records = $this->db
           ->select('
               sid,
               name,
               code,
               created_at
           ')
           ->order_by('sid', 'DESC')
           ->get('benefits_carrier')
           ->result_array();
       //
       if (!$records) {
           return $records;
       }
       //
       if ($makeIndex) {
           //
           $tmp = [];
           //
           foreach ($records as $record) {
               $tmp[$record['sid']] = $record;
           }
           //
           $records = $tmp;
           //
           unset($tmp);
       }
       //
       return $records;
   }
}
