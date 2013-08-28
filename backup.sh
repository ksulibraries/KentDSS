#!/bin/bash

DIR=/root/scripts
BACKUP_FILE=$DIR/backup

$DIR/checksum_check.php

if [ -f "$BACKUP_FILE" ] ; then
  /bin/rm -f $BACKUP_FILE
fi

$DIR/backup_files.sh
