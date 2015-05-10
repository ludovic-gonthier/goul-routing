<?hh //strict
namespace Goul\Routing;

use Goul\Routing\RouteEnum\Requirements;

class RouteCollection
{
    /**
     * A Vector in with Routes will be stored
     *
     * @var Vector<Route>
     */
    protected Vector<Route> $routes = Vector {};

    /**
     * Create and store a route for the GET method
     *
     * @param  string $pattern  Route's pattern
     * @param  mixed  $callback Route's callback
     * @return Route            A Route instance
     */
    public function get(string $pattern, mixed $callback): Route
    {
        $route = new Route($pattern, $callback);

        $this->routes[] = $route;

        return $route;
    }

    /**
     * Create and store a route for the POST method
     *
     * @param  string $pattern  Route's pattern
     * @param  mixed  $callback Route's callback
     * @return Route            A Route instance
     */
    public function post(string $pattern, mixed $callback): Route
    {
        $route = new Route($pattern, $callback);
        $route->requirements()[Requirements::METHOD] = HttpMethod::POST;

        $this->routes[] = $route;

        return $route;
    }

    /**
     * Group a RouteCollection to another, prefixing its route
     *
     * @param  string          $prefix     Prefix to apply to the RouteCollection
     *                                     Route's pattern
     * @param  RouteCollection $collection A RouteCollection instance
     * @return RouteCollection             Current instance
     */
    public function group(string $prefix, RouteCollection $collection): this
    {
        $prefix = trim(trim($prefix, '/'));
        if ($prefix === '') {
            return $this;
        }

        foreach ($collection->routes as $route) {
            $route->prefixPattern($prefix);

            $this->routes[] = $route;
        }

        return $this;
    }

    /**
     * Return the stored Route
     *
     * @return Vector<Route> A Vector of Route
     */
    public function routes(): Vector<Route>
    {
        return $this->routes;
    }
}
