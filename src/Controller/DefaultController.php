<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index(Request $request, $name, $old, $component)
    {
        return $this->render('default/index.html.twig', [
            'name' => $name,
            'old' => $old,
            'component' => $component,
        ]);
    }
}
