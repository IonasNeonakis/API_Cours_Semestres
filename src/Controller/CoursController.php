<?php


namespace App\Controller;


use App\Entity\Cours;
use App\Entity\Semestre;
use App\Repository\CoursRepository;
use App\Repository\SemestreRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotNull;

class CoursController extends AbstractController
{

    /**
     * @Route("/api/cours", name="accueilCours",methods={"GET"})
     */
    public function accueilCours(CoursRepository $repo,SerializerInterface $serializer){
        if(!$cours=$repo->findAll()){
            $cours=[];
        }
        $res =$serializer->serialize($cours,'json');
        $response = new Response($res);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api/cours/creer", name="creer_cours")
     * @Route("/cours/{id}/edit", name="edit_cours")
     */
    public  function creerCours(Request $request,SemestreRepository $repoSem, CoursRepository $repoCours ,EntityManagerInterface $manager){
        $body = json_decode($request->getContent(),true);

        if($body['estnouveau']) {
            $cours = new Cours();
        }else {
            $cours = $repoCours->find($body['id']);
        }

        $cours->setNom($body['nom']);
        $cours->setDescription($body['description']);

        $sem = $repoSem->find($body['semestre']['id']);
        $cours->setSemestre($sem);

        $manager->persist($cours);
        $manager->flush();

        return new JsonResponse();









        /*
        $creer=false;
        if(!$cours){
            $creer=true;
            $cours = new Cours();
        }



                $form = $this->createFormBuilder($cours)
                    ->add('nom', TextType::class,['required'=>true])
                    ->add('description', TextareaType::class, ['required'=>true])
                    ->add('semestre', EntityType::class, [
                        'class'=>Semestre::class,
                        'choice_label'=> 'numeroSemestre',
                        'constraints'=> new NotNull(['message'=>"Il n'existe aucun semestre, créés-en un d'abord"])])
                    ->getForm();
                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                    $manager->persist($cours);
                    $manager->flush();

                    return $this->redirectToRoute('cour_show',['id'=>$cours->getId()]);
                }
                        return $this->render("cours/creer_cours.html.twig", ['formCours'=> $form->createView(),'creer'=>$creer]);

                 */
    }

    /**
     * @Route("/api/cours/{id}", name="cour_show",methods={"GET"})
     */
    public function show($id, CoursRepository $repo, SerializerInterface $serializer){
        if(!$cour = $repo->find($id)){
            return $this->accueilCours($repo);
        }

        $res =$serializer->serialize($cour,'json');
        $response = new Response($res);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Route("/api/cours/{id}/delete", name="delete_cours",methods={"DELETE"})
     */
    public function deleteCours($id,CoursRepository $repo,  EntityManagerInterface $manager){
        if($cours = $repo->find($id)) {
            $manager->remove($cours);
            $manager->flush();
        }

        return new JsonResponse();
    }
}
