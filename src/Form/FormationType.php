<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as SFType;


class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', SFType\TextType::class)
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('nbreHeures', IntegerType::class)
            ->add('departement', SFType\TextType::class)
            ->add('description', SFType\TextType::class)
            ->add('image', SFType\TextType::class)
            ->add('produit', EntityType::class, array(
                'class' => 'App\Entity\Produit',
                'choice_label' => 'libelle',
                'required' => true,
            ))
            ->add('Cree', SubmitType::class, [
                'label' => 'Crée la formation',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
