<?php

namespace App\Controller;

use App\Entity\PageItem;
use App\Form\UploadFileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class XMLController extends AbstractController
{
    #[Route('/xml', name: 'app_xml')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(UploadFileType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
        $file = simplexml_load_file($form->get('XML_file')->getData());

        $em = $doctrine->getManager();

        foreach ($file as $item){
            $page = new PageItem();
            $page->setCode($item->code);
            $page->setName($item->name);
            $page->setBody($item->body);
            if ($item->parent_id != "null"){
                $page->setParentId($item->parent_id);
            }
            $em->persist($page);
        };
        }
        return $this->render('add/index.html.twig', [
            'form' => $form
        ]);
    }
}
