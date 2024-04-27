<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompleteDailyFormType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
 $builder
     ->add('question1', TextType::class, [
         'label' => 'Comment vous sentez-vous aujourd\'hui ?',
         'required' => true,
     ])
     ->add('question2', TextType::class, [
         'label' => 'Avez-vous bien dormi la nuit dernière ?',
         'required' => true,
     ])
     ->add('question3', TextType::class, [
         'label' => 'Avez-vous mangé des repas sains ?',
         'required' => true,
     ])
     ->add('question4', TextType::class, [
         'label' => 'Avez-vous fait de l\'activité physique aujourd\'hui ?',
         'required' => true,
     ])
     ->add('question5', TextType::class, [
         'label' => 'Avez-vous parlé à quelqu\'un de vos sentiments ?',
         'required' => true,
     ])
     ->add('question6', TextType::class, [
         'label' => 'Avez-vous accompli certains de vos objectifs aujourd\'hui ?',
         'required' => true,
     ])
     ->add('question7', TextType::class, [
         'label' => 'Avez-vous ressenti du stress aujourd\'hui ?',
         'required' => true,
     ])
     ->add('question8', TextType::class, [
         'label' => 'Vous sentez-vous optimiste pour demain ?',
         'required' => true,
     ]);
    }


public function configureOptions(OptionsResolver $resolver)
{
$resolver->setDefaults([
 'data_class' => null,
]);
}
}
