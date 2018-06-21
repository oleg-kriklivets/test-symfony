<?php

namespace App\Controller\Api;

use App\Entity\League;
use App\Entity\Team;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TeamController extends BaseApiController
{

    /**
     * @param $manager ManagerRegistry
     * @return JsonResponse
     * @Route("api/teams", name="teams", methods={"get"})
     */
    public function index(ManagerRegistry $manager): JsonResponse
    {
        return $this->createJsonResponse(
            $manager->getRepository(Team::class)->findAll(),
            200,
            ['show', 'with-league']
        );
    }

    /**
     * @param League $league
     * @return JsonResponse
     * @Route("api/league/{league}/teams", name="leagueTeams", methods={"get"})
     */
    public function teams(League $league)
    {
        return $this->createJsonResponse(
            $this->getDoctrine()->getManager()->getRepository(Team::class)->findBy(['league' => $league]),
            200,
            ['show']
        );
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @Route("api/team", name="createTeam", methods={"post"})
     */
    public function createTeam(Request $request, ValidatorInterface $validator, ObjectManager $objectManager)
    {
        $team = $this->deserializeRequest($request, Team::class, ['insert']);
        $errors = $validator->validate($team, null, ['insert']);
        if ($errors->count()) {
            return $this->createJsonResponse($errors, 400);
        }
        $objectManager->persist($team);
        $objectManager->flush();
        return $this->createJsonResponse($team);
    }

    /**
     * @param Team $team
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param ObjectManager $objectManager
     * @return JsonResponse
     * @Route("api/team/{team}", name="updateTeam", methods={"post"})
     */
    public function updateTeam(
        Team $team,
        Request $request,
        ValidatorInterface $validator,
        ObjectManager $objectManager
    ) {
        /** @var Team $teamUpdate */
        $teamUpdate = $this->deserializeRequest($request, Team::class, ['update']);
        $errors = $validator->validate($team, null, ['update']);
        if ($errors->count()) {
            return $this->createJsonResponse($errors, 400);
        }
        $team->setName($teamUpdate->getName());
        $team->setStrip($teamUpdate->getStrip());
        $team->setLeague($teamUpdate->getLeague());
        $objectManager->persist($team);
        $objectManager->flush();
        return $this->createJsonResponse($team);
    }
}
