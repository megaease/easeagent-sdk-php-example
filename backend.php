<?php

use Easeagent\AgentBuilder;
use Zipkin\Propagation\Map;

require_once __DIR__ . '/vendor/autoload.php';

$agent = AgentBuilder::buildFromYaml("./configs/agent_backend.yml");
$agent->serverTransaction(function ($span) use ($agent) {
    $childSpan = $agent->startClientSpan($span, 'user:get_list:mysql_query');
    usleep(50000);
    $childSpan->finish();
    echo "<p> --------------------- backend end --------------------------- </p>";
});
