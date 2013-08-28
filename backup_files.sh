#!/bin/bash

DIR=/root/scripts
FLAG_FILE=/data/files/RSYNC_IN_PROGRESS

# ---- the server listed below is the server that you are running rsync against (change as necessary) ----

# only run the rsync if the checksums match (as per checksum_check.php)
if [ -f "$FLAG_FILE" ] ; then
  /usr/bin/rsync --archive --compress --delete --verbose /data/ root@pod1.library.kent.edu:/data/ > $DIR/backup 2>>$DIR/rsync_errors
  /bin/rm $FLAG_FILE
fi
