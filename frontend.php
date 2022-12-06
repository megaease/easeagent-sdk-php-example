<?php

use Easeagent\AgentBuilder;
use Easeagent\HTTP\HttpUtils;
use Zipkin\Timestamp;
use GuzzleHttp\Client;

require_once __DIR__ . '/vendor/autoload.php';

$agent = AgentBuilder::buildFromYaml(getenv('EASEAGENT_SDK_CONFIG_FILE'));
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
