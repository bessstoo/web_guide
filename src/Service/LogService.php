<?php

namespace App\Service;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use App\Entity\ChangeLog;
use App\Entity\ImportLog;
class LogService
{
    private $em;
    private $logger;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger)
    {
        $this->em = $doctrine->getManager();
        $this->logger = $logger;
    }

    public function logInfo($item_code, $action, $details)
    {
       $this->logChange($item_code, $action, $details);
    }
    public function logError($item_code, $details)
    {
        $this->logChange($item_code, 'Ошибка', $details);
    }
    public function ImportStatus($status)
    {
        $this->LogImport($status);
    }
    private function logChange($item_code, $action, $details)
    {
        $entity = new ChangeLog();
        $entity->setItemCode($item_code);
        $entity->setAction($action);
        $entity->setDetails($details);
        $entity->setTime(new \DateTime());

        $this->em->persist($entity);
        $this->em->flush();
    }

    private function LogImport($status)
    {
        $entity = new ImportLog();
        $entity->setTime(new \DateTime());
        $entity->setStatus($status);

        $this->em->persist($entity);
        $this->em->flush();
    }

}