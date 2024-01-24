<?php

namespace App\Form;

use App\Entity\PageItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchBarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('code')
//            ->add('name')
            ->add('body', TextType::class, [
                'label' => false,
                'attr' => ['class' =>'Search bar', 'placeholder' => "Введите название или код страницы для поиска"],
            ])
//            ->add('parent_id')
                ->add('save', SubmitType::class, [
                    'label' => 'Найти',
                'attr' => ['class' =>'Search button']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PageItem::class,
        ]);
    }
}
