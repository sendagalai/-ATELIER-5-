<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AuthorRepository;
use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/showauthor/{name}', name: 'showauthor')]
    public function showAuthor($name): Response
    {
        return $this->render('author/author.html.twig', [
            'name' => $name,
        ]);
    }
    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        $authors = array(
            array('id' => 1, 'picture' => '../images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '../images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '../images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),
            );
        return $this->render('author/list.html.twig', [
            'a' => $authors,
        ]);
    }
    #[Route('/authorDetails/{id}', name: 'authorDetails')]
    public function authorDetails($id): Response
    { $authors = array(
        array('id' => 1, 'picture' => '../images/Victor-Hugo.jpg','username' => ' Victor
        Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '../images/william-shakespeare.jpg','username' => '
        William Shakespeare', 'email' => ' william.shakespeare@gmail.com', 'nb_books' =>
        200 ),
        array('id' => 3, 'picture' => '../images/Taha_Hussein.jpg','username' => ' Taha
        Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),);
        return $this->render('author/showAuthor.html.twig', [
            'd' => $id,
            'a' => $authors
        ]);
    }
    //methode 1
#[Route('/listAuth', name: 'listAuth')]
public function fetchAuthor(AuthorRepository $repo){
    $Authors=$repo->findAll();
    return $this->render('author/listAuthor.html.twig',[
        'result'=>$Authors]);
}
//methode 2
/*
#[Route('/listAuth', name: 'listAuth')]
public function fetchAuthor(AuthorRepository $repo){
   
    return $this->render('author/listAuthor.html.twig',[
        'result'=>$repo->findAll()]);
*/

#[Route('/addAuth',name:'addauth')]
public function addAuthor(ManagerRegistry $mr){
$author=new Author();
$author->setUsername('Victor Hugo');
$author->setEmail('victor.hugo@gmail.com');
$em=$mr->getManager();
$em->persist($author);
$em->flush();
return $this->redirectToRoute('listAuth');
}
#[Route('/addtwo', name: 'addtwo')]
public function addAuthortwo(ManagerRegistry $mr,Request $req){
$s=new Author();
//$s->setName('mmed');
$form=$this->createForm(AuthorType::class,$s);
$form->handleRequest($req);
if($form->isSubmitted()){
$em=$mr->getManager();
$em->persist($s);
$em->flush();
return $this->redirectToRoute('listAuth');
}
return $this->render('author/add.html.twig',[
    'f'=>$form->createView()
]);
}
#[Route('/update/{id}', name: 'update')]
public function updateAuthor(ManagerRegistry $mr,Request $req,$id,AuthorRepository $repo){
$s=$repo->find($id);
$form=$this->createForm(AuthorType::class,$s);
$form->handleRequest($req);
if($form->isSubmitted()){
$em=$mr->getManager();
$em->persist($s);
$em->flush();
return $this->redirectToRoute('listAuth');
}
return $this->renderForm('author/update.html.twig',[
    'f'=>$form
]);
}
#[Route('/delete/{id}', name: 'delete')]
public function deleteAuthor(ManagerRegistry $mr,Request $req,$id,AuthorRepository $repo){
$s=$repo->find($id);

if($s!=null){
$em=$mr->getManager();
$em->remove($s);
$em->flush();
return $this->redirectToRoute('listAuth');
} else {
    return new Response("error id  doesn't  exist !");
}

}
#[Route('/AuthorByEmail', name: 'AuthorByEmail')]
public function listAuthorByEmail(AuthorRepository $repo){
    $Authors=$repo->listAuthorByEmail() ;
    return $this->render('author/listAuthor.html.twig',[
        'result'=>$Authors]);
}


}
