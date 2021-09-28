<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route("/book/new", name="new")
     * Method ({"GET", "POST"})
     */
    function new (Request $request, EntityManagerInterface $entityManager): Response 
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        if (!$form->handleRequest($request)) {
            return new Response('Cannot create new record');
        }
        
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $book = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($book);
                $entityManager->flush();

                return $this->redirectToRoute('books');
            }
            
        } catch (Exception $e) {
            return new Response($e->getMessage());
        }

        return $this->render('create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/books", name="books")
     * Method ({"GET"})
     */
    public function index(): Response
    {
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findAll();

        return $this->render('books.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * @Route("/book/{id}", name="book")
     * Method ({"GET"})
     */
    public function read($id, Request $request)
    {
        $book = $this->getDoctrine()
            ->getRepository(Book::class)
            ->find($id);

        if (empty($book)) {
            return new Response('Cannot find this record');
        }
        return $this->render('book.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/book/delete/{id}", name="delete")
     * Method ({"GET"})
     */
    public function delete($id, Request $request)
    {
        $book = $this->getDoctrine()
            ->getRepository(Book::class)
            ->find($id);

        if (empty($book)) {
            return new Response('Cannot delete this record');
        }
        
        try {
            $entityManager = $this->getDoctrine()
            ->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
            
            return $this->redirectToRoute('books');

        } catch (Exception $e) {
            return new Response($e->getMessage());
        }
    }

    /**
     * @Route("/book/update/{id}", name="update")
     * Method ({"GET", "POST"})
     */
    public function update($id, Request $request)
    {
        $book = $this->getDoctrine()
            ->getRepository(Book::class)
            ->find($id);

        if (empty($book)) {
            return new Response('Cannot update this record');
        }

        try {

            $form = $this->createForm(BookType::class, $book);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $book = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($book);
                $entityManager->flush();
                
                return $this->redirectToRoute('books');
            }

        } catch (Exception $e) {
            return new Response($e->getMessage());
        }

        return $this->render('create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
