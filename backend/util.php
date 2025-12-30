<?php

function generateTempPassword($length = 12) {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%^&*';
    $result = '';
    $max = strlen($chars) - 1;

    for ($i = 0; $i < $length; $i++) {
        $result .= $chars[random_int(0, $max)];
    }

    return $result;
}

?>