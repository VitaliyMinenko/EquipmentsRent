<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\GitHub;

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

    public function getInfo(Request $request)
    {
//        $secondRepositoryName = 'schibsfasfasdfasdfasted/asdsdadasdasd adasda sdasd as';
//        $firstRepositoryName = 'schibstedudp-pipe';
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
