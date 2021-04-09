<?php

namespace App\Form;

use App\Entity\Classes;
use App\Entity\Support;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;


class SupportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url')
            ->add('alt')
            ->add('content')
            ->add('titre')
            ->add('image',FileType::class, [
                'label' => 'image',
                'mapped' => false,
                'required' => false
            ])
            ->add('video',FileType::class, [
                'label' => 'video',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '50M'
                    ])
                ]
            ])
            ->add('classes', EntityType::class, [
                'class' => Classes::class,
                'choice_label' => 'name'
            ])

//            ->add('classes', CollectionType::class, [
//                'entry_type' => ClassesType::class,
////                'entry_options' => [ 'label' => 'name'
//////                    'attr' =>['class' => 'name']
////                ]
//            ])

            ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Support::class,
        ]);
    }
}
