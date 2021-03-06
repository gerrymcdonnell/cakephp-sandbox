<?php
namespace App;

use Cache\Routing\Middleware\CacheMiddleware;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication {

	/**
	 * Setup the middleware your application will use.
	 *
	 * @param \Cake\Http\MiddlewareQueue $middleware The middleware queue to setup.
	 * @return \Cake\Http\MiddlewareQueue The updated middleware.
	 */
	public function middleware($middleware) {
		$middleware
			// Catch any exceptions in the lower layers,
			// and make an error page/response
			// Removed for now because of Whoops Error Handler
			//->add(new ErrorHandlerMiddleware())

			// Handle plugin/theme assets like CakePHP normally does.
			->add(new AssetMiddleware())

			// Handle cached files
			->add(new CacheMiddleware([
				'when' => function ($request, $response) {
					return $request->is('get');
				},
			]))

			// Apply routing
			->add(new RoutingMiddleware());

		return $middleware;
	}

}
