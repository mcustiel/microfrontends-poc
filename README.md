# Micro-frontend Examples

## Running
1. `docker-compose up -d`
2. Go to http://localhost

The idea is parts such as header/footer/article/match page/news list etc. are all served by microservices/micro frontends.

Each microservice will get every request, but can simply return a 404 to indicate it doesn't
support/care about the page. Further-more microservice responses are cached in the app, so 
the service can return a 404 with a long cache time, meaning very minimal performance loss.

