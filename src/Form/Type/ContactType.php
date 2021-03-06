<?php
namespace App\Form\Type;

use App\Model\Contact;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail'
            ])
            ->add('phone', PhoneNumberType::class, [
                'label' => 'Téléphone',
                'default_region' => 'FR',
            ])
            ->add('topic', ChoiceType::class, [
                'choices' => Contact::TOPIC,
                'label' => 'Sujet',
                'placeholder' => 'Choissiez le sujet de votre message',
            ])
            ->add('message', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
