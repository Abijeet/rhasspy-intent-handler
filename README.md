# Saathi

> (_साथी_ - Hindi): Sidekick

![Unit tests](https://github.com/Abijeet/saathi/actions/workflows/phpunit.yaml/badge.svg)
![Static analysis](https://github.com/Abijeet/saathi/actions/workflows/phan.yaml/badge.svg)
![Coding standard](https://github.com/Abijeet/saathi/actions/workflows/phpcs.yaml/badge.svg)

An HTTP intent handler for [Rhasspy](https://rhasspy.readthedocs.io/en/latest/) built to emulate a personal assistant.

- [What can it do?](#what-can-it-do)
- [How does it work?](#how-does-it-work)
  - [For intents using custom words](#for-intents-using-custom-words)
  - [General intents](#general-intents)
- [Development environment](#development-environment)
  - [Development setup](#development-setup)
  - [Other useful commands](#other-useful-commands)
- [License](#license)

## What can it do?

- [x] Query Wikipedia for certain queries. Example:
   * Who is Barack Obama?
   * Tell me about Delhi
- [x] Send responses to a Telegram channel
- [ ] How is the weather in `{my city}`
- [ ] What is the time in Delhi?
- [ ] Schedule events. Example:
   * Remind me to water the plants at 7:00 PM today
- [ ] Send me directions to `{location}`

## How does it work?

Ensure that you understand what [Rhasspy can do](https://www.youtube.com/watch?v=ijKTR_GqWwA).

### For intents using custom words

Intents in Rhasspy are defined in `sentences.ini` file as such,

```
set the light to (red | green | blue)
```
So the above will match *set the light to red / green / blue*, but it cannot recognize *set the light to yellow*, since yellow is not added to the alternatives list.

Basically Rhasspy [does not support custom / untrained words in sentences](https://community.rhasspy.org/t/recognized-untrain-sentences-words/465/7).

To circumvent this, we define the sentence simply as `set the light to`, and then trigger voice recording via our HTTP API that acts as the intent handler.

Various speech to text services are used to convert the audio to text, and then use that output to determine what to do.

### General intents

These are processed similar to any other intents. You can read more about this [here](https://rhasspy.readthedocs.io/en/latest/intent-handling/#remote-server).

## Development environment

Run `docker-compose up` to start a docker that will be the development environment for the Rhasspy Intent Handler API.

The API uses [Lumen](https://lumen.laravel.com/).

Go through the [`README.md` file under the `rhasspy`](./rhasspy/README.md) folder to setup get Rhasspy running in another docker.

### Development setup
```sh
# Login to the development docker
docker exec -it saathi-api-1 /bin/bash

# Install stuff
composer install

# Run code sniffer
cd /var/www/html
composer lint
## Fix automatic fixable issues
composer lint:fix

# Run static analysis
cd /var/www/html
composer phan

# Run test cases
./vendor/bin/phpunit
```

### Other useful commands
```sh
# Run commands as apache user after logging into development docker
su -l www-data -s /bin/bash
```
## License

This software is open-sourced and licensed under the [MIT license](https://opensource.org/licenses/MIT).

