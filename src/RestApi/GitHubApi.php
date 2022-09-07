<?php
namespace App\RestApi;

use App\Dto\Response\Transformer\IssueResponseDtoTransformer;
use App\Dto\Response\Transformer\ItemResponseDtoTransformer;

class GitHubApi {

    private static $baseUrl = "https://api.github.com/search/issues?";
    private static $contentType = "application/vnd.github+json";
    private static $methodTypeGet = "GET";
    private static $userAgent = "undabot";


    public function restCallGet(string $term = null, string $type = null, string $order = null, int $perPage = null, int $page = null){
        return $this->restCall($term, $type, $order, $perPage, $page, GitHubApi::$methodTypeGet);
    }


    private function restCall(string $term = null, string$type = null, string $order = null, int $perPage = null, int $page = null, string $method = null){
        $curl = curl_init();
        $serviceUrl = GitHubApi::$baseUrl;
        $header = $this->getHeader();

        if($term != null){
            $serviceUrl .= "q=" . $term;
        }

        if($type != null){
            $serviceUrl .= "+type:" . $type;
        }

        if($order != null){
            $serviceUrl .= "&order=" . $order;
        }

        if($perPage != null){
            $serviceUrl .= "&per_page=" . $perPage;
        }

        if($page != null){
            $serviceUrl .= "&page=" . $page;
        }

        curl_setopt($curl, CURLOPT_URL, $serviceUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method );

        $curlResponse = curl_exec($curl);
        if ($curlResponse === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($curl);
        return $curlResponse;
    }

    private function getHeader(){
        $header = array(
            'Content-Type: ' . GitHubApi::$contentType,
            'User-Agent: ' . GitHubApi::$userAgent
        );
        return $header;
    }
}