<?php

namespace App\Controller;

use App\Entity\PageItem;
use App\Service\LogService;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{
    private $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }
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
        try {
            $em->remove($item);
            $this->logService->logInfo($item->getCode(), 'Удаление страницы', 'Успех');
            $em->flush();
        } catch (\Exception $e) {
            $this->logService->logError($item->getCode(), $e->getMessage());
        }

    }
    #[Route('/delete_post/{post_code}', name: 'app_delete_post')]
    public function index(ManagerRegistry $doctrine, $post_code): Response
    {
        $this->DeleteBranch($doctrine, $post_code);
        return $this->redirectToRoute('homepage');
    }

}
