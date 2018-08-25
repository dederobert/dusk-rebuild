<?php

namespace DuskPHP\Core\Controller;

class PagesController
{
    public function view(array $params = []): string
    {
        return file_get_contents('assets/views/pages/test.html');
    }
}
