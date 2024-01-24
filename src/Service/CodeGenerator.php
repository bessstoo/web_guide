<?php

namespace App\Service;

use App\Entity\PageItem;
use Doctrine\Persistence\ManagerRegistry;

class CodeGenerator
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    public function RandomCode(): string
    {
        $characters = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
        $characterLength = strlen($characters);
        do {
            $randomCode = '';
            for ($i = 0; $i < 21; $i++){
                $randomCode .= $characters[rand(0, $characterLength - 1)];
            }
            $post = $this->doctrine->getRepository(PageItem::class)->findBy(['code' => $randomCode]);

            if (!$post){
                return $randomCode;
            }
        }  while (true);
    }
}