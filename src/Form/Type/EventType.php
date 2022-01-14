<?php

namespace App\Form\Type;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de l\'événement'
            ])
            ->add('description',  null, [
                'label' => 'Description',
            ])
            ->add('startAt', null, [
                'label' => 'Date de début',
                'widget' => 'single_text',
            ])
            ->add('endAt', null, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
            ])
            ->add('capacity', null, [
                'label' => 'Capacité',
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
            ])
            ->add('category', null, [
                'choice_label' => 'name',
                'label' => 'Categorie',
                'expanded' => true,
            ])
            ->add('place', null, [
                'choice_label' => 'label',
                'label' => 'Lieu'
            ])
            ->add('tags', null, [
                'choice_label' => 'name',
                'label' => 'Mot-clés',
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
        ]);
    }
}
