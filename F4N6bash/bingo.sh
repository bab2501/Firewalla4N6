nc -lup 7654 | dd of=/tmp/image20200305_$(date +%s).raw&
sudo echo "dd if=/dev/mmcblk0 | nc -u desktop-30.students.os3.nl 7654" | picocom -b 115200 -qrix 1000 /dev/ttyUSB0 >> output.txt
watch -n 1 ls -la /tmp
