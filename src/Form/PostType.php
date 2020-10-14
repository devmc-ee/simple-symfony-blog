<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostType
 *
 * @package App\Form
 */
class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'placeholder' => 'Enter post title...'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Content',
                'attr' => [
                    'placeholder' => 'Post content...'
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Post image',
                'required' => false,
                'mapped' => false
            ])

           ->add('save', SubmitType::class,[
               'label' => 'Save Post',
               'attr' => [
                   'class' => 'btn btn-success'
               ]
           ])
            ->add('delete', SubmitType::class,[
                'label' => 'Delete Post',
                'attr' => [
                    'class' => 'btn btn-danger'
                ]
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
