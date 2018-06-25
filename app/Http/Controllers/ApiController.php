<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\GitHub;

/**
 * Base controller for Api.
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Base api method which returned information about repositories.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo(Request $request)
    {
        $firstRepositoryName = $request->input('first_repository');
        $secondRepositoryName = $request->input('second_repository');
        $firstRepository = explode('/', $firstRepositoryName);
        $secondRepository = explode('/', $secondRepositoryName);
        if (count($firstRepository) == 1) {
            $firstRepository[1] = $firstRepository[0];
        }
        if (count($secondRepository) == 1) {
            $secondRepository[1] = $secondRepository[0];
        }
        $repositoryFirst = new GitHub($firstRepository[0], $firstRepository[1]);
        $repositorySecond = new GitHub($secondRepository[0], $secondRepository[1]);
        $repositoryInfoFirst = $repositoryFirst->getRepositoryInfo();
        $repositoryInfoSecond = $repositorySecond->getRepositoryInfo();
        $answer = [
            'status' => 'ok',
            $firstRepositoryName => $repositoryInfoFirst,
            $secondRepositoryName => $repositoryInfoSecond,
        ];
        return response()->json($answer);
    }
}
