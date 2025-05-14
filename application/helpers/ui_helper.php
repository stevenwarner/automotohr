<?php defined('BASEPATH') || exit('No direct script access allowed');

if (!function_exists('GetTabHeader')) {
    /**
     * Generates the Tabs
     * @param array  $tabs
     * [
     *  [
     *      "Text" => "Tab Text",
     *      "Slug" => "tab",
     *      "Link" => "http://xyz.com/tab"
     *  ]
     * ]
     * @param string $selected
     * "tab"
     * 
     * @return
     */
    function GetTabHeader($tabs, $selected)
    {
        //
        $html = '';
        $html .= '<div role="tabpanel">';
        $html .= '    <!-- Nav tabs -->';
        $html .= '    <ul class="nav nav-tabs">';
        foreach ($tabs as $tab) {
            $html .= '        <li role="presentation" class="' . ($tab['Slug'] === $selected ? 'active' : '') . '">';
            $html .= '            <a href="' . ($tab['Link']) . '" class="csFC4 csF16">' . ($tab['Text']) . '</a>';
            $html .= '        </li>';
        }
        $html .= '    </ul>';
        $html .= '</div>';
        //
        return $html;
    }
}
if (!function_exists('GetTabHeader')) {
    /**
     * Generates the Tabs
     * @param array  $tabs
     * [
     *  [
     *      "Text" => "Tab Text",
     *      "Slug" => "tab",
     *      "Link" => "http://xyz.com/tab"
     *  ]
     * ]
     * @param string $selected
     * "tab"
     * 
     * @return
     */
    function GetTabHeader($tabs, $selected)
    {
        //
        $html = '';
        $html .= '<div role="tabpanel">';
        $html .= '    <!-- Nav tabs -->';
        $html .= '    <ul class="nav nav-tabs">';
        foreach ($tabs as $tab) {
            $html .= '        <li role="presentation" class="' . ($tab['Slug'] === $selected ? 'active' : '') . '">';
            $html .= '            <a href="' . ($tab['Link']) . '" class="csFC4 csF16">' . ($tab['Text']) . '</a>';
            $html .= '        </li>';
        }
        $html .= '    </ul>';
        $html .= '</div>';
        //
        return $html;
    }
}


if (!function_exists('GetScripts')) {
    /**
     * Generates the scripts tags
     * @param array  $tabs
     * [
     *  'assets/js/script'
     * ]
     *
     * @return
     */
    function GetScripts($scripts)
    {
        //
        $html = '';
        //
        foreach ($scripts as $script) {
            //
            if (is_array($script)) {
                $html .= '<script type="text/javascript" src="' . (base_url('assets/' . _m($script[1], 'js', $script[0]))) . '"></script>' . "\n\t";
            } else {
                //
                if (strpos($script, 'http') !== false) {
                    $html .= '<script type="text/javascript" src="' . ($script) . '"></script>' . "\n\t";
                } else {
                    $html .= '<script type="text/javascript" src="' . (base_url('assets/' . _m($script))) . '"></script>' . "\n\t";
                }
            }
        }
        //
        return $html;
    }
}

if (!function_exists('GetCss')) {
    /**
     * Generates the style tags
     * @param array  $tabs
     * [
     *  'assets/js/script'
     * ]
     *
     * @return
     */
    function GetCss($scripts)
    {
        //
        $html = '';
        //
        foreach ($scripts as $script) {
            //
            if (is_array($script)) {
                $html .= '<link  rel="stylesheet" type="text/css"  href="' . (base_url('assets/' . _m($script[1], 'css', $script[0]))) . '">' . "\n\t";
            } else {
                //
                if (strpos($script, 'http') !== false) {
                    $html .= '<link  rel="stylesheet" type="text/css"  href="' . ($script) . '" />' . "\n\t";
                } else {
                    $html .= '<link  rel="stylesheet" type="text/css"  href="' . (base_url('assets/' . _m($script, 'css'))) . '">' . "\n\t";
                }
            }
        }
        //
        return $html;
    }
}

