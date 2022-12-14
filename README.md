# Easeagent SDK PHP example
This is an example app where three php services use [easeagent-sdk-php](https://github.com/megaease/easeagent-sdk-php).

## Requirements
- [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
- [Docker](https://docs.docker.com/engine/installation/) (optional, if you use Megaease Cloud this is not needed)

## Running the example

This example has two services: frontend and backend. They both report trace data to zipkin.

To setup the demo, do

```bash
composer install
```

Once the dependencies are installed, run the services:

```bash
# Run zipkin (optional):
composer run-zipkin

# In terminal 1:
composer run-frontend

# In terminal 2
composer run-backend

```

And then, request the frontend:
 
```
curl http://localhost:8081
```

1. This starts a trace in the frontend (http://localhost:8081/)
2. Continues the trace and calls the backend (http://localhost:9000)
3. Next, you can view traces that went through the backend via http://localhost:9411/?serviceName=frontend.


## Running example with a MegaEase Cloud location:

If you need to pass the MegaEase Cloud endpoint, please download the `agent.yaml` from [MegaEase Cloud](https://cloud.megaease.com/) `[stack]->[document]->[PHP]`.

`MEGAEASE_CLOUD_URL` and `TLS` will be filled in for you automatically.

Replace `agent.yml` with the configuration file in the `configs` directory. Then run it.

## Use local SDK

if you have your self `easeagent-sdk-php` and want to use, please add `repositories` to [composer.json](./composer.json) like this:

```json
"repositories": {
    "0": {
        "type": "path",
        "url": "../easeagent-sdk-php",
        "options": {
            "symlink": true
        }
    }
},
```
