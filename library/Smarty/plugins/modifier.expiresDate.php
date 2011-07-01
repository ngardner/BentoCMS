<?php
function smarty_modifier_expiresDate($string) {
    
    $expireTime = strtotime($string);
    $timeLeft = $expireTime-time();
    
    if($timeLeft > 86400) {
        
        //days:hours
        return date('j \d\a\y\s\<\b\r\/\>G \h\r\s',$timeLeft);
        
    } else if($timeLeft > 3600) {
        
        //hours:min
        return date('G \h\r\s\<\b\r\/\> i \m\i\n\s',$timeLeft);
        
    } else if($timeLeft > 0) {
        
        //min:sec
        return date('i \m\i\n\s\<\b\r\/\>s \s\e\c\s',$timeLeft);
        
    } else {
        
        //expired
        return 'Expired';
        
    }
    
}
?>
