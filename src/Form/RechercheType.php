<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Formation;
use App\Entity\Inscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'employé:',
                'required' => true,
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom de l\'employé:',
                'required' => true,
            ])
            ->add('rechercher', SubmitType::class);
    }


}