<?php

namespace App\Controller;

use App\Entity\PageItem;
use App\Form\AddPageType;
use App\Service\CodeGenerator;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\LogService;

class AddController extends AbstractController
{
    private $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    #[Route('/add_page', name: 'app_add_page')]
    public function index(ManagerRegistry $doctrine, Request $request, CodeGenerator $generator): Response
    {
        $item = new PageItem();
        $form = $this->createForm(AddPageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            try {
                $item = $form->getData();
                $item->setCode($generator->RandomCode());
                $code = $item->getCode();
                $item->setParentCode(null);

                $em = $doctrine->getManager();
                $em->persist($item);
                $em->flush();
                $this->logService->logInfo($item->getCode(), 'Создание страницы', 'Успех');
                return $this->redirectToRoute('show_one_page', ['page_code' => $code]);
            } catch (\Exception $e) {
                $this->logService->logError($item->getCode(), $e->getMessage());
                return $this->redirectToRoute('homepage');
            }

        }

        return $this->render('add/index.html.twig', [
            'controller_name' => 'AddController',
            "form" => $form
        ]);
    }

    #[Route('/add_sub_page/{parent_code}', name: 'app_add_sub_page')]
    public function AddSubPage(ManagerRegistry $doctrine, Request $request, $parent_code, CodeGenerator $generator): Response
    {
        $item = new PageItem();
        $parentItem = $doctrine->getRepository(PageItem::class)->findOneBy(['code' => $parent_code]);
        $item->setParentCode($parentItem->getCode());
        $form = $this->createForm(AddPageType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            try {
                $item = $form->getData();
                $item->setCode($generator->RandomCode());
                $code = $item->getCode();

                $em = $doctrine->getManager();
                $em->persist($item);
                $em->flush();
                $this->logService->logInfo($item->getCode(), 'Создание страницы', 'Успех');
                return $this->redirectToRoute('show_one_page', ['page_code' => $code]);
            } catch (\Exception $e) {
                $this->logService->logError($item->getCode(), $e->getMessage());
                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render('add/index.html.twig', [
            'controller_name' => 'AddController',
            "form" => $form
        ]);
    }


}
