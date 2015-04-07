<?hh //strict
namespace Goul\Routing;

use Goul\Routing\RouteEnum\Arguments;
use Goul\Routing\RouteEnum\Requirements;
use Goul\Routing\RouteEnum\Options;

enum HttpMethod: string {
    GET = 'GET';
    POST = 'POST';
    PUT = 'PUT';
    PATCH = 'PATCH';
    UPDATE = 'UPDATE';
    DELETE = 'DELETE';
};

class Route
{
    /**
     * The pattern representing the route
     *
     * @var string
     */
    private string $pattern;

    /**
     * The callback the route will trigger
     *      Either:
     *           - A Closure
     *           - A callable string: Foo::bar, where bar is a static function
     *           - A callable array: array($foo, 'bar')
     *
     * @var Closure|string|array(string, string)
     */
    private mixed $callback;

    private Map<Options, mixed> $options = Map{};

    private Map<Requirements, mixed> $requirements = Map{};

    private Map<string, Map<Arguments, mixed>> $arguments = Map{};

    /**
     * Route constructor
     *
     * @param string    $pattern  The route pattern
     * @param Callback  $callback The callback to trigger for the route
     */
    public function __construct(string $pattern, mixed $callback, array<Options, mixed> $options = array())
    {
        if ($pattern === '') {
            $message = 'A Route pattern cannot be an empty string.';
            throw new \InvalidArgumentException($message);
        }

        if (!is_callable($callback)) {
            $message = 'A valid callback must be passed to the constructor.';
            throw new \InvalidArgumentException($message);
        }

        $this->pattern = '/' . trim(ltrim($pattern, '/')) ;
        $this->callback = $callback;

        $this->options->setAll($options);
    }

    /**
     * Return the route pattern
     * @return string The route pattern
     */
    public function pattern(): string
    {
        return $this->pattern;
    }

    /**
     * Prefix the route pattern with the given prefix
     *
     * @param  string $prefix The prefix to apply to the pattern
     * @return this           The current instance
     */
    public function prefixPattern(string $prefix): this
    {
        $prefix = '/' . ltrim($prefix, '/');

        $this->pattern = $prefix . $this->pattern;
        $this->options[Options::PREFIX] = $prefix;

        return $this;
    }

    /**
     * Return the route callback
     *
     * @return mixed The route callback
     */
    public function callback(): mixed
    {
        return $this->callback;
    }

    /**
     * Return the route options
     *
     * @return Map<Options, mixed> The route options
     */
    public function options(): Map<Options, mixed>
    {
        return $this->options;
    }

    /**
     * Return the route requirements
     *
     * @return Map<Requirements, mixed> The route requirements
     */
    public function requirements(): Map<Requirements, mixed>
    {
        return $this->requirements;
    }

    /**
     * Return the route arguments
     *
     * @return Map<string, Map<Arguments, mixed>> The route arguments
     */
    public function arguments(): Map<string, Map<Arguments, mixed>>
    {
        return $this->arguments;
    }
}
