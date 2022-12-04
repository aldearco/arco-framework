<?php 

namespace Arco\Server;

use Arco\Http\HttpMethod;
use Arco\Http\Response;

interface Server {
    public function requestUri(): string;
    public function requestMethod(): HttpMethod;
    public function postData(): array;
    public function queryParams(): array;
    public function sendResponse(Response $response);
}