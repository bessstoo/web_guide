<?php

namespace App\Controller;

use App\Entity\PageItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class SearchController extends AbstractController
{

//    #[Route('/search/{str}', name: 'app_search')]
//    public function SearchAction(Request $request, $input, ManagerRegistry $doctrine): Response
//    {
//        if (preg_match('/^[a-zA-Z0-9]{20}$/', $input)){
//            $results = $doctrine->getRepository(PageItem::class)->SearchInDirectoryCode($input);
//        } else {
//            $results = $doctrine->getRepository(PageItem::class)->SearchInDirectoryName($input);
//        }
//        return $this->render('main/index.html.twig', ['tree' => $results,
//            'form' => $form
//        ]);
//    }
}
