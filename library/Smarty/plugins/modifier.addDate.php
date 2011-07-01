<?php
function smarty_modifier_addDate($string)
{
    return date('F\<\b\r\/\>jS Y',strtotime($string));
}
?>
