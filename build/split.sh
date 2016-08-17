git subsplit init git@github.com:slince/slince.git
git subsplit publish --heads="master" src/Application:git@github.com:slince/application.git
git subsplit publish --heads="master" src/Cache:git@github.com:slince/cache.git
git subsplit publish --heads="master" src/CakeBridge:git@github.com:slince/cake-bridge.git
git subsplit publish --heads="master" src/Config:git@github.com:slince/config.git
git subsplit publish --heads="master" src/Di:git@github.com:slince/di.git
git subsplit publish --heads="master" src/Event:git@github.com:slince/event.git
git subsplit publish --heads="master" src/MonologBridge:git@github.com:slince/monolog-bridge.git
git subsplit publish --heads="master" src/Routing:git@github.com:slince/routing.git
git subsplit publish --heads="master" src/View:git@github.com:slince/view.git
git subsplit publish --heads="master" src/WhoopsBridge:git@github.com:slince/whoops-bridge.git
rm -rf .subsplit/