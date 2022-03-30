<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Carnet;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
            // ___________________________la userInterface_______Authentification___________
            private $encoder;
            public function __construct(UserPasswordHasherInterface $encoder)
            {
                    $this->encoder=$encoder;
            }
            
    public function load(ObjectManager $manager)
    {
        // Gestion des utilisateurs principalement l'admin
        $adminUser=new User();
        // L'objet "$adminUser" sur lequel on veux agir rapidement, accompagné du mot de pass encodé
        $pwd="morelle";
        $pwd=$this->encoder->hashPassword($adminUser,$pwd);
        
        $adminUser->setEmail("yabamorelle@gmail.com")
                  ->setPassword($pwd);

        $manager->persist($adminUser);

        
        // --------

        $faker=Factory::create('fr-Fr');
        $slugify = new Slugify();
        // Jeu de donnnés pour l'ajout de carnet d'adresse

        for ($i=1; $i <= 12; $i++) { 
            # code...
            $carnet = new Carnet();

            $nom=$faker->firstname();
            $prenoms=$faker->lastname();
            $email=$faker->email();
            $pays=$faker->country();
            $categorie=$faker->jobTitle();
            $observation=$faker->paragraph(1);
            $slug=$slugify->slugify("$prenoms $nom");

            // Affectation
            $carnet->setNom("$nom $prenoms")
                    ->setEmail($email)
                    ->setTelephone($faker->phoneNumber)
                    ->setPays($pays)
                    ->setVille($faker->city)
                    ->setCategorie($categorie)
                    ->setObservation($observation)
                    ->setSlug($slug);

                    $manager->persist($carnet);
                    
        }

        $manager->flush();

        // lancer la fixture ("php bin/console doctrine:fixtures:load")
        
        // pour changer ou supprimer une proprieté/type de proprieté d'un entity , il suffit de
        //  le faire mannuelement dans le code en changeant ce qu'il y'a a changer ou supprimer et par la suite
        // lancer la commande "symfony console doctrine:migrations:diff" qui nous cree un fichier de migration update/mis a jour et enfin
        // lancer la commande "symfony console doctrine:migrations:migrate" qui met la base de donnée a jour
    }
}
