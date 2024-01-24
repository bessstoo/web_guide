<?php

namespace App\Controller;

use App\Entity\PageItem;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{
    public function DeleteBranch(ManagerRegistry $doctrine, $post_code)
    {
        $em = $doctrine->getManager();
        $item = $em->getRepository(PageItem::class)->findOneBy(['code' => $post_code]);

        if (!$item){
            return;
        }

        $children = $em->getRepository(PageItem::class)->findBy(['parent_code' => $post_code]);
        if ($children) {
            foreach ($children as $child) {
                $this->DeleteBranch($doctrine, $child->getCode());
            }
        }

        $em->remove($item);
        $em->flush();
    }
    #[Route('/delete_post/{post_code}', name: 'app_delete_post')]
    public function index(ManagerRegistry $doctrine, $post_code): Response
    {
        $this->DeleteBranch($doctrine, $post_code);
        return $this->redirectToRoute('homepage');
    }

}
