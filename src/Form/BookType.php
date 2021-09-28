<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Form\FormTypeInterface;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => new NotBlank([
                    'message' => 'Field {{ label }}  can\'t be blank'
                ]),
                'attr' => ['class' => 'form-control']
            ])
            ->add('author', TextType::class, [
                'constraints' => new NotBlank([
                    'message' => 'Field {{ label }}  can\'t be blank'
                ]),
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 30,
                        'max' => 255,
                        'minMessage' => '{{ label }} must be at least {{ limit }} characters long',
                        'maxMessage' => '{{ label }} cannot be longer than {{ limit }} characters'
                    ])
                ],
                'attr' => ['class' => 'form-control']

            ])
            ->add('pages', NumberType::class, [
                'constraints' => new NotBlank(),
                'attr' => ['class' => 'form-control']
            ])
            ->add('publishingHouse', TextType::class, [
                'constraints' => new NotBlank([
                    'message' => 'Field {{ label }}  can\'t be blank'
                ]),
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Add New Book',
                'attr' => ['class' => 'btn btn-primary']
            ])->setMethod('POST')
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
