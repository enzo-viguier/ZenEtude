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
        $questions = [
            'Comment vous sentez-vous aujourd\'hui ?',
            'Avez-vous bien dormi la nuit dernière ?',
            'Avez-vous mangé des repas sains ?',
            'Avez-vous fait de l\'activité physique aujourd\'hui ?',
            'Avez-vous parlé à quelqu\'un de vos sentiments ?',
            'Avez-vous accompli certains de vos objectifs aujourd\'hui ?',
            'Avez-vous ressenti du stress aujourd\'hui ?',
            'Vous sentez-vous optimiste pour demain ?'
        ];

        foreach ($questions as $key => $question) {
            $builder->add('question' . ($key + 1), TextType::class, [
                'label' => $question,
                'required' => true,
                'attr' => ['question_text' => $question]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
