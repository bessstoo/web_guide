<?php

namespace App\Controller;

use App\Entity\PageItem;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{
    public function DeleteBranch(ManagerRegistry $doctrine, $post_id)
    {
        $em = $doctrine->getManager();
        $item = $em->getRepository(PageItem::class)->find($post_id);

        if (!$item){
            return;
        }

        $children = $em->getRepository(PageItem::class)->findBy(['parent_id' => $post_id]);
        if ($children) {
            foreach ($children as $child) {
                $this->DeleteBranch($doctrine, $child->getId());
            }
        }

        $em->remove($item);
        $em->flush();
    }
    #[Route('/delete_post/{post_id}', name: 'app_delete_post')]
    public function index(ManagerRegistry $doctrine, $post_id): Response
    {
        $this->DeleteBranch($doctrine, $post_id);
        return $this->redirectToRoute('homepage');
    }

}
