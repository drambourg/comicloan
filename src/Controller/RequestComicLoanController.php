<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RequestComicLoanController extends AbstractController
{
    /**
     * @Route("/loan/requests", name="loan_request_index")
     */
    public function index()
    {
        return $this->render('request_comic_loan/index.html.twig', [
            'title_h1' => 'Request Comics',
            'title_h2' => 'Need Help ?!',
            'controller_name' => 'RequestComicLoanController',
        ]);
    }
}
