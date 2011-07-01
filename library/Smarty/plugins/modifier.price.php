<?php

/**
 * Smarty price modifier plugin
 *
 * Type:     modifier<br>
 * Name:     price<br>
 * Purpose:  format strings like a dollar amount (USD)
 * @author   Nathan Gardner <nathan@ecrated.com>
 */
function smarty_modifier_price($string)
{
    $price = doubleval($string);
    $price = number_format($price,2,'.',',');
    return '$'.$price;
}

/* vim: set expandtab: */

?>
