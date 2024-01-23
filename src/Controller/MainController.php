<?php

namespace App\Controller;

use App\Entity\PageItem;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    public function BuildTree(array $elements, $parentId = null)
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
    public function index(ManagerRegistry $doctrine): Response
    {
        $pages = $doctrine->getRepository(PageItem::class)->findAll();
        $tree = $this->BuildTree($pages);
        return $this->render('main/index.html.twig', ['tree' => $tree

        ]);
    }

    #[Route('/page/{page_code}', name: 'show_one_page')]
    public function ViewOneItem(ManagerRegistry $doctrine, $page_code) : Response
    {
        $pages = $doctrine->getRepository(PageItem::class)->findAll();
        $tree = $this->BuildTree($pages);
        $item = $doctrine->getRepository(PageItem::class)->findBy(['code' => $page_code]);


        return $this->render('main/one_page.html.twig', ['page' => $item,
            'tree' => $tree]);
    }

}
