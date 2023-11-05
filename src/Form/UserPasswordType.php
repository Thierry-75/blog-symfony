<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plainPassword', PasswordType::class,['attr'=>['class'=>'form-control'],
        'label'=>'Old password','label_attr'=>['class'=>'form-label mt-4']])
        ->add('newPassword', RepeatedType::class,['type'=>PasswordType::class,
        'first_options'=>['label'=>'New password','label_attr'=>['class'=>'form-label mt-4']],
        'second_options'=>['label'=>'Confirm your new password','label_attr'=>['class'=>'form-label mt-4']],
        'invalid_message'=>'Passwords not the sames !','options'=>['attr'=>['class'=>'password-field']],
        'constraints'=> [new Assert\NotBlank(['message' => ''])]])
 
        ->add('submit',SubmitType::class, ['attr'=>['class'=>'btn btn-light text-primary mt-4 float-end'],'label'=>'Send'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
