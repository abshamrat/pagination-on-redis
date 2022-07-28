## For the first time (fresh installation)
- ./vendor/bin/sail up
- ./vendor/bin/sail artisan migrate
- nvm use && npm i && npm run dev

## Insert Seed data
- ./vendor/bin/sail artisan db:seed --class=PersonSeeder

## Query builder
- https://stackoverflow.com/questions/24555025/how-to-customize-laravels-database-query-builder-make-better-subquery

## Redis pagination
- https://redis.io/docs/reference/patterns/twitter-clone/#paginating-updates
- https://redis.com/redis-best-practices/time-series/sorted-set-time-series/
- https://hotexamples.com/site/file?hash=0xf85e82e94424a923bca9633adfb59707f0fc24cf78cd5cf88ba2aa68f5ddcf5a&fullName=src/TaskQueue/Queue/Redis/RedisQueue.php&project=rybakit/taskqueue