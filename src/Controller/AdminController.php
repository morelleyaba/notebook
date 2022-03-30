<?php

namespace App\Controller;

use App\Entity\Carnet;
use App\Form\CarnetType;
use Cocur\Slugify\Slugify;
use App\Repository\CarnetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

    /**
     * Creer un nouvel adresse
     * @Route("/admin/new", name="carnet_create")
     */
    public function create(Request $request,EntityManagerInterface $entityManager): Response
    {
        $slugify = new Slugify();
        // nouvel objet $carnet
        $carnet = new Carnet();
        $form = $this->createForm(CarnetType::class,$carnet);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            # recuperation du nom se trouvant sur le formulaire pour constituer me slug
            # affectation du slug
            $nom=$carnet->getNom();
            $slug=$slugify->slugify("$nom");
            $carnet->setSlug($slug);
            
            # enregistrement
            $entityManager->persist($carnet);
            $entityManager->flush();

                // notification
                $this->addFlash(
                    "success",
                    "L'adresse de <strong>{$carnet->getNom()}</strong> !a bien été enregistrer"
                );
                return $this->redirectToRoute("admin_index");
        }

        return $this->render('admin/new.html.twig', [
            'formulaire'=>$form->createView()
        ]);
    }

    // ----------------------
    /**
     * Afficher la liste des articles
     * @Route("/admin/{page<\d+>?1}", name="admin_index")
     * security
     * @IsGranted("ROLE_USER")
     */
    public function index(CarnetRepository $carnetRepo, $page): Response
    {
        $limit=5;
        $start=$page * $limit - $limit;
        // 1 * 10 = 10 - 10 = 0
        // 2 * 10 = 20 - 10 = 10

        // Rendre dynamique la pagination, determiner le nbre de page qu'on peut afficher en 
        //fonction du nbre d'annonces divisé par la limit
        $total= count($carnetRepo->findAll());
        $pages = ceil($total/$limit); # 3.4 => 4 [arrondie a 4 par la fonction "ceil()"]

        return $this->render('admin/index.html.twig', [
            // 'carnet' => $carnetRepo->findAll(),
            'carnet' => $carnetRepo->findBy([],['id'=>'DESC'],$limit,$start),
            'pages'=>$pages,
            'page'=>$page,
        ]);
    }



        // _____________edition d'un carnet par l'admin_______________
    
    /**
     * Afficher le formulaire d'edition / utiliser le formType "AdType" de l'annonce / ParamConverter
     * @Route("/admin/{slug}/edit", name ="admin_edit")
     * @param Carnet $carnet
     * @return void
     */
    public function edit (Carnet $carnet, Request $request, EntityManagerInterface $em)
    {
        
        $form=$this->createForm(CarnetType::class,$carnet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // faire persister l'$ad
            $em->persist($carnet);
            $em->flush();

            // notification
            $this->addFlash(
                "success",
                "Le carnet de <strong>{$carnet->getNom()}</strong>! a bien été modifier"
            );
            return $this->redirectToRoute("admin_index");
        }
        return $this->render('admin/edit.html.twig',[
             'formEdit'=>$form->createView(),
             'carnet'=>$carnet
        ]);
    }

    // ____________supprimer un carnet par l'admin

    /**
     * suprimier une annonce par l'admin
     * @ROUTE("/admin/{slug}/delete", name ="admin_delete")
     * @param Carnet $carnet
     * @return void
     */
    public function delete(Carnet $carnet, EntityManagerInterface $em)
    {
            $em->remove($carnet);
            $em->flush();
            // notification
            $this->addFlash(
                "success",
                "L'aeticle de <strong>{$carnet->getNom()}</strong> ! a bien été supprimé"
            );
        
        return $this->redirectToRoute('admin_index');
    }
}
