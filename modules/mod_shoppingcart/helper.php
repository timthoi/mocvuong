<?php
# Raindrops Shopping Cart Module for Virtuemart                                          
# Copyright (C) 2015 by Raindropsinfotech
# Homepage   : www.raindropsinfotech.com               
# Author     : raindropsinfotech                       
# Email      : raindropsinfotech@gmail.com             
# Version    : 1.0                                    
# license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
defined('_JEXEC') or die;
class modshoppingcartHelper
{
    /**
     * Retrieves the hello message
     *
     * @param array $params An object containing the module parameters
     * @access public
     */    
    public static function imagecall($id) 
    {
    /* exit(JRequest::getVar("image"));*/
        $db = JFactory::getDBO();

        $sql = "select file_title, file_url, file_url_thumb from #__virtuemart_medias where virtuemart_media_id='$id'";
        $db->setQuery($sql);
        $data = $db->loadObjectList();
         
        //$name=$data[0]->file_title;
       return $data;
    }
}
?>