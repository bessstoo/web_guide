<?php

namespace App\Controller;

use App\Entity\PageItem;
use App\Form\SearchBarType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{

    public function BuildTree(array $elements, $parentId = null): array
    {
        $branch = [];

        foreach ($elements as $element) {
            if($element->getParentId() == $parentId){
                $children = $this->BuildTree($elements, $element->getId());
                if($children){
                    $element->children = $children;
                }
                $branch[] = $element;
                unset($element);
            }
        }
        return $branch;
    }
    #[Route('/', name: 'homepage')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $pages = $doctrine->getRepository(PageItem::class)->findAll();
        $tree = $this->BuildTree($pages);
        $form = $this->createForm(SearchBarType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $input = $form->getData();
            return $this->redirectToRoute('app_search', ['input' => $input->getBody()]);
        }
        return $this->render('main/index.html.twig', ['tree' => $tree,
        'form' => $form
        ]);
    }

    #[Route('/page/{page_code}', name: 'show_one_page')]
    public function ViewOneItem(ManagerRegistry $doctrine, $page_code, Request $request) : Response
    {
        $pages = $doctrine->getRepository(PageItem::class)->findAll();
        $tree = $this->BuildTree($pages);
        $item = $doctrine->getRepository(PageItem::class)->findBy(['code' => $page_code]);
        $form = $this->createForm(SearchBarType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $input = $form->getData();
            return $this->redirectToRoute('app_search', ['input' => $input->getBody()]);
        }

        return $this->render('main/one_page.html.twig', ['page' => $item,
            'tree' => $tree,
            'form' => $form]);
    }

    #[Route('/search/{input}', name: 'app_search')]
    public function SearchAction(Request $request, $input, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(SearchBarType::class);
        if (preg_match('/^[a-zA-Z0-9]{21}$/', $input)) {
            $results = $doctrine->getRepository(PageItem::class)->SearchInDirectoryCode($input);
        } else {
            $results = $doctrine->getRepository(PageItem::class)->SearchInDirectoryName($input);
        }
//        dump($results);die();
        return $this->render('main/one_page.html.twig', [
            'tree' => $results,
            'page' => $results,
            'form' => $form
        ]);
    }
}
