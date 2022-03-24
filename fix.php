<?php

class Cleaner {
    //
    private $basePath = '/var/www/html/automotohr/';
    private $foldersarray = [];
    private $fielsarray = [];

    function searchfile($path = '')
    {
        if(empty($path)) { return;}
        $arrFiles = scandir($path ? $path : $this->basePath, 1);
        foreach ($arrFiles as $file) {
            if (strpos($file, '.') === false) {
                $newPath = $this->basePath . ($path ? str_replace($this->basePath, '', $path) . '/' : '') . $file;
                // folder
                $this->searchfile($newPath);
                $folder_search = 'bak';
                if (preg_match("/{$folder_search}/i", $newPath)) {
                    array_push($this->foldersarray, $newPath);
                }
            } else {
                $search = '.bak';
                if (preg_match("/{$search}/i", $file)) {
                    $backfiles = $path . "/" . $file;
                    array_push($this->fielsarray, $backfiles);
                }
            }
        }
    }
    
    function getbackfiles()
    {
        $this->searchfile();
        echo "Folders <br>";
        print_r($this->foldersarray);
        echo "<br><br> Files";
        print_r($this->fielsarray);

        // Delete the folders
        // foreach($this->foldersarray as $file){
        //     shell_exec("rm -r ".$file);
        // }

        // Delete the files
        // foreach($this->fielsarray as $file){
        //     unlink($file);
        // }
    }
}


$o = new Cleaner();

echo '<pre>';
$o->getbackfiles();
