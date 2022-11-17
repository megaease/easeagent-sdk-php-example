<?php

use Easeagent\AgentBuilder;
use Easeagent\Middleware\Type;

require_once __DIR__ . '/vendor/autoload.php';

$agent = AgentBuilder::buildFromYaml("./configs/agent_backend.yml");
$agent->serverTransaction(function ($span) use ($agent) {
    $childSpan = $agent->startMiddlewareSpan($span, 'mysql-mysql_query', Type::MySql);
    $childSpan->setRemoteEndpoint(\Zipkin\Endpoint::create("mysql"));
    usleep(50000);
    $childSpan->finish();
    echo "<p> --------------------- backend end --------------------------- </p>";
});
