<?php
namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    const MODE_REGISTER = 'register';
    const MODE_CHECKOUT = 'checkout';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
        ;

        if($options['mode'] === self::MODE_REGISTER){
            $builder
                ->add('displayName', null, [
                    'label' => 'Nom d\'utilisateur',
                ])
                ->add('plainPassword', PasswordType::class, [
                    'label' => 'Mot de passe',
                    'help' => 'Un chiffre, une lettre, 8 caractères minimum',
                ])
            ;
        }else if($options['mode'] === self::MODE_CHECKOUT){
            $builder
                ->add('firstname', null, [
                    'label' => 'Prénom'
                ])
                ->add('lastname', null, [
                    'label' => 'Nom',
                ])
                ->add('address', AddressType::class, [
                    'label' => 'Adresse'
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'mode' => self::MODE_REGISTER,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
            'validation_groups' => function(FormInterface $form){
                $options = $form->getConfig()->getOptions();
                return ['Default', $options['mode']];
            }
        ]);
    }
}
