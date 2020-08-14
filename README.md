# Introduction
[![MIT licensed](https://img.shields.io/badge/license-MIT-blue.svg)](./LICENSE) 
[![Build Status](https://travis-ci.com/famoser/wall.svg?branch=master)](https://travis-ci.com/famoser/wall)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/famoser/wall/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/famoser/wall/?branch=master)

## About
Wall helps flat mates to coordinate.

It provides the following functionality:
 - shopping list (we need apples & tomatoes!)
 - questions (who's up for dinner?)
 - tasks (the kitchen should be cleaned again...)
 - events (are friends coming over in the next few days?)
 - embeds (want to share a funny image / video?)

<img src="assets/images/screenshot.jpg?raw=true" alt="Screenshot">

## Release

`famoser/agnes` is set up to release new versions & deploy them.

- `./vendor/bin/agnes release v.1.0.0 master` to release to master (and deploy to dev)
- `./vendor/bin/agnes deploy *:*:prod master` to deploy to production