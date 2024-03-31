#!/bin/bash

# Change directory to your project directory
cd /var/www/devilbox/data/www/scheduler

# Start the Docker containers in detached mode
vendor/bin/sail up -d

# Run the npm development script using Laravel Sail
vendor/bin/sail npm run dev