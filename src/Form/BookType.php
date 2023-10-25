<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('ref')
            ->add('title')
            ->add('Author',EntityType::class,[
                'class'=> Author::class,
                'choice_label'=>'username',
                'multiple'=>false, 
                'expanded'=>false,
                'required'=>true,
                'placeholder'=>'veuillez selectionner un auteur',
            ])
            ->add('category',ChoiceType::class, [
                'choices'  => [
                    'Science-Fiction' => 'Science-Fiction',
                    'Mystery' => 'Science-Fiction',
                    'Autobiography' => 'Science-Fiction',
                ],])
            ->add('published')
            ->add('publicationDate',DateType::class,array(
                'required' => false,))
            ->add('save',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
