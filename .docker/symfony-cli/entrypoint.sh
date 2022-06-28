#!/bin/bash
source /root/.bashrc

nvm install $NODEJS_VERSION

exec "$@"
