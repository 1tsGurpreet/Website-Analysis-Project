/* Include the required headers from httpd */
#include "httpd.h"
#include "http_core.h"
#include "http_protocol.h"
#include "http_request.h"
#include "apr_strings.h"
#include "util_cookies.h"
#include "util_script.h"
#include <stdlib.h>
#include <string.h>
#include <time.h>

typedef struct {
    const char *key;
    const char *value;
} keyValuePair;

static void register_hooks(apr_pool_t *pool);
static int example_handler(request_rec *r);
static void html_world(request_rec *r);
static void json_world(request_rec *r);
static void env(request_rec *r);
static void get_echo(request_rec *r);
static void post_echo(request_rec *r);
static void general_echo(request_rec *r);
static void session_1(request_rec *r);
static void session_2(request_rec *r);
static void destroy_session(request_rec *r);
keyValuePair *readPost(request_rec *r); 

module AP_MODULE_DECLARE_DATA   bonus_module =
{
    STANDARD20_MODULE_STUFF,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    register_hooks   /* Our hook registering function */
};

static void register_hooks(apr_pool_t *pool)
{
    /* Create a hook in the request handler, so we get called when a request arrives */
    ap_hook_handler(example_handler, NULL, NULL, APR_HOOK_LAST);
}

static int example_handler(request_rec *r)
{
    /* First off, we need to check if this is a call for the "example-handler" handler.
     * If it is, we accept it and do our things, if not, we simply return DECLINED,
     * and the server will try somewhere else.
     */
    if (!r->handler || strcmp(r->handler, "bonus-handler")) return (DECLINED);
    
    /* Now that we are handling this request, we'll write out "Hello, world!" to the client.
     * To do so, we must first set the appropriate content type, followed by our output.*/
     
    //ap_set_content_type(r, "text/html");
    //ap_rprintf(r, "Hello, %s", r->filename);

    if (strstr(r->filename, "html-world.apache") != NULL)
        html_world(r);
    else if (strstr(r->filename, "json-world.apache") != NULL)
        json_world(r);
    else if (strstr(r->filename, "env.apache") != NULL)
        env(r);
    else if (strstr(r->filename, "get-echo.apache") != NULL)
        get_echo(r);
    else if (strstr(r->filename, "post-echo.apache") != NULL)
        post_echo(r);
    else if (strstr(r->filename, "general-echo.apache") != NULL)
        general_echo(r);
    else if (strstr(r->filename, "session-1.apache") != NULL)
        session_1(r);
    else if (strstr(r->filename, "session-2.apache") != NULL)
        session_2(r);
    else if (strstr(r->filename, "destroy-session.apache") != NULL)
        destroy_session(r);
    else 
        return HTTP_NOT_FOUND;
    
    /* Lastly, we must tell the server that we took care of this request and everything went fine.
     * We do so by simply returning the value OK to the server.
     */
    return OK;
}

keyValuePair *readPost(request_rec *r) {
    apr_array_header_t *pairs = NULL;
    apr_off_t len;
    apr_size_t size;
    int res;
    int i = 0;
    char *buffer;
    keyValuePair *kvp;

    res = ap_parse_form_data(r, NULL, &pairs, -1, HUGE_STRING_LEN);
    if (res != OK || !pairs) return NULL; /* Return NULL if we failed or if there are is no POST data */
    kvp = apr_pcalloc(r->pool, sizeof(keyValuePair) * (pairs->nelts + 1));
    while (pairs && !apr_is_empty_array(pairs)) {
        ap_form_pair_t *pair = (ap_form_pair_t *) apr_array_pop(pairs);
        apr_brigade_length(pair->value, 1, &len);
        size = (apr_size_t) len;
        buffer = apr_palloc(r->pool, size + 1);
        apr_brigade_flatten(pair->value, buffer, &size);
        buffer[len] = 0;
        kvp[i].key = apr_pstrdup(r->pool, pair->name);
        kvp[i].value = buffer;
        i++;
    }
    return kvp;
}

static void html_world(request_rec *r){
    time_t t;
    time(&t);
    // Print HTML header
    ap_set_content_type(r, "text/html");

    ap_rprintf(r,"<html><head><title>Hello CGI World</title></head>\
       <body><h1 align=center>Hello HTML World</h1>\
       <hr/>\n");
   
    ap_rprintf(r, "Hello World<br/>\n");
    ap_rprintf(r, "This program was generated at: %s\n<br/>", ctime(&t));
    ap_rprintf(r, "Your current IP address is: %s<br/>", r->useragent_ip);
   
    // Print HTML footer
    ap_rprintf(r, "</body></html>");
    return;
}

