<?php defined('BASEPATH') || exit(0);

/**
 * Fake employee data generator
 * 
 * @author AutomotoHR dev team <www.automotohr.com>
 * @version 1.0
 * @package employees
 */
class Fake_employees
{
    /**
     * holds the number of records to be generated
     * @var int
     */
    private $numberOfRecords;

    /**
     * holds the records
     * @var array
     */
    private $fakeEmployees;

    /**
     * holds the locations
     * @var array
     */
    private $locations;

    /**
     * holds the job titles
     * @var array
     */
    private $jobTitles;

    /**
     * main entry point of file
     */
    public function __construct()
    {
        // set record to 10
        $this->numberOfRecords = 10;
        // set the default fake employees array
        $this->fakeEmployees = [];
    }

    /**
     * generate records
     */
    public function init(int $numberOfRecords = 10): array
    {
        // set record to 10
        $this->numberOfRecords = $numberOfRecords;
        // set locations
        $this->setLocations();
        // set job title
        $this->setJobTitle();
        // get data from API
        $this->getDataFromApi();
        //
        return $this->fakeEmployees;
    }

    /**
     * sets the number of records
     *
     * @param int $numberOfRecords
     * @return reference
     */
    private function getDataFromApi()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://randomuser.me/api/?results=' . $this->numberOfRecords . '&inc=name,gender,dob,registered,email,login,picture&nat=us',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //
        if (!$response) {
            return $this->fakeEmployees;
        }
        // convert JSON to array
        $response = json_decode($response, true);
        // set empty array
        $fakeEmployees = [];
        //
        foreach ($response['results'] as $value) {
            // get a random location
            $location = $this->getLocation();
            //
            $fileName = $this->uploadFileToAWS(
                $value['picture']['large'],
                $value['login']['uuid'] . ".jpg"
            );
            // set the array
            $fakeEmployees[] = [
                "username" => "fake" . $value['login']['username'],
                "password" => do_hash("password@123", "md5"),
                "first_name" => $value['name']['first'],
                "last_name" => $value['name']['last'],
                "email" => str_replace("@example.com", "@automotohr.com", $value['email']),
                "gender" => $value['gender'],
                "timezone" => "PST",
                "dob" => explode("T", $value['dob']['date'])[0],
                "joined_at" => explode("T", $value['registered']['date'])[0],
                "registration_date" => explode("T", $value['registered']['date'])[0],
                "Location_Address" => $location['street_1'],
                "Location_Address_2" => $location['street_2'],
                "Location_City" => $location['city'],
                "Location_ZipCode" => $location['zip'],
                "PhoneNumber" => $location['phone'],
                "Location_State" => 6,
                "access_level" => "employee",
                "profile_picture" => $fileName,
                "ssn" => $this->generateSSN(),
                "job_title" => $this->getJobTitle(),
                // "Location_State" => $location['state'],
            ];
        }
        //
        $this->fakeEmployees = $fakeEmployees;
    }

    /**
     * sets the locations
     *
     * @return reference
     */
    private function setLocations()
    {
        // set location array
        $this->locations = json_decode('[{"street_1":"273 Kerry Way","street_2":"","city":"Whittier","zip":"90602","state":"CA","country":"US","phone":"562-789-7666"},{"street_1":"4796 Francis Mine","street_2":"","city":"Janesville","zip":"96114","state":"CA","country":"US","phone":"530-253-6618"},{"street_1":"3789 Pretty View Lane","street_2":"","city":"Sacramento","zip":"95814","state":"CA","country":"US","phone":"707-910-3962"},{"street_1":"2475 Timber Ridge Road","street_2":"","city":"Sacramento","zip":"95814","state":"CA","country":"US","phone":"916-778-9754"},{"street_1":"3615 Woodland Terrace","street_2":"","zip":"95814","phone":"916-951-8576","city":"Sacramento","state":"CA","country":"US"},{"street_1":"4257 Highland View Drive","street_2":"","zip":"95814","phone":"916-596-0507","city":"Sacramento","state":"CA","country":"US"},{"street_1":"4306 Park Avenue","street_2":"","zip":"95814","phone":"916-445-2754","city":"Sacramento","state":"CA","country":"US"},{"street_1":"1599 Woodland Terrace","street_2":"","zip":"95814","phone":"916-874-9466","city":"Sacramento","state":"CA","country":"US"},{"street_1":"1576 Maxwell Farm Road","street_2":"","zip":"94260","phone":"530-933-0175","city":"Sacramento","state":"CA","country":"US"}]', true);
        //
        return $this;
    }

    /**
     * get a random location
     *
     * @return array
     */
    private function getLocation(): array
    {
        return $this->locations[array_rand($this->locations)];
    }

    /**
     * sets the job titles
     *
     * @return reference
     */
    private function setJobTitle()
    {
        // set location array
        $this->jobTitles = json_decode('["Accounting Office","Accounts Payable","Accounts Receivable","Allstate","Allstate Manager ","Attorney","BDC Manager","BDC Representative ","Benefits","Body Shop ","Body Shop Advisor","Body Shop Manager","Body Shop Tech","Café ","Cashier","Controller","Corporate ","Custodian ","Detail ","Detail Manager","Dispatcher","DX Driver ","Express Advisor","F&I","F&I Director","F&I Sales ","Farm ","Farm Manager","Farmer ","Fleet","Fleet Manager ","General Manager","Hockey ","Hockey Manager","Human Resources","Inventory Clerk","Inventory Manager","Lab","Lot Manager","Lube Technician","Maintenance","Marketing ","Parts","Parts Counter person","Parts Driver","Parts Manager ","Payroll","Pilot","Porter ","Receptionist","Sales","Sales Manager ","Sales Representative","Service","Service Advisor","Service Concierge","Service Manager","Service Technician -Flat Rate","Service Technician- Hourly","Shop Foreman","Shuttle Driver","Title Clerk","VAS","VAS Manager","Warranty Administrator"]', true);
        //
        return $this;
    }

    /**
     * get a random job title
     *
     * @return string
     */
    private function getJobTitle(): string
    {
        return $this->jobTitles[array_rand($this->jobTitles)];
    }

    /**
     * upload file to AWS
     *
     * @param string $fileURL
     * @param string $fileName
     * @return string
     */
    private function uploadFileToAWS(string $fileURL, string $fileName): string
    {
        // get CI instance
        $CI = get_instance();
        // load AWS library
        $CI->load->library('aws_lib');
        //
        $options = [
            'Bucket' => AWS_S3_BUCKET_NAME,
            'Key' => $fileName,
            'Body' => getFileData($fileURL),
            'ACL' => 'public-read',
            'ContentType' => getMimeType("jpg")
        ];
        //
        $CI->aws_lib->put_object($options);
        //
        return $fileName;
    }

    /**
     * generate SSN
     *
     * @return int
     */
    private function generateSSN(): int
    {
        return
            number_format(
                mt_rand(100000000, 999999999),
                0,
                "",
                ""
            );
    }
}
