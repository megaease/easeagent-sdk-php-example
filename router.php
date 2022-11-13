<?php
require __DIR__ . '/vendor/autoload.php';

use Easeagent\AgentBuilder;

$agent = AgentBuilder::buildFromYaml("./configs/agent_router.yml");
$agent->serverTransaction(function ($span) use ($agent){
    $childSpan = $agent->startClientSpan($span, 'user:get_list:mysql_query');
    usleep(50000);
    $childSpan->finish();
    if (preg_match('/\.(?:png|jpg|jpeg|gif)$/', $_SERVER["REQUEST_URI"])) {
        return false;    // serve the requested resource as-is.
    } else {
        echo "<p>Welcome to PHP</p>";
    }

});
