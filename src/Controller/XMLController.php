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
                if ($item->code != "null"){
                    $page->setCode($item->code);;
                }
                else {
                    $AddPageInstance = new AddController();
                    $page->setCode($AddPageInstance->RandomCode($doctrine));
                }
                $page->setName($item->name);
                $page->setBody($item->body);
                if ($item->parent_code != "null"){
                    $page->setParentCode($item->parent_code);
                }
                $em->persist($page);
                }
            $em->flush();
            return $this->redirectToRoute('homepage');
            }
        return $this->render('add/index.html.twig', [
            'form' => $form
        ]);
    }
}
