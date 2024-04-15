composer install --no-dev
plugin_slug=adapter-gravity-add-on
build_version=$(grep 'Version:' $plugin_slug.php | cut -f4 -d' ')
zip_file_name=$plugin_slug.$build_version.zip
cd ..; sed "s/^[^*]/*&/g" "$plugin_slug/.distignore" | sed "s/[^*]$/&*/g" | xargs zip -r "$zip_file_name" $plugin_slug -x
mv "$zip_file_name" "$plugin_slug"
