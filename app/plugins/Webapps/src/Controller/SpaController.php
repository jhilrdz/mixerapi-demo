<?php
declare(strict_types=1);

namespace Webapps\Controller;

use Webapps\Controller\AppController;

/**
 * Spa Controller
 *
 */
class SpaController extends AppController
{
    /**
     * viewHomepage method
     *
     *
     * @return \Cake\Http\Response|null|void Renders view
     *
     */
    public function viewHomepage()
    {
        $this->viewBuilder()->setLayout('ajax');
    }
}
