#!/bin/bash
php artisan inventory:listen &
php artisan inventory-purchased:listen
wait
