<?php

namespace App\Controller;

use App\Entity\PageItem;
use App\Form\AddPageType;
use App\Service\LogService;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditController extends AbstractController
{
    private $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }
    #[Route('/edit/{page_id}', name: 'app_edit_page')]
    public function index(ManagerRegistry $doctrine,$page_id, Request $request): Response
    {
        $item = $doctrine->getRepository(PageItem::class)->find($page_id);

        $form = $this->createForm(AddPageType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            try {
                $item = $form->getData();
                $code = $item->getCode();
                $em = $doctrine->getManager();
                $em->persist($item);
                $em->flush();
                $this->logService->logInfo($item->getCode(), 'Изменение страницы', 'Успех');
                return $this->redirectToRoute('show_one_page', ['page_code' => $code]);
            } catch (\Exception $e) {
                $this->logService->logError($item->getCode(), $e->getMessage());
                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render('add/index.html.twig', [
            "form" => $form
        ]);
    }
}
