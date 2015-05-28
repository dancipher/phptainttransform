/*
  +----------------------------------------------------------------------+
  | PHP Version 5                                                        |
  +----------------------------------------------------------------------+
  | Copyright (c) 2012 Daniel Zulla				                         |
  | Copyright (c) 2012-2013 Defense I/O LLC								 |
  +----------------------------------------------------------------------+
  | Author:  Daniel Zulla   <dan@zulla.org>                              |
  +----------------------------------------------------------------------+
*/

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "SAPI.h"
#include "zend_compile.h"
#include "zend_execute.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include "ext/taint/php_taint.h"
#include "php_prefill.h"

ZEND_DECLARE_MODULE_GLOBALS(prefill)

zend_module_entry prefill_module_entry = {
#if ZEND_MODULE_API_NO >= 20050922
	STANDARD_MODULE_HEADER_EX, NULL,
	NULL,
#else
	STANDARD_MODULE_HEADER,
#endif
	"prefill",
	NULL,
	PHP_MINIT(prefill),
	PHP_MSHUTDOWN(prefill),
	PHP_RINIT(prefill),
	PHP_RSHUTDOWN(prefill),
	PHP_MINFO(prefill),
#if ZEND_MODULE_API_NO >= 20010901
	PHP_PREFILL_VERSION,
#endif
	PHP_MODULE_GLOBALS(prefill),
	NULL,
	NULL,
	NULL,
	STANDARD_MODULE_PROPERTIES_EX
};
/* }}} */


#ifdef COMPILE_DL_PREFILL
ZEND_GET_MODULE(prefill)
#endif
	
PHP_INI_BEGIN()
    STD_PHP_INI_ENTRY("prefill.preload", "foobar_1", PHP_INI_SYSTEM, OnUpdateString, preload, zend_prefill_globals, prefill_globals)
    STD_PHP_INI_ENTRY("prefill.get", "", PHP_INI_SYSTEM, OnUpdateString, get, zend_prefill_globals, prefill_globals)
    STD_PHP_INI_ENTRY("prefill.post", "", PHP_INI_SYSTEM, OnUpdateString, post, zend_prefill_globals, prefill_globals)
    STD_PHP_INI_ENTRY("prefill.files", "", PHP_INI_SYSTEM, OnUpdateString, files, zend_prefill_globals, prefill_globals)
    STD_PHP_INI_ENTRY("prefill.cookie", "", PHP_INI_SYSTEM, OnUpdateString, cookie, zend_prefill_globals, prefill_globals)
PHP_INI_END()

static void php_taint_mark_strings(zval *symbol_table TSRMLS_DC) /* {{{ */ {
    zval **ppzval;
    HashTable *ht = Z_ARRVAL_P(symbol_table);
    HashPosition pos = {0};

    for(zend_hash_internal_pointer_reset_ex(ht, &pos);
            zend_hash_has_more_elements_ex(ht, &pos) == SUCCESS;
            zend_hash_move_forward_ex(ht, &pos)) {
        if (zend_hash_get_current_data_ex(ht, (void**)&ppzval, &pos) == FAILURE) {
            continue;
        }
        if (Z_TYPE_PP(ppzval) == IS_ARRAY) {
            php_taint_mark_strings(*ppzval TSRMLS_CC);
        } else if (IS_STRING == Z_TYPE_PP(ppzval)) {
            Z_STRVAL_PP(ppzval) = erealloc(Z_STRVAL_PP(ppzval), Z_STRLEN_PP(ppzval) + 1 + PHP_TAINT_MAGIC_LENGTH);
            PHP_TAINT_MARK(*ppzval, PHP_TAINT_MAGIC_POSSIBLE);
        }
    }
} /* }}} */


PHP_MINIT_FUNCTION(prefill) {
	REGISTER_INI_ENTRIES();
	return SUCCESS;
}

PHP_MSHUTDOWN_FUNCTION(prefill) {
	UNREGISTER_INI_ENTRIES();
	return SUCCESS;
}

PHP_RINIT_FUNCTION(prefill) {
	char *ptr;
    
    ptr = strtok(PREFILL_G(post), ",");
    while (ptr != NULL) {
        php_register_variable(ptr, PREFILL_G(preload), PG(http_globals)[TRACK_VARS_POST] TSRMLS_CC);
		ptr = strtok(NULL, ",");
    }
	php_taint_mark_strings(PG(http_globals)[TRACK_VARS_POST] TSRMLS_CC);

    ptr = strtok(PREFILL_G(get), ",");
    while (ptr != NULL) {
        php_register_variable(ptr, PREFILL_G(preload), PG(http_globals)[TRACK_VARS_GET] TSRMLS_CC);
        ptr = strtok(NULL, ",");
    }
	php_taint_mark_strings(PG(http_globals)[TRACK_VARS_GET] TSRMLS_CC);

    ptr = strtok(PREFILL_G(cookie), ",");
    while (ptr != NULL) {
        php_register_variable(ptr, PREFILL_G(preload), PG(http_globals)[TRACK_VARS_COOKIE] TSRMLS_CC);
        ptr = strtok(NULL, ",");
    }
	php_taint_mark_strings(PG(http_globals)[TRACK_VARS_COOKIE] TSRMLS_CC);

    ptr = strtok(PREFILL_G(files), ",");
    while (ptr != NULL) {
        php_register_variable(ptr, PREFILL_G(preload), PG(http_globals)[TRACK_VARS_FILES] TSRMLS_CC);
        ptr = strtok(NULL, ",");
    }
	php_taint_mark_strings(PG(http_globals)[TRACK_VARS_FILES] TSRMLS_CC);

	return SUCCESS;
}

PHP_RSHUTDOWN_FUNCTION(prefill) {
	return SUCCESS;
}

PHP_MINFO_FUNCTION(prefill) {
	php_info_print_table_start();
	php_info_print_table_header(2, "prefill support", "enabled");
	php_info_print_table_row(2, "Version", PHP_PREFILL_VERSION);
	php_info_print_table_end();

	DISPLAY_INI_ENTRIES();
}


/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
