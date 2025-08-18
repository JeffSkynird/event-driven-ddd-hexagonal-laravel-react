#!/bin/bash
php artisan inventory-ready:listen &
php artisan inventory-response:listen
wait
