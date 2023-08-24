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
                    $html .= '<script type="text/javascript" src="' . ($script) . '"></script>'."\n\t";
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
     * @return string
     */
    function bundleJs(
        array $inputs,
        string $destination = ROOTPATH
    )
    {
        //
        $file = 'assets/v1/app/js/app'.(MINIFIED).'.js';
        $destination .= $file;
        //
        $handler = fopen($destination, 'w');
        //
        if (!$handler) {
            exit('Failed to set resources');
        }
        //
        foreach ($inputs as $input) {
            //
            $input = base_url('assets/' . $input .(MINIFIED).'.js');
            //
            fwrite($handler, file_get_contents($input) . "\n\n");
        }
        //
        fclose($handler);
        //
        return base_url(
            $file.'?v='.(MINIFIED === '.min' ? '1.0.0' : time())
        );
    }
}

if (!function_exists('bundleCSS')) {
    /**
     * Make a bundle of CSS files
     *
     * @param array  $files
     * @param string $destination Optional
     * @return string
     */
    function bundleCSS(
        array $inputs,
        string $destination = ROOTPATH
    ) {
        //
        $file = 'assets/v1/app/css/app' . (MINIFIED) . '.js';
        $destination .= $file;
        //
        $handler = fopen($destination, 'w');
        //
        if (!$handler) {
            exit('Failed to set resources');
        }
        //
        foreach ($inputs as $input) {
            //
            $input = base_url('assets/' . $input . (MINIFIED) . '.css');
            //
            fwrite($handler, file_get_contents($input) . "\n\n");
        }
        //
        fclose($handler);
        //
        return base_url(
            $file . '?v=' . (MINIFIED === '.min' ? '1.0.0' : time())
        );
    }
}