static void json_world(request_rec *r){
    time_t t;
    time(&t);
    char *buffer = ctime(&t);
    buffer[strlen(buffer) - 1] = '\0';

    ap_set_content_type(r, "application/json");
    ap_rprintf(r, "{\n\t\"message\": \"Hello World\",\n");
    ap_rprintf(r, "\t\"date\": \"%s\",\n", buffer);
    ap_rprintf(r, "\t\"currentIP\": \"%s\"\n}\n", r->useragent_ip);
    return;
}

static void env(request_rec *r){
    ap_set_content_type(r, "text/html");
    ap_rprintf(r, "<html><head><title>Environment Variables</title></head> \
        <body><h1 align=center>Environment Variables</h1> \
        <hr/>\n");
    ap_rprintf(r, "client_ip: %s <br/>\n", r->connection->client_ip);
    ap_rprintf(r, "remote_host: %s <br/>\n", r->connection->remote_host);
    ap_rprintf(r, "remote_logname: %s <br/>\n", r->connection->remote_logname);
    ap_rprintf(r, "local_ip: %s <br/>\n", r->connection->local_ip);
    ap_rprintf(r, "local_host: %s <br/>\n", r->connection->local_host);
    ap_rprintf(r, "id: %ld <br/>\n", r->connection->id);
    ap_rprintf(r, "</body></html>");
    return;
}

int callback(void *rec, const char *key, const char *value){
    request_rec *r = rec;
    ap_rprintf(r, "%s: %s<br/>", key, value);
    return key != NULL;
}


static void get_echo(request_rec *r){
    apr_table_do_callback_fn_t *comp = &callback;
    apr_table_t*GET; 
    ap_args_to_table(r, &GET); 

    ap_set_content_type(r, "text/html");
    ap_rprintf(r, "<html><head><title>GET query string</title></head>\
      <body><h1 align=center>GET query string</h1>\
      <hr/>\n");
    
    // Get and format query string
    ap_rprintf(r, "Raw query string: %s\n<br/><br/>", r->args);
    ap_rprintf(r, "Formatted Query String:<br/>");
    apr_table_do(comp, r, GET, NULL);
    

    /*char *query = strdup(r->args);
    char *tokens = query;
    char *p = query;
    /*while ((p = strsep (&tokens, "&\n"))) {
          char *var = strtok (p, "="),
               *val = NULL;
          if (var && (val = strtok (NULL, "=")))
              ap_rprintf (r, "<tr><td>%-8s:</td><td>%s</td></tr>\n", var, val);
          else
              fputs ("<empty field>\n", stderr);
      }
      free (query);*/
    
    // Print HTML footer
    ap_rprintf(r, "</body>");
    ap_rprintf(r, "</html>");
    return;
}

static void post_echo(request_rec *r){
    ap_set_content_type(r, "text/html");
    // Print HTML header
    ap_rprintf(r, "<html><head><title>POST Message Body</title></head>\
      <body><h1 align=center>POST Message Body</h1>\
      <hr/>\n");

    /*~~~~~~~~~~~~~~~~~~~~~~*/
    keyValuePair *formData;
    /*~~~~~~~~~~~~~~~~~~~~~~*/

    formData = readPost(r);
    if (formData) {
        int i;
        for (i = 0; &formData[i]; i++) {
            if (formData[i].key && formData[i].value) {
                ap_rprintf(r, "%s = %s <br>\n", formData[i].key, formData[i].value);
            } else if (formData[i].key) {
                ap_rprintf(r, "%s<br>\n", formData[i].key);
            } else if (formData[i].value) {
                ap_rprintf(r, "= %s<br>\n", formData[i].value);
            } else {
                break;
            }
        }
    }
    
    // Print HTML footer
    ap_rprintf(r, "</body>");
    ap_rprintf(r, "</html>");
    return;
}

