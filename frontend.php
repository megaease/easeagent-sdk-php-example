<?php
/**
 * Copyright 2022 MegaEase
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


use Easeagent\AgentBuilder;
use Easeagent\HTTP\HttpUtils;
use Zipkin\Timestamp;
use GuzzleHttp\Client;

require_once __DIR__ . '/vendor/autoload.php';

$agent = AgentBuilder::buildFromYaml(getenv('EASEAGENT_CONFIG'));
$agent->serverReceive(function ($span) use ($agent) {
    usleep(100 * mt_rand(1, 3));
    print_r($_SERVER);

    /* Creates the span for getting the users list */
    $childSpan = $agent->startClientSpan($span, "");
    /* Injects the context into the wire */
    $headers = $agent->injectorHeaders($childSpan);
    /* HTTP Request to the backend */
    $httpClient = new Client();
    $request = new \GuzzleHttp\Psr7\Request('POST', 'localhost:9000', $headers);
    $childSpan->annotate('request_started', Timestamp\now());
    $response = $httpClient->send($request);
    echo $response->getBody();
    $childSpan->annotate('request_finished', Timestamp\now());
    /* Save Request info */
    HttpUtils::finishSpan($childSpan, $request->getMethod(), $request->getUri()->getPath(), $response->getStatusCode());
});



/* Sends the trace to zipkin once the response is served */
