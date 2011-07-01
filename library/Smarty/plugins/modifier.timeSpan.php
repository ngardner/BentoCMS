<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty time span plugin
 *
 * Type: modifier<br>
 * Name: timeSpan<br>
 * Purpose: convert date to a human friendly string such as "32 minutes ago"
 * @link: 
 * @author Nathan Gardner <nathan@factory8.com>
 * @param string
 * @return string
 */
function smarty_modifier_timeSpan($string)
{
    $timestamp = strtotime($string);
    $now = time();
    $timeSpan = $now-$timestamp;
    $date = date("m/d/y",$timestamp);
    $dateYesterday = date("m/d/y",$now-86400);
    $dayOfMonth = date("D",$timestamp);
    $time = date("g:ia",$timestamp);
    $minutes = round($timeSpan/60);
    $hours = round($timeSpan/3600);
    $month = date("M,",$timestamp);
    $dayOfMonthNumeric = date("d",$timestamp);
    $year = date("Y",$timestamp);
    $yearNow = date("Y",$now);
    
    if($timeSpan < 0) {
        
        $spanMessage = $month . ' ' . $dayOfMonthNumeric . ' ' . $year . ' at ' . $time;
        
    } else if($timeSpan < 180) { // less than 3 minutes
        
        $spanMessage = 'Just now';
        
    } else if ($timeSpan < 3000) { // less than 50 minutes ago
        
        
        $spanMessage = $minutes . ' mins ago';
        
    } else if ($timeSpan < 86400) { // less than 24 hours ago
        
        
        $spanMessage = $hours . ' hours ago';
        
    } else if ($date == $dateYesterday) { // yesterday
        
        $spanMessage = 'Yesterday at ' . $time;
        
    } else if ($timeSpan < 432000) { // less than 5 days ago
        
        $spanMessage = $dayOfMonth . ' at ' . $time;
        
    } else if ($year == $yearNow) { // more than 5 days ago this year
        
        $spanMessage = $month . ' ' . $dayOfMonthNumeric . ' at '. $time;
        
    } else { // more than a year ago
        
        $spanMessage = $month . ' ' . $dayOfMonthNumeric . ' ' . $year . ' at ' . $time;
        
    }
    
    return $spanMessage;
    
}

?>
