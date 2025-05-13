<?php

namespace CSlant\Blog\Api\OpenApi;

/**
 * @OA\Info(
 *     description=L5_SWAGGER_INFO_DESCRIPTION,
 *     version="1.0.0",
 *     title=L5_SWAGGER_INFO_TITLE,
 *     termsOfService="https://swagger.io/terms/",
 *     @OA\Contact(
 *         email="contact@cslant.com",
 *         name="CSlant",
 *         url="https://cslant.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 * @OA\Schemes(
 *     scheme="http",
 *     scheme="https",
 * )
 * @OA\ExternalDocumentation(
 *     description="Find out more about Swagger",
 *     url="https://swagger.io"
 * )
 * @OA\PathItem(
 *     path="/",
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_API_DEV_URL,
 *     description="OpenApi dynamic host local"
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_API_STAGING_URL,
 *     description="OpenApi dynamic host staging"
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_API_PROD_URL,
 *     description="OpenApi dynamic host production"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Authentication by bearer token",
 *     name="bearerAuth",
 *     in="header",
 *     scheme="bearer",
 *     securityScheme="sanctum"
 * )
 */
class AppInfo
{
}
