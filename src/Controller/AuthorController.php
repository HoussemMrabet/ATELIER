<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\MinmaxType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    public $authors = array(
        array('id' => 1, 'picture' => '/images/victor-hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showdbauthor', name: 'showdbauthor')]
    public function showdbauthor(AuthorRepository $AuthorRepository,Request $req): Response
    {
        $author=$AuthorRepository->findAll();
        $author = $AuthorRepository->AfficherAuthorsParOrdreAlphabetiqueParAdresseMail();
        $form = $this->createForm(MinmaxType::class);
            $form->handleRequest($req);
             if ($form->isSubmitted())
            {
            $minimum=$form->get('min')->getData();
            $maximum=$form->get('max')->getData();
            $authors = $AuthorRepository->MinimumMaximum($minimum,$maximum);
            return $this->renderForm('author/showDBauthor.html.twig', [
                'author' => $authors,
                'f' => $form
            ]);
            
            }
        return $this->renderForm('author/showdbauthor.html.twig', [
            'author' =>  $author,
            'f' => $form
        ]);
    }

    #[Route('/addauthor', name: 'addauthor')]
    public function addauthor(ManagerRegistry $managerRegistry): Response
    {
        $em=$managerRegistry->getManager();
      
        $author=new Author();
        $author->setUsername("3A54new");
        $author->setEmail("3A54new@esprit.tn");
        $em->persist($author);
        $em->flush();
        return new Response("great add");
        
    }

    #[Route('/addformauthor', name: 'addformauthor')]
    public function addformauthor(ManagerRegistry $managerRegistry,Request $req): Response
    {
        $em=$managerRegistry->getManager();
        $author=new Author();
        $form=$this->createForm(AuthorType::class,$author);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
        $em->persist($author);
        $em->flush();
    return $this->redirectToRoute('showdbauthor');
    }
        return $this->renderForm('author/addformauthor.html.twig', [
            'f' =>$form
        ]);
    }

    #[Route('/editauthor/{id}', name: 'editauthor')]
    public function editauthor($id,AuthorRepository $AuthorRepository,ManagerRegistry $managerRegistry,Request $req): Response
    {
        //var_dump($id).die();
        $em=$managerRegistry->getManager();
        $dataid=$AuthorRepository->find($id);
       //recuperation
        $form=$this->createForm(AuthorType::class,$dataid);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
        $em->persist($dataid);
        $em->flush();
    return $this->redirectToRoute('showdbauthor');
}
        //var_dump($dataid).die();

        return $this->renderForm('author/editauthor.html.twig', [
            'form' => $form,
        ]);
    
    }


    #[Route('/deleteauthor/{id}', name: 'deleteauthor')]
    public function deleteauthor($id,AuthorRepository $AuthorRepository,ManagerRegistry $managerRegistry): Response
    {
        $em=$managerRegistry->getManager();
        $dataid=$AuthorRepository->find($id);
        $em->remove($dataid);
        $em->flush();

return $this->redirectToRoute('showdbauthor');  
    }






    #[Route('/showauthor/{name}', name: 'app_showauthor')]
    public function showauthor($name): Response
    {
        return $this->render('author/show.html.twig', [
            'name'=>$name
        ]);
    }

    #[Route('/showtableauthor', name: 'showtableauthor')]
    public function showtableauthor(): Response
{ 
    return $this->render('author/showtableauthors.html.twig', [
        'authors' => $this->authors
    ]);
}
#[Route('/showbyidauthor/{id}', name: 'showbyidauthor')]
public function showbyidauthor($id): Response
{ 
    //var_dump($id).die();
    $author=null; 
    foreach($this->authors as $authorD){
        if ($authorD['id']==$id)  
        {$author=$authorD;}
    }
   // var_dump($author).die();
return $this->render('author/showbyidauthor.html.twig', [
    'author' => $author
]);
}

    #[Route('/deleteZeroBooks', name: 'deleteZeroBooks')]
    public function deleteZeroBooks(AuthorRepository $AuthorRepository): Response
    {
        $AuthorRepository->RomoveNOBooks();  
        return $this->redirectToRoute('showdbauthor');
    }

}
