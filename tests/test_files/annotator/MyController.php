<?php
namespace App;

use App\Controller\AppController;

class MyController extends AppController
{
    use \Burzum\CakeServiceLayer\Service\ServiceAwareTrait;

    /**
     * @return void
     */
    public function index(): void
    {
        $this->loadService('Articles');
        $this->loadService('Sub/Folder/Nested');
    }
}
