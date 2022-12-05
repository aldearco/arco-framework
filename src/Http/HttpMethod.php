<?php 

namespace Arco\Http;

/**
 * HTTP verbs enum.
 */
enum HttpMethod: string {
    case GET = "GET";
    case POST = "POST";
    case PUT = "PUT";
    case PATCH = "PATCH";
    case DELETE = "DELETE";
}