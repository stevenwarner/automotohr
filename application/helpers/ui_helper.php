<?php defined('BASEPATH') || exit('No direct script access allowed');

if(!function_exists('GetTabHeader')){
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
    function GetTabHeader($tabs, $selected){
        //
        $html = '';
        $html .= '<div role="tabpanel">';
        $html .= '    <!-- Nav tabs -->';
        $html .= '    <ul class="nav nav-tabs">';
        foreach($tabs as $tab){
            $html .= '        <li role="presentation" class="'.($tab['Slug'] === $selected ? 'active' : '').'">';
            $html .= '            <a href="'.($tab['Link']).'" class="csFC4 csF16">'.($tab['Text']).'</a>';
            $html .= '        </li>';
        }
        $html .= '    </ul>';
        $html .= '</div>';
        //
        return $html;
    }
}
if(!function_exists('GetTabHeader')){
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
    function GetTabHeader($tabs, $selected){
        //
        $html = '';
        $html .= '<div role="tabpanel">';
        $html .= '    <!-- Nav tabs -->';
        $html .= '    <ul class="nav nav-tabs">';
        foreach($tabs as $tab){
            $html .= '        <li role="presentation" class="'.($tab['Slug'] === $selected ? 'active' : '').'">';
            $html .= '            <a href="'.($tab['Link']).'" class="csFC4 csF16">'.($tab['Text']).'</a>';
            $html .= '        </li>';
        }
        $html .= '    </ul>';
        $html .= '</div>';
        //
        return $html;
    }
}


if(!function_exists('GetScripts')){
    /**
     * Generates the scripts tags
     * @param array  $tabs
     * [
     *  'assets/js/script'
     * ]
     *
     * @return
     */
    function GetScripts ($scripts)
    {
        //
        $html = '';
        //
        foreach ($scripts as $script) {
            //
            if (is_array($script)) {
                $html .= '<script type="text/javascript" src="'.(base_url('assets/'._m($script[1], 'js', $script[0]))).'"></script>';
            } else {
                $html .= '<script type="text/javascript" src="'.(base_url('assets/'._m($script))).'"></script>';
            }
        }
        //
        return $html;
    }
}

if(!function_exists('GetCss')){
    /**
     * Generates the style tags
     * @param array  $tabs
     * [
     *  'assets/js/script'
     * ]
     *
     * @return
     */
    function GetCss ($scripts)
    {
        //
        $html = '';
        //
        foreach ($scripts as $script) {
            //
            if (is_array($script)) {
                $html .= '<link  rel="stylesheet" type="text/css"  href="'.(base_url('assets/'._m($script[1], 'css', $script[0]))).'"></script>';
            } else {
                $html .= '<link  rel="stylesheet" type="text/css"  href="'.(base_url('assets/'._m($script, 'css'))).'"></script>';
            }
        }
        //
        return $html;
    }
}