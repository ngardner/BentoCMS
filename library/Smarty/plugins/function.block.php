<?php
/**
 * Smarty {block} function plugin
 *
 * Type:     function<br>
 * Name:     block<br>
 * Purpose:  Loads in a CMS block from the database<br>
 * @author Nathan Gardner <nathan@factory8.com>
 * @param array
 * @param Smarty
 */

function smarty_function_block($params, &$smarty) {
    
    if(!empty($params['identifier'])) {
        
        $objBlock = new BlocksModel;
        $blockId = $objBlock->getBlockId($params['identifier']);
        
        if(!empty($blockId)) {
            
            $blockInfo = $objBlock->loadBlock($blockId);
            return $smarty->fetch('fromstring:'.$blockInfo['code']);
            
            
        } else {
            
            return 'ERROR: Unknown block identifier';
            
        }
        
    } else {
        
        return 'ERROR: Must pass block identifier';
        
    }
    
}

?>
