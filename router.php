<?php
require __DIR__ . '/vendor/autoload.php';

use Easeagent\AgentBuilder;
use Zipkin\Endpoint;

$agent = AgentBuilder::buildFromYaml(getenv('EASEAGENT_SDK_CONFIG_FILE'));

$agent->serverTransaction(function ($span) use ($agent) {
    echo "<p> is noop: " . ($span->isNoop() == true ? "true" : "false") . "</p>";
    echo "<p> trace id: " . ($span->getContext()->getTraceId()) . "</p>";
    $childSpan = $agent->startClientSpan($span,  'user:get_list:mysql_query');
    $childSpan->setRemoteEndpoint(Endpoint::create("", "0.0.0.0", null, 8081));
    usleep(50000);
    $childSpan->finish();
    if (preg_match('/\.(?:png|jpg|jpeg|gif)$/', $_SERVER["REQUEST_URI"])) {
        return false;    // serve the requested resource as-is.
    } else {
        echo "<p>Welcome to PHP</p>";
    }
});
