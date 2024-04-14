<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Formation;
use App\Entity\Inscription;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RechercheProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('produit', EntityType::class, array(
                'class' => 'App\Entity\Produit',
                'choice_label' => 'libelle',
                'required' => true,
            ))
            ->add('rechercher', SubmitType::class);
    }


}