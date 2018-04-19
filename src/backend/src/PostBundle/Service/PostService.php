<?php
namespace PostBundle\Service;

use PostBundle\Entity\Post;
use PostBundle\Repository\PostRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PostService
{

    private $postRepository;
    private $maxTagsLimit;
    private $maxAttachmentsLimit;

    public function __construct(
        PostRepository $postRepository,
        int $maxTagsLimit,
        int $maxAttachmentsLimit
    ){
        $this->postRepository = $postRepository;
        $this->maxTagsLimit = $maxTagsLimit;
        $this->maxAttachmentsLimit = $maxAttachmentsLimit;
    }

    public function getPostRepository(): PostRepository
    {
        return $this->postRepository;
    }

    public function create(Post $post): Post
    {
        $this->postRepository->saveWithTagsAndAttachments($post);

        return $post;
    }

    public function update(Post $post): Post
    {
        if(!$post->getId()) throw new AccessDeniedHttpException("post id required");

        $this->postRepository->saveWithTagsAndAttachments($post);

        return $post;
    }

    public function deletePostById(int $id)
    {
        // тут нужна проверка

        $postRepo = $this->getPostRepository();

        $post = $postRepo->getPostById($id);

        $postRepo->delete($post);

        return $post;
    }


}