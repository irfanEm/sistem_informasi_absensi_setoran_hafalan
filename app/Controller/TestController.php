<?php

namespace IRFANM\SIASHAF\Controller;

class TestController
{
    public function index()
    {
        echo json_encode([
            'status' => 'success',
        ]);
    }
}
