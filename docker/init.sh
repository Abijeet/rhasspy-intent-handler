chown www-data:www-data -R /var/www/html/storage
chmod 755 -R /var/www/html/storage

composer install

# Change ownership of sound devices to group audio
# By default it appears to be owned by only root
chgrp -R audio /dev/snd/*
