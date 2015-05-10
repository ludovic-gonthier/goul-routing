<?hh //strict
namespace Goul\Routing;

use Goul\Exception\Http\NotFoundException;

class UrlMatcher implements MatcherInterface
{
    private RouteCollection $collection;

    public function __construct(RouteCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * {@inheritdoc}
     *
     * @throws NotFoundException If no route matching
     */
    public function match(string $url): MatchingData
    {
        $routes = $this->collection->routes();
        foreach ($routes as $route) {
            $regexp = $this->patternToRegexp($route->pattern());

            $matches = [];
            $matched = preg_match_all($regexp, $url, $matches);
            if ($matched > 0) {
                return Pair{
                    $route->callback(),
                    $this->captureUrlArguments($matches)
                };
            }
        }

        // No route has been found
        $message = sprintf('No route matching url %s.', $url);
        throw new NotFoundException($message);
    }

    private function captureUrlArguments(array<mixed> $matches): array<mixed>
    {
        $arguments = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $arguments[$key] = $value;
            }
        }

        return $arguments;
    }

    private function patternToRegexp(string $url): string
    {
        $url = preg_quote($url, '/{}');

        $matches = [];
        $matched = preg_match_all('/\\\{(\w)+\\\}/', $url, $matches);
        if (count($matches) > 1) {
            foreach ($matches[0] as $argument) {
                $url = str_replace(
                    $argument,
                    '(?P<' . substr($argument, 2, -2) . '>.*)',
                    $url
                );
            }
        }

        return '/^' . $url . '$/';
    }
}
