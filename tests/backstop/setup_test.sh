#!/bin/bash


read -p "Enter the default test site [http://psupmoodle.local/]" siteurl
SITEURL=${siteurl:-"http://psupmoodle.local"}

sed "s|<TESTSITEURL>|$SITEURL|g" backstop.template.json > backstop.json