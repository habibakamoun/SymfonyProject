<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\AuthorType;

class AuthorController extends AbstractController
{
    public $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
        'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => 'William Shakespeare', 'email' =>
        'william.shakespeare@gmail.com', 'nb_books' => 200 ),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
        'taha.hussein@gmail.com', 'nb_books' => 300),
        );

    #[Route('/author', name: 'app_author')]
    
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/author/show/{name}', name: 'showAuthor')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig', [
            'authorName' => $name,
        ]);
    }
    #[Route('/authors', name: 'list')]
    public function list():Response
    {
        $nbbooks=0; // Initialize the sum variable
        foreach($this->authors as $author) {
            $nbbooks+=$author['nb_books'];
        }
            return $this->render('author/list.html.twig',[
                'authors' =>$this->authors,
                'nbauthors' =>count($this->authors),
                'nbbooks'=>$nbbooks,
            ]);
    }
    #[Route('/authors/details/{id}', name: 'authorDetails')]
    public function auhtorDetails($id):Response
    {
        return $this->render('author/showAuthor.html.twig',['authors'=>$this->authors,"id"=>$id-1]);
    }
    #[Route('/author/read', name: 'read')]
    public function listeAuthor(AuthorRepository $authorRepository)
    {
        $author=$authorRepository->findAll();
        return $this->render('author/read.html.twig',['authors'=>$author]);
    }
    #[Route('/author/add', name: 'add')]
    public function addAuthor(ManagerRegistry $doctrine, Request $request)
    {
        $em = $doctrine->getManager();
        $author = new Author();
       /*  $author->setUsername("sami");
        $author->setEmail("sami.ghazouani@gmail.com"); */
       
        $form = $this->createForm(AuthorType::class,$author);
        $form ->handleRequest($request);
        if ($form -> isSubmitted()){
            $em->persist($author);
            $em->flush();
    
            return $this->redirectToRoute('read');
        }
        else {
            return $this->renderForm('author/add.html.twig',['f'=>$form]);
        }
       
    }
    #[Route('/author/delete/{id}', name: 'delete')]
    public function deleteAuthor($id, ManagerRegistry $doctrine):Response
    {
        $em = $doctrine -> getManager();
        $author = $doctrine -> getRepository(Author::class)->find($id);
        /* if (!$author){\Exception}; or bel length*/
        $em ->remove($author);
        $em ->flush();
        return  $this->redirectToRoute('read');
    }
    #[Route('/author/update/{id}', name: 'update')]
    public function updateAuthor($id, ManagerRegistry $doctrine, Request $request):Response
    {
        $em = $doctrine -> getManager();
        $author = $doctrine -> getRepository(Author::class)->find($id);
        /* if (!$author){\Exception}; or bel length*/
        $form = $this->createForm(AuthorType::class,$author);
        $form ->handleRequest($request);
        if ($form -> isSubmitted()){
            $em->persist($author);
            $em->flush();
    
            return $this->redirectToRoute('read');
        }
        else {
            return $this->renderForm('author/update.html.twig',['f'=>$form]);
        }
    }
}
