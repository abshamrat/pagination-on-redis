### Prerequisite
- [Docker](https://docs.docker.com/engine/install/ubuntu/)
- [NVM](https://github.com/nvm-sh/nvm) or [Node.js 16](https://nodejs.org/en/download/)

### Get Started (locally)
- Clone and go to the project root folder.
- Run `./vendor/bin/sail up`
- After service up an running, execute `./vendor/bin/sail artisan migrate`
- Add seed data by running `./vendor/bin/sail artisan db:seed --class=PersonSeeder`
- On another terminal run `nvm use && npm i && npm run dev`
- Open http://localhost and play around.

### Demo video
https://user-images.githubusercontent.com/12497649/181757312-0b179182-4054-48af-8d55-0fad54c25a30.mp4

