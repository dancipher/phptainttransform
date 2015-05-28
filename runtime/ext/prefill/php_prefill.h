/*
  +----------------------------------------------------------------------+
  | PHP Version 5                                                        |
  +----------------------------------------------------------------------+
  | Copyright (c) 1997-2012 Daniel Zulla		                         |
  | Copyright (c) 2012-2013 Defense I/O LLC								 |
  +----------------------------------------------------------------------+
  | Author:  Daniel Zulla   <dan@zulla.org>								 |
  +----------------------------------------------------------------------+
*/

/* $Id$ */

#ifndef PHP_PREFILL_H
#define PHP_PREFILL_H

extern zend_module_entry prefill_module_entry;
#define phpext_prefill_ptr &prefill_module_entry

#ifdef PHP_WIN32
#define PHP_PREFILL_API __declspec(dllexport)
#else
#define PHP_PREFILL_API
#endif

#ifdef ZTS
#include "TSRM.h"
#endif

#define PHP_PREFILL_VERSION "1.0.0"

PHP_MINIT_FUNCTION(prefill);
PHP_MSHUTDOWN_FUNCTION(prefill);
PHP_RINIT_FUNCTION(prefill);
PHP_RSHUTDOWN_FUNCTION(prefill);
PHP_MINFO_FUNCTION(prefill);

typedef void (*php_func)(INTERNAL_FUNCTION_PARAMETERS);

ZEND_BEGIN_MODULE_GLOBALS(prefill)
    char*     get;
    char*     post;
    char*     files;
    char*     cookie;
    char*     preload;
	int       error_level;
ZEND_END_MODULE_GLOBALS(prefill)

#ifdef ZTS
#define PREFILL_G(v) TSRMG(prefill_globals_id, zend_prefill_globals *, v)
#else
#define PREFILL_G(v) (prefill_globals.v)
#endif

#endif	/* PHP_PREFILL_H */


/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
