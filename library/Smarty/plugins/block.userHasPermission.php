<?php
/**
 * Smarty {userHasPermission} block plugin
 *
 * Type:     block<br>
 * Name:     userHasPermission<br>
 * Purpose: Outputs $content if user has permission
 * Useage:  {userHasPermission controller='foo' action='bar'}you have permission{/userHasPermission}
 * @author Nathan Gardner <nathan@factory8.com>
 */

function smarty_block_userHasPermission(&$params, $content, &$smarty, &$repeat) {
    
    if(!empty($params['controller']) && !empty($params['action'])) {
        
        $objPermissions = Permissions::getInstance();
        $objAuth = Authentication::getInstance();
        $user_id = $objAuth->user_id;
        
        $isAllowed = $objPermissions->actionAllowed($params['controller'],$params['action'],$user_id);
        
        if($isAllowed) {
            
            return $content;
            
        } else {
            
            return false;
            
        }
        
    } else {
        
        echo 'Must pass controller and action to do permission check.';
        
    }
    
}

?>
