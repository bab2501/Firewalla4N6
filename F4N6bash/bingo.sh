#!/bin/bash

# Firewalla data acquisition script.

echo "#### Firewalla data acquisition script #####"
read -p 'Case name: ' casenumber
read -p 'Evidence number: ' evidencenumber
read -p 'Examiner: ' examiner
read -p 'Description: ' description
read -p 'notes: ' notes

# Create case directroy
path=`pwd`
mkdir $casenumber -p $path
casedir=$path"/"$casenumber



echo "#####################################"
echo "Establish connection with device on: " `date`
ID=`date +%F`_`date +%T`
nc -lup 7654 | dd of=/tmp/image-$ID.raw&
NCPROCESS=`expr $! - 1`
echo "root" | picocom -b 115200 /dev/ttyUSB0
sleep 5
echo "/n" | picocom -b 115200 /dev/ttyUSB0
echo "Start data copy acquisition on device: " `date`
echo "dd if=/dev/mmcblk0 | nc -u desktop-30.students.os3.nl 7654" | picocom -b 115200 /dev/ttyUSB0

sleep 5

sizeA=`du /tmp/image-$ID.raw | cut -d "/" -f 1`
sleep 5 
sizeB=`du /tmp/image-$ID.raw | cut -d "/" -f 1`
while [ "$sizeA" != "$sizeB" ]
do
 sizeA=`du /tmp/image-$ID.raw | cut -d "/" -f 1`
 sleep 2
 sizeB=`du /tmp/image-$ID.raw | cut -d "/" -f 1`
done

echo "Finish copy data acquisition on device: " `date`
hashold=`md5sum /tmp/image-$ID.raw | cut -d " " -f 1`



echo "Start data  acquisition on data: " `date`


## FTK imager
wget https://ad-zip.s3.amazonaws.com/ftkimager.3.1.1_ubuntu64.tar.gz
tar -xzvf ftkimager.3.1.1_ubuntu64.tar.gz
./ftkimager  /tmp/image-$ID.raw $casedir --e01 --frag 1500MB  --compress 2 --case-number $casenumber --evidence-number $evidencenumber --description $description --examiner $examiner --notes $notes

echo "Finish data  acquisition on data: " `date`

hashnew=`cat $casedir/*.txt | grep "MD5 checksum:" | awk 'FS="/t" {print $3} '`

echo "hash dd: $hashold"
echo "\n"
echo "hash dd: $hashnew"