<?php

declare(strict_types=1);

namespace Blog\Route;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Blog\LatestPosts;
use Twig\Environment;

class HomePage
{
    /**
     * @var LatestPosts
     */
    private LatestPosts $latestPosts;

    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * HomePage constructor.
     * @param LatestPosts $latestPosts
     * @param Environment $twig
     */
    public function __construct(LatestPosts $latestPosts, Environment $twig)
    {
        $this->latestPosts = $latestPosts;
        $this->twig = $twig;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function execute(Request $request, Response $response): Response
    {
        $posts = $this->latestPosts->get(3);

        $body = $this->twig->render('index.html.twig', [
            'posts' => $posts
        ]);
        $response->getBody()->write($body);
        return $response;
    }
}
