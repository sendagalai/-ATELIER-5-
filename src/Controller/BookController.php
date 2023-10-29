<?php

namespace App\Controller;
use App\Entity\Book;
use App\Form\BookType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/listBook', name: 'listBook')]
public function fetchAuthor(BookRepository $repo){
    $book=$repo->findAll();
    return $this->render('book/listBook.html.twig',[
        'a'=>$book]);
}
    #[Route('/addBook', name: 'addBook')]
    public function addBook(ManagerRegistry $mr,Request $req){
    $book=new Book();
    
    $form=$this->createForm(bookType::class,$book);
    $form->handleRequest($req);
    if($form->isSubmitted()){
        $author=$book->getAuthor();
        $author->setNbBooks($author->getNbBooks()+1);
   $book->setPublished(true);
    $em=$mr->getManager();
    $em->persist($book);
    $em->flush();
    return $this->redirectToRoute('listBook');
    }
    return $this->render('book/addBook.html.twig',[
        'f'=>$form->createView()
    ]);
    }
    #[Route('/updateBook/{ref}', name: 'updateBook')]
    public function updateBook(ManagerRegistry $mr,Request $req,$ref,BookRepository $repo){
    $s=$repo->find($ref);
    $form=$this->createForm(BookType::class,$s);
    $form->handleRequest($req);
    if($form->isSubmitted()){
    $em=$mr->getManager();
    $em->persist($s);
    $em->flush();
    return $this->redirectToRoute('listBook');
    }
    return $this->renderForm('book/updateBook.html.twig',[
        'f'=>$form
    ]);
    }

    #[Route('/deleteBook/{ref}', name: 'deleteBook')]
    public function deleteAuthor(ManagerRegistry $mr,$ref,BookRepository $repo){
    $s=$repo->find($ref);
    
    if($s!=null){
    $em=$mr->getManager();
    $em->remove($s);
    $em->flush();
    return $this->redirectToRoute('listBook');
    } else {
        return new Response("error ref  doesn't  exist !");
    }
    }
    #[Route('/BookByRef/{ref}', name: 'BookByRef')]
    public function searchBookByRef(BookRepository $repo,$ref){
        if($ref){
        $books=$repo->searchBookByRef($ref) ; }
        else {
            $books = $repo->findAll();
        }
        return $this->render('book/listBook.html.twig',[
            'a'=>$books]);
    }
    #[Route('/ListByAuthors', name: 'ListByAuthors')]
    public function booksListByAuthors(BookRepository $repo){
        $Authors=$repo->ListByAuthors() ;
        return $this->render('book/listBook.html.twig',[
            'a'=>$Authors]);
    }
#[Route('/before', name: 'before')]
public function findPublishedBefore2023(BookRepository $repo){
    $books=$repo->findPublishedBefore2023() ;
    return $this->render('book/listBook.html.twig',[
        'a'=>$books]);
}

//dql
#[Route('/count', name: 'count')]
public function countRomance(BookRepository $repo)
{ $book=$repo->countRomance();
    return $this->render('book/count.html.twig',[
        'count'=>$book]);
}
#[Route('/PublishedBetweenDates', name: 'PublishedBetweenDates')]
public function booksPublishedBetweenDates(BookRepository $bookRepository): Response
{
    $startDate = new \DateTime('2014-01-01');
    $endDate = new \DateTime('2018-12-31');
    $books = $bookRepository->findBooksPublishedBetweenDates($startDate, $endDate);
    return $this->render('book/listBook.html.twig', ['books' => $books,]);
}
#[Route('/listtwo', name: 'listtwo')]
public function list2(Request $request, BookRepository $repo)
    {
        $result = [];
        $searchForm = $this->createFormBuilder()
            ->add('ref', TextType::class, ['label' => 'Search by Ref'])
            ->add('search', SubmitType::class, ['label' => 'Search'])
            ->getForm();
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            $ref = $data['ref'];
            $result = $repo->searchBookByRef2($ref);
        } else {
            $result = $repo->findAll();
        }
        return $this->render('book/searchBook.html.twig', [
        'books' => $result,
        'searchForm' => $searchForm->createView(),
        ]);
    }
}
