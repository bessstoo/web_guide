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
        $item = $doctrine->getRepository(PageItem::class)->findBy(['parent_id' => $post_id]);
        if ($item) {
            $child_id = $item->getId();
            $this->DeleteBranch($doctrine, $child_id);
        }
        else {
            $item = $doctrine->getRepository(PageItem::class)->find($post_id);
            $em = $doctrine->getManager();
            $em->remove($item);
            $em->flush();
        }
    }
    #[Route('/delete_post/{post_id}', name: 'app_delete_post')]
    public function index(ManagerRegistry $doctrine, $post_id): Response
    {
        $this->DeleteBranch($doctrine, $post_id);
        return $this->redirectToRoute('homepage');
    }

}
