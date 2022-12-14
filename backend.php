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
use Easeagent\Middleware\Type;

require_once __DIR__ . '/vendor/autoload.php';

$agent = AgentBuilder::buildFromYaml(getenv('EASEAGENT_CONFIG'));
$agent->serverReceive(function ($span) use ($agent) {
    $childSpan = $agent->startMiddlewareSpan($span, 'mysql-mysql_query', Type::MySql);
    $childSpan->setRemoteEndpoint(\Zipkin\Endpoint::create("mysql"));
    usleep(50000);
    $childSpan->finish();
    echo "<p> --------------------- backend end --------------------------- </p>";
});
