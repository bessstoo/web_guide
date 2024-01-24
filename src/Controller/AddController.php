<?php

namespace App\Controller;

use App\Entity\PageItem;
use App\Form\AddPageType;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
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
//        dump($item);die();
//        $FormArray = $doctrine->getRepository(PageItem::class)->findAll();
//        $FormCodes = [];
//        foreach ($FormArray as $items) {
//            $FormCodes[$items->getCode()] = $items->getId();
//        }
//
//        $form = $this->createForm(AddPageType::class, $item, ['array' => $FormCodes]);
        $form = $this->createForm(AddPageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $item = $form->getData();
            $item->setCode($this->RandomCode($doctrine));
            $code = $item->getCode();
            $item->setParentCode(null);

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

    #[Route('/add_sub_page/{parent_code}', name: 'app_add_sub_page')]
    public function AddSubPage(ManagerRegistry $doctrine, Request $request, $parent_code): Response
    {
        $item = new PageItem();
        $parentItem = $doctrine->getRepository(PageItem::class)->findOneBy(['code' => $parent_code]);
        $item->setParentCode($parentItem->getCode());
        $form = $this->createForm(AddPageType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $item = $form->getData();
            $item->setCode($this->RandomCode($doctrine));
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