if (!function_exists('bundleJs')) {
    /**
     * Make a bundle of JS files
     *
     * @param array  $files
     * @param string $destination Optional
     * @param string $file Optional
     * @param bool   $lockFile Optional
     * @return string
     */
    function bundleJs(
        array $inputs,
        string $destination = 'assets/v1/app/js/',
        string $file = 'main',
        $lockFile = false
    ) {
        // reset the destination path
        $absolutePath = ROOTPATH . $destination;
        //
        checkAndCreateFileForVersion($destination . $file . ".min.js", "js");
        // check if served over production
        if (MINIFIED === '.min' || $lockFile) {
            //
            $fileName = $destination . $file;
            //
            return
                '<script src="' . (base_url(
                    $destination . $file . '.min.js?v=' . (getStaticFileVersion($fileName, 'js'))
                )) . '"></script>';
        }
        //
        if (!is_dir($absolutePath)) {
            mkdir($absolutePath, true, 0777) || exit('Failed to create path "' . ($absolutePath) . '"');
        }
        // add file to destination
        $absolutePathMin = $absolutePath;
        // add file to destination
        $absolutePath .= $file . '.js';
        $absolutePathMin .= $file . '.min.js';
        // creates a new file
        $handler = fopen($absolutePath, 'w');
        // if failed throw an error
        if (!$handler) {
            exit('Failed to set resources');
        }
        //
        foreach ($inputs as $input) {
            //
            $input = base_url('assets/' . $input . '.js');
            //
            fwrite($handler, file_get_contents($input) . "\n\n");
        }
        //
        fclose($handler);
        // delete the old file first
        unlink($absolutePathMin);
        //
        shell_exec(
            "uglifyjs {$absolutePath} -c -m > {$absolutePathMin}"
        );
        //
        @unlink($absolutePath);
        //
        return '<script src="' . (base_url(
            $destination . $file . '.min.js?v=' . time()
        )) . '"></script>';
    }
}

if (!function_exists('bundleCSS')) {
    /**
     * Make a bundle of CSS files
     *
     * @param array  $files
     * @param string $destination Optional
     * @param string $file Optional
     * @param bool   $lockFile Optional
     * @return string
     */
    function bundleCSS(
        array $inputs,
        string $destination = 'assets/v1/app/css/',
        string $file = 'main',
        $lockFile = false
    ) {
        // reset the destination path
        $absolutePath = ROOTPATH . $destination;
        //
        checkAndCreateFileForVersion($destination . $file . ".min.css", "css");
        // check if served over production
        if (MINIFIED === '.min' || $lockFile) {
            //
            $fileName = $destination . $file;
            //
            return '<link rel="stylesheet" href="' . (base_url(
                $destination .
                $file . '.min.css?v=' . (getStaticFileVersion($fileName, 'css'))
            )) . '" />';
        }
        //
        if (!is_dir($absolutePath)) {
            mkdir($absolutePath, true) || exit('Failed to create path "' . ($absolutePath) . '"');
        }
        // add file to destination
        $absolutePathMin = $absolutePath;
        $absolutePath .= $file . '.css';
        $absolutePathMin .= $file . '.min.css';
        // creates a new file
        $handler = fopen($absolutePath, 'w');
        // if failed throw an error
        if (!$handler) {
            exit('Failed to set resources');
        }
        //
        foreach ($inputs as $input) {
            //
            $input = base_url('assets/' . $input . '.css');
            //
            fwrite($handler, file_get_contents($input) . "\n\n");
        }
        //
        fclose($handler);
        // delete the old file first
        @unlink($absolutePathMin);
        //
        shell_exec(
            "uglifycss {$absolutePath} > {$absolutePathMin}"
        );
        //
        @unlink($absolutePath);
        //
        return '<link rel="stylesheet" href="' . (base_url(
            $destination . $file . '.min.css?v=' . time()
        )) . '" />';
    }
}

if (!function_exists("combineBundles")) {
    /**
     * combine bundles
     *
     * @param array $bundles
     * @return string
     */
    function combineBundle(array $bundles): string
    {
        // holder for bundles
        $bundlesString = '';
        // loop through the bundles
        foreach ($bundles as $bundle) {
            $bundlesString .= "\n" . $bundle;
        }
        // return bundle string
        return $bundlesString;
    }
}

if (!function_exists("checkAndCreateFileForVersion")) {
    /**
     * Create file path wih version
     *
     * @param string $fileWithPath
     * @param string $type js|css
     */
    function checkAndCreateFileForVersion(string $fileWithPath, string $type)
    {
        // set main file path
        $filePath = ROOTPATH . "../protected_files/versions.json";
        //
        $originalData = file_get_contents($filePath);
        // get the file data
        $fileDataArray = json_decode($originalData, true);
        //
        if (!$fileDataArray[$type][$fileWithPath]) {
            $fileDataArray[$type][$fileWithPath] = "4.0.0";
        }
        if (json_decode($originalData) == json_decode(json_encode($fileDataArray))) {
            return;
        }
        // get the file data
        $handler = fopen($filePath, "w");
        fwrite($handler, json_encode($fileDataArray, JSON_PRETTY_PRINT));
        fclose($handler);
    }
}
