<?php
namespace FeedBundle\Handler;

use FeedBundle\Criteria\FeedCriteria;
use Symfony\Component\HttpFoundation\Request;

class FeedHandler
{
    private $criteria;

    public function __construct(Request $request)
    {
        $this->handleRequest($request);
    }

    public function handleRequest( Request $request)
    {

        $limit = $request->get('limit');
        $cursor = $request->get('cursor');
        $order = $request->get('order') ?? 'id';
        $profile = $request->get('profile') ?? null;
        $direction = $request->get('direction') ?? 'DESC';

        $dateFrom = $request->get('dateFrom') ? new \DateTime($request->get('dateFrom')) : null;
        $dateTo = $request->get('dateTo')? new \DateTime($request->get('dateTo')) : null;

        $this->criteria = new FeedCriteria($limit, $cursor, $order, $direction, $dateFrom, $dateTo, $profile);
    }



    public function getCriteria(): FeedCriteria
    {
        return $this->criteria;
    }
}