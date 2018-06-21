<?php

namespace App\Controller\Api;

use App\Entity\League;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LeagueController extends BaseApiController
{
    /**
     * @param $manager ManagerRegistry
     * @return JsonResponse
     * @Route("api/leagues", name="leagues", methods={"get"})
     */
    public function index(ManagerRegistry $manager): JsonResponse
    {
        return $this->createJsonResponse(
            $manager->getRepository(League::class)->findAll(),
            200
        );
    }

    /**
     * @param League $league
     * @return JsonResponse
     * @Route("api/league/{league}", name="deleteLeague", methods={"delete"})
     */
    public function delete(League $league): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($league);
        $em->flush();
        return $this->createJsonResponse(
            ['result' => true],
            200
        );
    }
}
