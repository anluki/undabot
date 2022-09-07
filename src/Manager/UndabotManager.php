<?php
declare(strict_types=1);

namespace App\Manager;

use App\Dto\Response\IssueResponseDto;
use App\Entity\TermScore;
use App\RestApi\GitHubApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UndabotManager{


    private  EntityManagerInterface $entityManager;
    private static string $rocks = "rocks";
    private static string $sucks = "sucks";

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getTermScore(Request $request) : ?TermScore{
        $gitHubApi = new GitHubApi();
        $ret = null;

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $serializer = new Serializer($normalizers, $encoders);

        $parameters = null;
        $term = null;
        $termRocks = null;
        $termSucks = null;
        $type = null;
        $perPage = null;
        $page = null;
        $order = null;

        if ($request->getContent()) {
            $parameters = json_decode($request->getContent(), true);
        }

        if(!isset($parameters['term'])){
            return $this->json("Missing term");
        } else {
            $term = trim($parameters['term']);
        }

        $termRocks = $term . "%20" . UndabotManager::$rocks;
        $termSucks = $term . "%20" . UndabotManager::$sucks;

        if(!isset($parameters['type'])){
            return $this->json("Missing type");
        } else {
            $type = $parameters['type'];
        }

        if(isset($parameters['perPage'])) {
          $perPage = $parameters['per_page'];
        }

        if(isset($parameters['page'])) {
            $page = $parameters['page'];
        }

        if(isset($parameters['order'])) {
            $order = $parameters['order'];
        }


        $dbResult = $this->getTermScoreFromDatabase($term);

        if (!isset($dbResult)) {
            $positiveResultResponse = $serializer->deserialize($gitHubApi->restCallGet($termRocks, $type, $order, $perPage, $page), IssueResponseDto::class, 'json');
            $negativeResultResponse = $serializer->deserialize($gitHubApi->restCallGet($termSucks, $type, $order, $perPage, $page), IssueResponseDto::class, 'json');

            $positiveCount = (int) $propertyAccessor->getValue($positiveResultResponse, 'totalCount');
            $negativeCount = (int) $propertyAccessor->getValue($negativeResultResponse, 'totalCount');

            $totalCount = (int) ($positiveCount + $negativeCount);
            $score = number_format((($positiveCount/$totalCount) * 10), 2,'.', '');

            $termScore = new TermScore();
            $termScore->setTerm($term);
            $termScore->setScore($score);
            $this->saveTermScoreInDatabase($termScore);

            $ret = $termScore;
        } else {
            $ret = $dbResult;
        }
        return $ret;
    }

    private function getTermScoreFromDatabase ($term = null) {
        $ret = array();
        if($term == null) {
            return $ret;
        }
        $repository = $this->entityManager->getRepository(TermScore::class);
        $ret = $repository->getRowByTerm($term);
        return $ret;
    }

    private function saveTermScoreInDatabase (TermScore $termScore) : void{
        $repository = $this->entityManager->getRepository(TermScore::class);
        $repository->add($termScore, true);
    }
}