static void general_echo(request_rec *r){
    ap_set_content_type(r, "text/html");
    ap_rprintf(r, "<html><head><title>General Request Echo</title></head> \
      <body><h1 align=center>General Request Echo</h1> \
      <hr/>\n");
    
    // Get environment vars
    ap_rprintf(r, "<table>\n");
    ap_rprintf(r, "<tr><td>Method:</td><td>%s</td></tr>\n", r->method);
    ap_rprintf(r, "<tr><td>Query:</td><td>");
    ap_rprintf(r, "%s\n<br/>", r->args);
    ap_rprintf(r, "<tr><td>Message Body:</td><td>");
 
    /*~~~~~~~~~~~~~~~~~~~~~~*/
    keyValuePair *formData;
    /*~~~~~~~~~~~~~~~~~~~~~~*/

    formData = readPost(r);
    if (formData) {
        int i;
        for (i = 0; &formData[i]; i++) {
            if (formData[i].key && formData[i].value) {
                ap_rprintf(r, "%s = %s <br>\n", formData[i].key, formData[i].value);
            } else if (formData[i].key) {
                ap_rprintf(r, "%s<br>\n", formData[i].key);
            } else if (formData[i].value) {
                ap_rprintf(r, "= %s<br>\n", formData[i].value);
            } else {
                break;
            }
        }
    }   
    ap_rprintf(r, "</td></tr>\n");
    // Print HTML footer
    ap_rprintf(r, "</body>");
    ap_rprintf(r, "</html>");
    return;
}

static void session_1(request_rec *r){
    apr_table_t*GET; 
    ap_args_to_table(r, &GET); 

    ap_set_content_type(r, "text/html");
    char buf[1024];
    char *user = buf;
    if (strcmp(r->method, "POST") == 0){
        /*~~~~~~~~~~~~~~~~~~~~~~*/
        keyValuePair *formData;
        /*~~~~~~~~~~~~~~~~~~~~~~*/
    
        formData = readPost(r);
        if (formData && strcmp(formData[0].key, "username") == 0) {
            user = strncpy(buf, formData[0].value, 1023);
        }
        ap_cookie_write(r, "apache-cookie", user, NULL, 60*60*24, NULL);
    } else if (strcmp(r->method, "GET") == 0){
        user = apr_table_get(GET, "user");
    }
    ap_rprintf(r, "<html>");
    ap_rprintf(r, "<head><title>Apache Sessions</title></head>\n");
    ap_rprintf(r, "<body>");
    ap_rprintf(r, "<h1>Apache Sessions Page 1</h1>");
    ap_rprintf(r, "User: %s<br>", user);
    ap_rprintf(r, "<a href=\"/session-2.apache?user=%s\">Session Page 2</a><br/>", user);
    ap_rprintf(r, "<a href=\"/hw2/apache-cgiform.html\">Apache CGI Form</a><br />");
    ap_rprintf(r, "<a href=\"/destroy-session.apache\">Destroy Session</a><br />");
    ap_rprintf(r, "<a href=\"/\">Home</a><br />");
    ap_rprintf(r, "</body>");
    ap_rprintf(r, "</html>");
}

static void session_2(request_rec *r){
    apr_table_t*GET; 
    ap_args_to_table(r, &GET); 

    ap_set_content_type(r, "text/html");
    const char *user = apr_table_get(GET, "user");

    ap_rprintf(r, "<html>");
    ap_rprintf(r, "<head><title>Apache Sessions</title></head>\n");
    ap_rprintf(r, "<body>");
    ap_rprintf(r, "<h1>Apache Sessions Page 2</h1>");
    ap_rprintf(r, "User: %s<br>", user);
    ap_rprintf(r, "<a href=\"/session-1.apache?user=%s\">Session Page 1</a><br/>", user);
    ap_rprintf(r, "<a href=\"/hw2/apache-cgiform.html\">Apache CGI Form</a><br />");
    ap_rprintf(r, "<a href=\"/destroy-session.apache\">Destroy Session</a><br />");
    ap_rprintf(r, "<a href=\"/\">Home</a><br />");
    ap_rprintf(r, "</body>");
    ap_rprintf(r, "</html>");
    return;
}

static void destroy_session(request_rec *r){
    ap_set_content_type(r, "text/html");

    ap_rprintf(r, "<html>");
    ap_rprintf(r, "<head><title>Apache Sessions</title></head>\n");
    ap_rprintf(r, "<body>");
    ap_rprintf(r, "<h1>Apache Session Destroyed</h1>");
    ap_rprintf(r, "Session Destroyed<br>");
    ap_rprintf(r, "<a href=\"/hw2/apache-cgiform.html\">Apache CGI Form</a><br />");
    ap_rprintf(r, "<a href=\"/\">Home</a><br />");
    ap_rprintf(r, "</body>");
    ap_rprintf(r, "</html>");
    return;
}

