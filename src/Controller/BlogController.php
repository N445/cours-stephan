<?php

namespace App\Controller;

use App\Repository\Blog\BlogRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    public function __construct(
        private readonly BlogRepository $blogRepository,
    )
    {
    }

    #[Route('/blogs', name: 'APP_BLOGS')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $this->blogRepository->getBlogsQuery(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10, /*limit per page*/
        );


        return $this->render('blog/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/blogs/{id}', name: 'APP_BLOGS_SHOW')]
    public function show(int $id): Response
    {
        if (!$blog = $this->blogRepository->find($id)) {
            return $this->redirectToRoute('APP_BLOGS');
        }
        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
        ]);
    }
}
