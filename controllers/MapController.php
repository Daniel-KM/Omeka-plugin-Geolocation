<?php

class Geolocation_MapController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
        $this->_helper->db->setDefaultModelName('Item');
    }
    
    public function browseAction()
    {
        $this->_setParam('only_map_items', true);
        $this->_setParam('use_map_per_page', true);
        
        $table = $this->_helper->db->getTable();
        $locationTable = $this->_helper->db->getTable('Location');
        
        $params = $this->getAllParams();
        $currentPage = $this->getParam('page', 1);
        $limit = (int)get_option('geolocation_per_page');
        $params['only_map_items'] = true;

        $items = $table->findBy($params, $limit, $currentPage);
        $this->view->items = $items;
        $this->view->locations = $locationTable->findLocationByItem($items);
        $this->view->totalItems = $table->count($params);
        
        $params = array('page'  => $currentPage,
                'per_page'      => $limit,
                'total_results' => $this->view->totalItems);
        
        Zend_Registry::set('map_params', $params);
        
        // Make the pagination values accessible from pagination_links().
        Zend_Registry::set('pagination', $params);
    }
}
