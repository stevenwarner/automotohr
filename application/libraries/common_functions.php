<?php

class common_functions {

    //Other funcitons used internally
    public function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function random_key($str_length = 24) {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $bytes = openssl_random_pseudo_bytes(3 * $str_length / 4 + 1);
        $repl = unpack('C2', $bytes);
        $first = $chars[$repl[1] % 62];
        $second = $chars[$repl[2] % 62];
        return strtr(substr(base64_encode($bytes), 0, $str_length), '+/', "$first$second");
    }

    function clean($string) {
        $string = str_replace(' ', '', $string); // Replaces all spaces.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    function clean_domain($string) {
        $string = trim($string, '/');
        if (!preg_match('#^http(s)?://#', $string)) {
            $string = 'http://' . $string;
        }
        $urlParts = parse_url($string);
        $domain = preg_replace('/^www\./', '', $urlParts['host']);
        return $domain;
    }

}

?>