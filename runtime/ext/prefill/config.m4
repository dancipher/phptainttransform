dnl $Id$

PHP_ARG_ENABLE(prefill, whether to enable prefill support,
[  --enable-prefiill           Enable prefill support])

if test "$PHP_PREFILL" != "no"; then
  PHP_NEW_EXTENSION(prefill, prefill.c, $ext_shared)
fi
