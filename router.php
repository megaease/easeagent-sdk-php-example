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

require __DIR__ . '/vendor/autoload.php';

use Easeagent\AgentBuilder;
use Zipkin\Endpoint;

$agent = AgentBuilder::buildFromYaml(getenv('EASEAGENT_CONFIG'));

$agent->serverReceive(function ($span) use ($agent) {
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
