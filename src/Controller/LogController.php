<?php

namespace App\Controller;

use App\Repository\LogCountRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController
{
    private LogCountRepository $repository;

    /**
     * @param LogCountRepository $repository
     */
    public function __construct(LogCountRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/logs/count", methods={"GET"})
     */
    public function count(Request $request): JsonResponse
    {
        // Get the filter criteria
        $serviceNames = $request->query->get('serviceNames');
        $statusCode = $request->query->getInt('statusCode');
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        // Get the count of rows that match the filter criteria
        try {
            $count = $this->repository->countByFilter($serviceNames, $statusCode, $startDate, $endDate);
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }

        return new JsonResponse(['count' => $count]);
    }
}
