<?php

namespace App\Form;


use App\Entity\Register;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,['attr'=>['class'=>'form-control form-control-sm', 'minlength' =>'2', 'maxlength'=>'30'],
            'label'=>'Name *','label_attr'=>['class'=>'form-label mt-3'],
            'constraints'=>[new Assert\Length(['min'=>2,'max'=>50,'minMessage'=>'minimum 2','maxMessage'=>'max 30']),
             ]
            ])
            ->add('surname',TextType::class,['attr'=>['class'=>'form-control form-control-sm', 'minlength' =>'2', 'maxlength'=>'30'],
            'label'=>'Surname *','label_attr'=>['class'=>'form-label mt-3'],
            'constraints'=>[new Assert\Length(['min'=>2,'max'=>50,'minMessage'=>'minimum 2','maxMessage'=>'max 30']),
            ]
            ])
            ->add('phone',TelType::class,['attr'=>['class'=>'form-control form-control-sm', 'minlength' =>'10', 'maxlength'=>'10'],
            'label'=>'Phone *','label_attr'=>['class'=>'form-label mt-3'],
            'constraints'=>[new Assert\Length(['min'=>10,'max'=>10,'minMessage'=>'minimum 10','maxMessage'=>'max 10']),
            ]
            ])
            ->add('pseudo',TextType::class,['attr'=>['class'=>'form-control form-control-sm', 'minlength' =>'2', 'maxlength'=>'30'],
            'label'=>'Pseudo','label_attr'=>['class'=>'form-label mt-3'],
            'constraints'=>[new Assert\Length(['min'=>2,'max'=>50,'minMessage'=>'minimum 2','maxMessage'=>'max 30']),
            new Assert\NotBlank(['message'=>'']) ]
            ])
            ->add('avatar',AvatarType::class)
            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-sm rounded-pill px-3 btn-info mt-3 float-end'],'label'=>'Send'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Register::class,
        ]);
    }
}
