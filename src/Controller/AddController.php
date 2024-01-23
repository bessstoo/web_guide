<?php

namespace App\Controller;

use App\Entity\PageItem;
use App\Form\AddPageType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddController extends AbstractController
{

    public function RandomCode(ManagerRegistry $doctrine): string
    {
        $characters = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
        $characterLength = strlen($characters);
        do {
            $randomCode = '';
            for ($i = 0; $i < 21; $i++){
                $randomCode .= $characters[rand(0, $characterLength - 1)];
            }
            $post = $doctrine->getRepository(PageItem::class)->findBy(['code' => $randomCode]);

            if (!$post){
                return $randomCode;
            }
        }  while (true);
    }

    #[Route('/add_page', name: 'app_add_page')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $item = new PageItem();
        $form = $this->createForm(AddPageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $item = $form->getData();
            $item->setCode($this->RandomCode($doctrine));
            $item->setIsParent(false);
            if (!is_null($item->getParentId())){
                $doctrine->getRepository(PageItem::class)->find($item->getParentId())->setIsParent(true);
            }
            $code = $item->getCode();

            $em = $doctrine->getManager();
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('show_one_page', ['page_code' => $code]);
        }

        return $this->render('add/index.html.twig', [
            'controller_name' => 'AddController',
            "form" => $form
        ]);
    }


}
