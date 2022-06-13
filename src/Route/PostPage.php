<?php

declare(strict_types=1);

namespace Blog\Route;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Blog\PostMapper;
use Twig\Environment;

class PostPage
{
    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * @var PostMapper
     */
    private PostMapper $postMapper;

    /**
     * PostPage constructor.
     * @param Environment $twig
     * @param PostMapper $postMapper
     */
    public function __construct(PostMapper $postMapper, Environment $twig)
    {
        $this->postMapper = $postMapper;
        $this->twig = $twig;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args = []): ResponseInterface
    {
        $post = $this->postMapper->getByUrlKey((string) $args['url_key']);

        if (empty($post)) {
            $body = $this->twig->render('not-found.html.twig');
        } else {
            $body = $this->twig->render('post.html.twig', [
                'post' => $post
            ]);
        }

        $response->getBody()->write($body);
        return $response;
    }
}
