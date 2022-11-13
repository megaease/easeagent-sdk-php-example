<?php

use Easeagent\AgentBuilder;
use Zipkin\Timestamp;
use GuzzleHttp\Client;

require_once __DIR__ . '/vendor/autoload.php';

$agent = AgentBuilder::buildFromYaml("./configs/agent_frontend.yml");
$agent->serverTransaction(function ($span) use ($agent) {
    usleep(100 * mt_rand(1, 3));
    print_r($_SERVER);

    /* Creates the span for getting the users list */
    $childSpan = $agent->startClientSpan($span, 'users:get_list');

    /* Injects the context into the wire */
    $headers = $agent->injectorHeaders($childSpan);
    /* HTTP Request to the backend */
    $httpClient = new Client();
    $request = new \GuzzleHttp\Psr7\Request('POST', 'localhost:9000', $headers);
    $childSpan->annotate('request_started', Timestamp\now());
    $response = $httpClient->send($request);
    echo $response->getBody();
    $childSpan->annotate('request_finished', Timestamp\now());

    $childSpan->finish();
});



/* Sends the trace to zipkin once the response is served */
