<?php

namespace App\Form;

use App\Entity\Carnet;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CarnetType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, $this->getConfiguration(
                "Nom", "Veuillez entrer un nom svp !")
                )
            ->add('email',EmailType::class, $this->getConfiguration(
                "Email", "Veuillez entrer un email valide svp !")
                )
            ->add('telephone',TextType::class, $this->getConfiguration(
                "Telephone", "Veuillez entrer un numero de telephone svp !")
                )
            ->add('pays',TextType::class, $this->getConfiguration(
                "Pays", "Veuillez entrer le pays concerné !")
                )    
            ->add('ville',TextType::class, $this->getConfiguration(
                "Ville", "Veuillez entrer votre ville svp !")
                )
            ->add('categorie',TextType::class, $this->getConfiguration(
                "Categories", "Dans quelle categorie se situe t'elle ? !")
                )
            ->add('observation',TextareaType::class, $this->getConfiguration(
                "Observation", "decriver.... !")
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carnet::class,
        ]);
    }
}
