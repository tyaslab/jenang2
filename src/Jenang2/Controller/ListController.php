<?php

namespace Jenang2\Controller;

use Jenang2\Controller\TemplateController;


class ListController extends TemplateController {
    protected $paginate_by = 20;  // IF NULL THEN NO PAGINATION

    private $total_page = NULL;
    private $current_page = NULL;
    protected $object_list = NULL;

    protected function getPaginateBy() {
        return $this->paginate_by;
    }

    protected function getCurrentPage() {
        if ($this->current_page) return $this->current_page;
        $current_page = $this->c->request->getParam('page', 1);
        $this->current_page = $current_page;

        return $current_page;
    }

    protected function getList() {
        // default implementation
        return $this->model::all();
    }

    protected function paginate($collections) {
        // ALERT: this is Collection mode, NOT Query mode!!!
        $per_page = $this->getPaginateBy();
        $current_page = $this->getCurrentPage();

        if (!$per_page) return $collections;

        $total_page = ceil($collections->count() / $per_page);

        if ($total_page > 0 && $current_page > $total_page) {
            // TODO: redirect to first page
            throw new \Slim\Exception\NotFoundException($this->request, $this->response);
        }

        $paged = $collections->forPage($current_page, $per_page);

        $this->total_page = $total_page;
        
        return $paged->all();
    }

    protected function getContextData($args=[]) {
        $context = parent::getContextData($args);

        $object_list = $this->getList();
        $paginate_by = $this->getPaginateBy();

        if ($paginate_by) {
            $object_list = $this->paginate($object_list);
        }

        $context['object_list'] = $object_list;
        $context['paginate_by'] = $paginate_by;
        $context['total_page'] = $this->total_page;
        $context['current_page'] = $this->getCurrentPage();

        return $context;
    }
}
