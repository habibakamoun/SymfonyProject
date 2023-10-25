<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use ContainerQpIc1h5\getBookRepositoryService;
use Doctrine\Persistence\ManagerRegistry ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{   #[Route('/book/readb', name: 'readb')]
    public function readb(BookRepository $bookRepository): Response
    {
        $book = $bookRepository->findAll();
        return $this->render('book/readb.html.twig', [
            'books' => $book,
        ]);
        
    }

    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/book/add', name: 'addBook')]
    public function addBook(ManagerRegistry $doctrine, Request $request): Response
    {
        $em= $doctrine->getManager();
        $book= new Book();
        $form= $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($book);
            $auteur = $book->getAuthor();
            $auteur->setNbBooks($auteur->getNbBooks()+1);
            $em->flush();
            return $this->redirectToRoute('readb');
        }
        else {
            return $this->renderForm('book/addBook.html.twig', ['f'=>$form]);
        }
    }
    #[Route('/book/delete/{id}', name: 'delete')]
    public function deleteBook($id, ManagerRegistry $doctrine):Response
    {
        $em = $doctrine -> getManager();
        $book = $doctrine -> getRepository(Book::class)->find($id);
        /* if (!$author){\Exception}; or bel length*/
        $em ->remove($book);
        $em ->flush();
        return  $this->redirectToRoute('readb');
    }
    #[Route('/book/update/{id}', name: 'update')]
    public function updateAuthor($id, ManagerRegistry $doctrine, Request $request):Response
    {
        $em = $doctrine -> getManager();
        $book = $doctrine -> getRepository(Book::class)->find($id);
        /* if (!$author){\Exception}; or bel length*/
        $form = $this->createForm(BookType::class,$book);
        $form ->handleRequest($request);
        if ($form -> isSubmitted()){
            $em->persist($book);
            $em->flush();
    
            return $this->redirectToRoute('readb');
        }
        else {
            return $this->renderForm('book/update.html.twig',['f'=>$form]);
        }
    }
    #[Route('/book/details/{id}', name: 'details')]
    public function getDetails($id,BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
        
    }

}
