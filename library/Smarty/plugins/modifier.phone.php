<?php

/**
 * Smarty phone modifier plugin
 *
 * Type:     modifier<br>
 * Name:     phone<br>
 * Purpose:  format strings into phone number (817)870-1578
 * @author   Nathan Gardner <nathan@ecrated.com>
 */
function smarty_modifier_phone($string)
{
    $numbers = preg_replace('/\D/','',$string);
    
    if(strlen($numbers) == 10) {
        
        // format as US phone
        $zip = substr($numbers,0,3);
        $part1 = substr($numbers,3,3);
        $part2 = substr($numbers,6,4);
        
        return '('.$zip.')'.$part1.'-'.$part2;
        
    } else {
        
        return $string;
        
    }
    
}

/* vim: set expandtab: */

?>
