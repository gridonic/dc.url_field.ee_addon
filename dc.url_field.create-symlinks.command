#!/bin/bash

# This script creates symlinks from the local repo into your EE install.
# Inspired by and thanks to Leevi Graham http://leevigraham.com/ and Newism.

dirname="$PWD"

echo "Enter the path to your ExpressionEngine Install without a trailing slash [ENTER]:"
read ee_path

echo "Enter your system folder name [ENTER]:"
read ee_system_folder

ln -sf "$dirname"/system/extensions/ext.dc_url_field.php "$ee_path"/"$ee_system_folder"/extensions/ext.dc_url_field.php
ln -sf "$dirname"/system/language/english/lang.dc_url_field.php "$ee_path"/"$ee_system_folder"/language/english/lang.dc_url_field.php
ln -sf "$dirname"/themes/cp_global_images/link_go.png "$ee_path"/"$ee_system_folder"/../themes/cp_global_images/link_go.png