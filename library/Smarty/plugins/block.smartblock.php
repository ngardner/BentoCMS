<?php
/**
 * Smarty {smartblock} function plugin
 *
 * Type:     block<br>
 * Name:     smartblock<br>
 * Purpose:  Loads in a CMS block from the database<br>
 * @author Nathan Gardner <nathan@ecrated.com>
 */

{smartblock name='var' identifer='myblock'}
name = {$var.name}<br/>
{/smartblock}

function smarty_block_smartblock($params, $content, &$smarty, $firstRun) {
    
    if($firstRun) {
        
        if(!empty($params['identifier']) && !empty($params['name'])) {
            
            $objBlock = new BlocksModel;
            $blockId = $objBlock->getBlockId($params['identifier']);
            
            if(!empty($blockId)) {
                
                $blockInfo = $objBlock->loadBlock($blockId);
                $blockInfo['content'] = $blockInfo['code'];
                unset($blockInfo['code']);
                $smarty->assign($params['name'],$blockInfo);
                
            } else {
                
                return 'ERROR: Unknown block identifier';
                
            }
            
        } else {
            
            return 'ERROR: Must pass a block identifier and a name';
            
        }
        
    } else {
        
        return $content;
        
    }
    
}

?>
