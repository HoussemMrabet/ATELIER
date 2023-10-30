<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Form\SearchType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

        #[Route('/ShowBookTable', name: 'ShowBookTable')]
        public function ShowBookTable(BookRepository $BookRepository,Request $Req): Response
        {
            $book = $BookRepository->findBy(['published' => true]);
            $category="Romance";
            $nameW="William Shakespear";
            $form = $this->createForm(SearchType::class);
            $form->handleRequest($Req);
            $NBRUnpublishedBooks = count($BookRepository->findBy(['published' => false]));
            $NBRPublishedBooks = count($BookRepository->findBy(['published' => true]));
            $book = $BookRepository->TrierParAuteur();  
            $BookRepository->updateCategory($category, $nameW);
            $NBROfSIFIBooks = $BookRepository->NBROfSIFIBooks(); 

            /* if ($form->isSubmitted())
            {
             $data=$form->get('ref')->getData();
             $books = $BookRepository->ChercherBookAbecREF($data);
            
             return $this->renderForm('book/ShowBookTable.html.twig', [
                 'book' => $books,
                 'NBRUnpublishedBooks' => $NBRUnpublishedBooks,
                 'NBRPublishedBooks' => $NBRPublishedBooks,
                 'f' => $form
             ]);
            } */
           
            return $this->renderForm('book/ShowBookTable.html.twig', [
                'book' => $book,
                'NBRUnpublishedBooks' => $NBRUnpublishedBooks,
                'NBRPublishedBooks' => $NBRPublishedBooks,
                'NBROfSIFIBooks' => $NBROfSIFIBooks,
                'f' => $form
            ]);
        }



        #[Route('/AjouterUnLivre', name: 'AjouterUnLivre')]
        public function AjouterUnLivre(ManagerRegistry $ManagerRegistry, Request $Req): Response
        {
            $em = $ManagerRegistry->getManager();
            $book = new Book();
            $book->setPublished(true);
            $form = $this->createForm(BookType::class, $book);
            $form->handleRequest($Req);
          
            if ($form->isSubmitted() and $form->isValid()) {
                $em->persist($book);
                $em->flush();
                return $this->redirectToRoute('ShowBookTable');
            }
            return $this->renderForm('book/AjouterUnLivre.html.twig', [
                'f' =>$form
            ]);
        }
    
                    #[Route('/UpdateLivre/{ref}', name: 'UpdateLivre')]
                    public function UpdateLivre($ref,BookRepository $BookRepository,ManagerRegistry $ManagerRegistry, Request $Req): Response
                    {
                        $em = $ManagerRegistry->getManager();
                        $dataid = $BookRepository->find($ref);
                        $form = $this->createForm(BookType::class, $dataid);
                        $form->handleRequest($Req);
                        if ($form->isSubmitted() and $form->isValid()) {
                            $em->persist($dataid);
                            $em->flush();
                            return $this->redirectToRoute('ShowBookTable');
                        }
                        return $this->renderForm('book/UpdateLivre.html.twig', [
                            'f' =>$form
                        ]);
                    }
    
                     #[Route('/DeleteBook/{ref}', name: 'DeleteBook')]
                     public function DeleteBook($ref,BookRepository $BookRepository,ManagerRegistry $ManagerRegistry): Response
                     {
                         $em = $ManagerRegistry->getManager();
                         $dataid = $BookRepository->find($ref);
                         $form = $this->createForm(BookType::class, $dataid);
                             $em->remove($dataid);
                             $em->flush();
                             return $this->redirectToRoute('ShowBookTable');
    
                     }
    
                      #[Route('/BookDetails/{ref}', name: 'BookDetails')]
                      public function BookDetails($ref,BookRepository $BookRepository): Response
                      {
                          $book = $BookRepository->find($ref);
                         
                          return $this->renderForm('book/BookDetails.html.twig', [
                            'book' => $book
                        ]);
     
                      }

                         #[Route('/LivresPublieAvant2023', name: 'LivresPublieAvant2023')]
                        public function LivresPublieAvant2023(BookRepository $BookRepository): Response
                        {
                        
                            $book = $BookRepository->LivresPublieAvant2023();
                            return $this->renderForm('book/LivresPublieAvant2023.html.twig', [
                                'books' => $book,
                            ]);
                        }

                        #[Route('/AfficherLivresPublieEntreDeuxDate', name: 'AfficherLivresPublieEntreDeuxDate')]
                        public function AfficherLivresPublieEntreDeuxDate(BookRepository $BookRepository): Response
                        {
                        
                            $book = $BookRepository->AfficherLivresPublieEntreDeuxDate();
                            return $this->renderForm('book/AfficherLivresPublieEntreDeuxDate.html.twig', [
                                'books' => $book,
                            ]);
                        }



}
