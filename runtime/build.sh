sudo make clean
rm configure
rm Makefile
./buildconf --force
./configure CFLAGS="-O3" --with-config-file-path=/opt/php --with-config-file-scan-dir=/opt/php --disable-phar --enable-sockets --enable-zip --without-pear --enable-fpm --enable-taint --enable-prefill --with-mysql --with-mysqli --prefix=/usr
make -j 8
sudo make install
