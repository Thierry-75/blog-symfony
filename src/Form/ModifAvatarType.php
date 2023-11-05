<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ModifAvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('avatar', AvatarType::class, ['label'=>'Avatar :','label_attr'=>['class'=>'form_label mt-3'],'attr'=>['class'=>'form-control-file'],
        'constraints'=> [ new Assert\NotBlank(['message'=>'Please upload your avatar'])]])
        ->add('submit', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-light text-primary mt-4 float-end'
            ], 'label' => 'Valider'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => user::class,
        ]);
    }
}
