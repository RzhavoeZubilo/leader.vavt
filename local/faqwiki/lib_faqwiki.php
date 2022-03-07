<?php
/**
 * User: densh
 * Date: 28.02.2022
 * Time: 16:21
 */


function getParams($param)
{
    if ($param) {
        if (unserialize($param) == false) {
            $str = $param;
            $str = preg_replace_callback('!s:(\d+):"(.*?)";! s', function ($match) {
                return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
            }, $str);
            $params = unserialize($str);
        } else {
            $params = unserialize($param);
        }
        return $params;
    }
    return null;
}

function trimString($string){
    $string = strip_tags($string);
    $string = substr($string, 0, 250);
    $string = rtrim($string, "!,.-");
    $string = substr($string, 0, strrpos($string, ' '));
    return $string."...";
}