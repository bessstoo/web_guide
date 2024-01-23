<?php

namespace App\Form;

use App\Entity\PageItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('code')
            ->add('name', TextType::class, ['label' => 'Название:'])
            ->add('body', TextareaType::class, ['label' => 'Содержание'])
            ->add('parent_id')
            ->add('save', SubmitType::class, ['label' => 'Добавить'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PageItem::class,
        ]);
    }
}
