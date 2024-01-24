<?php

namespace App\Controller;

use App\Entity\PageItem;
use App\Form\UploadFileType;
use App\Service\CodeGenerator;
use App\Service\LogService;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class XMLController extends AbstractController
{

    private $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }
    #[Route('/xml', name: 'app_xml')]
    public function index(Request $request, ManagerRegistry $doctrine, CodeGenerator $generator): Response
    {
        $form = $this->createForm(UploadFileType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            try {
                $file = simplexml_load_file($form->get('XML_file')->getData());
                $em = $doctrine->getManager();
                foreach ($file as $item){
                    $page = new PageItem();
                    if ($item->code != "null" && $item->code != ""){
                        if ($doctrine->getRepository(PageItem::class)->findBy(['code' => $item->code]))
                        {
                            throw new \Exception('Ошибка: Страница с таким кодом уже существует');
                        }
                        else {
                            $page->setCode($item->code);
                        }
                    }
                    else {
                        $page->setCode($generator->RandomCode());
                    }
                    if ($item->name != "")
                        $page->setName($item->name);
                    else
                        throw new \Exception('Ошибка: Отсутствует атрибут name');
                    if ($item->body != "")
                        $page->setBody($item->body);
                    else
                        throw new \Exception('Ошибка: Отсутствует атрибут body');
                    if ($item->parent_code != "null")
                        $page->setParentCode($item->parent_code);

                    $em->persist($page);
                }
                $em->flush();
                $this->logService->ImportStatus('Успешный импорт');
                return $this->redirectToRoute('homepage');
            } catch (\Exception $e) {
                $this->logService->ImportStatus($e->getMessage());
                return $this->redirectToRoute('homepage');
            }
            }
        return $this->render('add/index.html.twig', [
            'form' => $form
        ]);
    }
}
