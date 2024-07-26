<?php 

// src/Controller/ApiDocController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiDocController extends AbstractController
{
    /**
     * @Route("/docs/openapi.yaml", name="api_doc")
     */
    public function openApi(): Response
    {
        $yaml = file_get_contents($this->getParameter('kernel.project_dir') . '/config/swagger/openapi.yaml');
        return new Response($yaml, 200, ['Content-Type' => 'application/yaml']);
    }
}
