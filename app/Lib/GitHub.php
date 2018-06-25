<?php
/**
 * Created by PhpStorm.
 * User: witold
 * Date: 23.06.2018
 * Time: 21:07
 */

namespace App\Lib;

use Illuminate\Support\Facades\Log;


class GitHub
{
    public $author;
    public $repository;
    private $url;

    const FULL_SEARCH = true;
    const REPOS = 'repos/';
    const PULL_REQUEST = 'pulls';
    const RELEASE = 'releases';
    const REPOSITORY_INFO = 'search/repositories?q=';

    public function __construct($author, $repository)
    {
        $this->url = env('GIT_HUB_URL');
        $this->author = $author;
        $this->repository = $repository;
    }

    /**
     * Method for call to github Api.
     * @param $param
     * @param bool $fullSearch
     * @return array
     */
    private function call($param, $fullSearch = false)
    {

        if ($fullSearch) {
            $requestUrl = $this->url . $param;
        } else {
            $requestUrl = $this->url . self::REPOS . $this->author . '/' . $this->repository . '/' . $param;
        }
        $curlSession = curl_init();
        if ($curlSession === false) {
            throw new Exception('failed to initialize');
        }
        curl_setopt($curlSession, CURLOPT_USERAGENT, 'Awesome-Octocat-App');
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlSession, CURLOPT_URL, $requestUrl);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        $jsonData = json_decode(curl_exec($curlSession));
        $httpcode = curl_getinfo($curlSession, CURLINFO_HTTP_CODE);
        if ($httpcode == 200) {
            $answear = [
                'status' => 'ok',
                'data' => $jsonData
            ];
            return $answear;
        } else {
            Log::info('HTTP ERROR.  code: ' . $httpcode);
        }

    }

    /**
     * Method for getting information about pull requests.
     * @return array
     */
    private function getPullRequests()
    {
        $pullRequests = $this->call(self::PULL_REQUEST);
        $openNumber = 0;
        $closeNumber = 0;
        if (!empty($pullRequests['data'])) {
            foreach ($pullRequests['data'] as $key => $value) {
                if ($value->state === 'open') {
                    $openNumber++;
                } else {
                    $closeNumber++;
                }
            }
        }
        $result = [
            'open' => $openNumber,
            'close' => $closeNumber
        ];
        return $result;
    }

    /**
     * Method for getting information last release.
     * @return false|string
     */
    private function getLatestRelease()
    {
        $release = $this->call(self::RELEASE);
        if (isset($release['data']['0']->published_at)) {
            return date('Y-m-d H:i', strtotime($release['data']['0']->published_at));
        } else {
            return 'Date is undefined';
        }

    }

    /**
     * Method for getting information repository.
     * @return false|string
     */
    public function getRepositoryInfo()
    {
        $request = self::REPOSITORY_INFO . $this->author . '/' . $this->repository;
        $info = $this->call($request, self::FULL_SEARCH);
        if ($info['status'] == 'ok') {
            if ($info['data']->total_count === 0) {
                $answer = [
                    'Repository is not found.'
                ];
                return $answer;
            }
            $forksCount = $info['data']->items[0]->forks_count;
            $stargazersCount = $info['data']->items[0]->stargazers_count;
            $watchersCount = $info['data']->items[0]->watchers_count;
            $latestRelease = $this->getLatestRelease();
            $pullRequests = $this->getPullRequests();
            $answer = [
                $this->author . '/' . $this->repository => [
                    'Number of forks' => $forksCount,
                    'Number of stars' => $stargazersCount,
                    'Number of watchers' => $watchersCount,
                    'Date of the latest release' => $latestRelease,
                    'Pull requests' => $pullRequests
                ]
            ];
            return $answer;
        } else {
            return 'Repository is not found.';
        }
    }

}