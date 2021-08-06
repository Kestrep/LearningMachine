<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Entity\Category;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubcategoryRepository;
use DateInterval;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/card")
 * @Security("is_granted('ROLE_USER')", message="Vous devez vous connecter pour pouvoir continuer")
 */
class CardController extends AbstractController
{
    /**
     * @Route("/", name="card_index", methods={"GET"})
     */
    public function index(CardRepository $cardRepository): Response
    {
        return $this->render('card/index.html.twig', [
            'cards' => $cardRepository->findUserCards(),
        ]);
    }

    /**
     * @Route("/get", name="get_cards", methods={"POST"})
     */
    public function getCards(CardRepository $cardRepository, NormalizerInterface $normalizer, Request $request): Response
    {
        $parameters = json_decode($request->getContent(), true);

        if ($parameters['count'] === null) {
            return $this->json([], 200, [], ['groups' => 'card:read']);
        }

        $cards = $cardRepository->findUserCards($parameters['count'], $parameters['idList']);
        
        // Faire remonter les cartes à jouer en fonction de leur date de remise en jeu
        
        $normalizedCards = $normalizer->normalize($cards, null, [
            'groups' => 'card:read'
        ]);

        
        foreach($normalizedCards as $key => $value) {

            $localCard = $cards[$key];
            $normalizedCards[$key] += ["html" => $this->renderView('card/_flashcard.html.twig', [
                'card' => $localCard
            ])];
        }

        $json = json_encode($normalizedCards);

        // return $this->json($json, 200, [], ['groups' => 'card:read']);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/stage", name="stage_update", methods={"POST"})
     */
    public function updateCard(CardRepository $cardRepository, EntityManagerInterface $em, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $card = $cardRepository->find($data['id']);

        $old_date = $card->getPlayAt();

        $currentStage = $card->getStage();
        if($data['action'] === 'stage-up') {
            $card->setStage($currentStage + 1);
            $message = "Une carte apprise !!!";
        } else if ($data['action'] === 'stage-down') {
            $card->setStage($currentStage - 1);
            $message = "On va faire descendre la pression !";
        }

        $parameters = [
            0 => 'P1D',
            1 => 'P3D',
            2 => 'P1W',
            3 => 'P2W',
            4 => 'P1M',
            5 => 'P3M',
            6 => 'P6M',
            7 => 'P12M'
        ];

        // On modifie la date en fonction de la valeur du stage après sa modification
        foreach ($parameters as $key => $value) {
            if($card->getStage() === $key) {

                $today = new \DateTime();
                $interval = new \DateInterval($value);
                $card->setPlayAt($today->add($interval));
            }
        }

        $em->persist($card);
        $em->flush();

        return $this->json([
            'message' => $message,
            'exclamation' => 'Hey !',
            'color' => 'orange',
            'icon' => 'feather',
        ], 201, [], ['groups' => 'card:read']);
    }

    // TODO Revoir cette partie
    /**
     * @Route("/play", name="card_play", methods={"GET"})
     */
    public function play(CardRepository $cardRepository): Response
    {
        return $this->render('card/play.html.twig', [
            'card' => $cardRepository->findAll()['0'],
        ]);
    }
    

    // ! @Security("post.isAuthor(user)") dans https://symfony.com/doc/4.2/best_practices/security.html
    // TODO people can modify a card that he didn't own
    /**
     * @Route("/new", name="card_new", methods={"GET","POST"})
     * @Route("/{id}/edit", name="card_edit", methods={"GET","POST"})
     */
    public function new(Request $request, Card $card = null, EntityManagerInterface $em): Response
    {
        // Vérification du user
        if($card && $card->getSubcategory()->getCategory()->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('card_index');
        } else if(!$card) {
            $card = new Card();
        }

        // Initiation formulaire
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);
        
        // Form submitting
        if ($form->isSubmitted() && $form->isValid()) {
            $card->setStage(2);
            $card->setCreatedAt(new \DateTime);

            if($card->getID() !== null) {
                $subcategory = $card->getSubcategory();
                $category = $subcategory->getCategory();
                
                $subcategory->setUpdatedAt(new \DateTime);
                $category->setUpdatedAt(new \DateTime);

                $em->persist($subcategory, $category);
            }
            
            $em->persist($card);
            $em->flush();

            if($request->isXmlHttpRequest()) {
                return $this->json("Comme une lettre à la poste", 201);
            };
            return $this->redirectToRoute('card_index');
        }

        // Form rendering
        if($request->isXmlHttpRequest()) {
            return new Response(
                $this->renderView('card/_form.html.twig', [
                    'edit' => $card->getID() !== null,
                    'ajaxForm' => true,
                    'card' => $card,
                    'form' => $form->createView(),
                ])
            );
        };
        return $this->render('card/form.html.twig', [
            'edit' => $card->getID() !== null,
            'ajaxForm' => false,
            'card' => $card,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="card_delete", methods={"GET"})
     */
    public function delete(Request $request, Card $card): Response
    {
        if ($this->isCsrfTokenValid('delete'.$card->getId(), $request->query->get('token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($card);
            $entityManager->flush();
        }

        return $this->redirectToRoute('card_index');
    }
}
