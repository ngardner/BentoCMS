<?php
function smarty_modifier_monthName($string)
{
    
    switch($string) {
        
        case 01:
        case 1:
            return 'January';
        break;
        
        case 02:
        case 2:
            return 'Februrary';
        break;
        
        case 03:
        case 3:
            return 'March';
        break;
        
        case 04:
        case 4:
            return 'April';
        break;
        
        case 05:
        case 5:
            return 'May';
        break;
        
        case 06:
        case 6:
            return 'June';
        break;
        
        case 07:
        case 7:
            return 'July';
        break;
        
        case 08:
        case 8:
            return 'August';
        break;
        
        case 09:
        case 9:
            return 'September';
        break;
        
        case 10:
            return 'October';
        break;
        
        case 11:
            return 'November';
        break;
        
        case 12:
            return 'December';
        break;
        
    }
    
}

?>
