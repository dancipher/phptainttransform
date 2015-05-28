sudo rm configure
sudo rm Makefile
sudo phpize --clean
sudo phpize .
sudo ./configure --enable-prefill
sudo make -j 4
sudo make install
