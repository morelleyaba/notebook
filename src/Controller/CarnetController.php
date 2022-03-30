<?php

namespace App\Controller; 

use App\Entity\Carnet;
use App\Form\CarnetType;
use Cocur\Slugify\Slugify;
use App\Repository\CarnetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CarnetController extends AbstractController
{

    /**
     * notre page d'acceuil pour les utilisateurs
     * @Route("/", name="home")
     */

    public function home()
    {
        return $this->render('carnet/home.html.twig');
    }
    
    /**
     * @Route("/index", name="index")
     */

    public function searchBar()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un mot-clé'
                ]
            ])
            ->add('recherche', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();
        return $this->render('carnet/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/handleSearch", name="handleSearch")
     * @param Request $request
     */
    public function handleSearch(Request $request, CarnetRepository $repo)
    {
        $query = $request->request->get('form')['query'];
        if($query) { 
            $carnets = $repo->findcarnetsByName($query);
        }
        return $this->render('carnet/index.html.twig', [
            'carnets' => $carnets
        ]);
    }
// Pour faire court, si on trouve le mot clé recherché
// dans un "title" ou un "content" d'article, l'article est "sélectionné".("nom" ou un "categorie") dans le repo



    /**
     * @Route("/carnet/{slug}/show", name="carnet_show", methods={"GET"})
     * @param Carnet $carnet
     * @return Response
     */    
    public function carnetShow(Carnet $carnet)
    { 
        return $this->render('carnet/show.html.twig', [
            'show' => $carnet,
        ]);
    }   
}
