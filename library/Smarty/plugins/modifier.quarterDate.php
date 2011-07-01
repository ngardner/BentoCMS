<?php
/**
 * Smarty quarterDate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     quarterDate<br>
 * Purpose:  Converts a date/time into something like "3Q 2010"
 * @author   Nathan Gardner <nathan@ecrated.com>
 * @param string
 * @return string
 */
function smarty_modifier_quarterDate($string)
{
    
    $month = date("n",strtotime($string));
    $year = date("Y",strtotime($string));
    
    if($month <= 12) {
        $quarter = 4;
    }
    
    if($month <= 9) {
        $quarter = 3;
    }
    
    if($month <= 6) {
        $quarter = 2;
    }
    
    if($month <= 3) {
        $quarter = 1;
    }
   
    
    return $quarter.'Q '.$year;
    
}
?>
