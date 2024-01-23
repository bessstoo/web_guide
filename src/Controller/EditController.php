<?php

namespace App\Controller;

use App\Entity\PageItem;
use App\Form\AddPageType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditController extends AbstractController
{
    #[Route('/edit/{page_id}', name: 'app_edit_page')]
    public function index(ManagerRegistry $doctrine,$page_id, Request $request): Response
    {
        $item = $doctrine->getRepository(PageItem::class)->find($page_id);

        $form = $this->createForm(AddPageType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $item = $form->getData();
            $code = $item->getCode();

            $em = $doctrine->getManager();
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('show_one_page', ['page_code' => $code]);
        }

        return $this->render('add/index.html.twig', [
            "form" => $form
        ]);
    }
}
