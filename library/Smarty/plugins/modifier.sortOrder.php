<?php
function smarty_modifier_sortOrder($string)
{
    if(strtolower($string)=='asc') {
        return 'DESC';
    } else {
        return 'ASC';
    }
}
?>
