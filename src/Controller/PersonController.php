<?php

namespace App\Controller;

use DateTime;
use App\Entity\Person;
use App\Form\PersonType;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Form\AddBeneficiaryType;
use App\Service\TransactionService;
use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/person")
 */
class PersonController extends AbstractController
{
    /**
     * @Route("/", name="person_index", methods={"GET"})
     */
    public function index(PersonRepository $personRepository): Response
    {
        return $this->render('person/index.html.twig', [
            'people' => $personRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="person_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('person_index');
        }

        return $this->render('person/new.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="person_show", methods={"GET"})
     */
    public function show(Person $person): Response
    {
        return $this->render('person/show.html.twig', [
            'person' => $person,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="person_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Person $person): Response
    {
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('person_index');
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add/beneficiary/{id}", name="add_beneficiary", methods={"GET","POST"})
     */
    public function addBeneficiary(Request $request, Person $person)
    {
        $form = $this->createForm(AddBeneficiaryType::class, $person, [
            'person' => $person
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('person_index');
        }

        return $this->render('person/addBeneficiary.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add/transaction/{id}", name="add_transaction", methods={"GET","POST"})
     */
    public function addTransaction(Request $request, Person $person, TransactionService $ts)
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction, [
            'person' => $person
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datetime = new DateTime();
            $transaction->setDate($datetime);

            $ts->creditDebitAccounts($transaction->getCreditAccount(), $transaction->getDebitAccount(), $transaction->getAmount());

            $em = $this->getDoctrine()->getManager();
            $em->persist($transaction);
            $em->flush();

            return $this->redirectToRoute('person_index');
        }

        return $this->render('person/addTransaction.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="person_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Person $person): Response
    {
        if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($person);
            $entityManager->flush();
        }

        return $this->redirectToRoute('person_index');
    }
}
