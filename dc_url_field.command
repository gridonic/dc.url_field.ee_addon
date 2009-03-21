#!/bin/bash

# This script creates symlinks from the local repo into your EE install.

dirname="$PWD"

echo "Enter the path to your ExpressionEngine Install without a trailing slash [ENTER]:"
read ee_path
echo "Enter your system folder name [ENTER]:"
read ee_system_folder
 
ln -sf "$dirname"/extensions/ext.dc_url_field.php "$ee_path"/"$ee_system_folder"/extensions/ext.dc_url_field.php
ln -sf "$dirname"/language/english/lang.dc_url_field.php "$ee_path"/"$ee_system_folder"/language/english/lang.dc_url_field.php