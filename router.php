<?php


class Router {
	function __construct() {

		$options = [];
		$url = strtok($_SERVER["REQUEST_URI"], '?');
		$method = $_SERVER['REQUEST_METHOD'];

		if ($method === 'GET') $options = array_merge($options, $_GET);
		if ($method === 'POST') $options = array_merge($options, (array) (json_decode(file_get_contents('php://input'), true)));
		if ($method === 'PUT') $options = array_merge($options, $_PUT);
		$options['url'] = $url;

		$this->options = (object) $options;
		$this->url = $url;
		$this->method = $method;
	}

	public function get($url, $handler) {
		$this->route($url, 'GET', $handler);
	}

	public function post($url, $handler) {
		$this->route($url, 'POST', $handler);
	}

	public function delete($url, $handler) {
		$this->route($url, 'DELETE', $handler);
	}

	public function put($url, $handler) {
		$this->route($url, 'PUT', $handler);
	}

	public static function match($matched, $url) {
		$res = (object) [
			'match' => false,
			'vars' => []
		];

		preg_match_all('/\{.+?\}/', $matched, $vars);
		if (!empty($vars[0])) {
			$vars = $vars[0];
			$matched = str_replace($vars, '(.+?)', $matched);
			$matched = str_replace('/', '\/', $matched);
			preg_match('/^' . $matched . '$/', $url, $matched);

			$matched_count = count($matched);
			if (count($vars) == $matched_count - 1) {
				for ($x = 1; $x < $matched_count; $x++) {
					$var_name = str_replace(['{', '}'], '', $vars[$x - 1]);
					$res->vars[$var_name] = $matched[$x];
				}
				$res->match = true;
			} else {
				// not match
			}
		} else {
			$res->match = $matched === $url;
		}

		return $res;
	}

	private function route($url, $method, $handler) {
		$matches = $this->match($url, $this->url);

		if ($matches->match && $this->method === $method) {
			$this->options->matches = $matches;

			if (!empty($matches->vars)) {
				$this->options = (object) (array_merge((array) $this->options, $matches->vars));
			}

			$this->options = strip($this->options);

			$handler($this->options);
		}
	}
}
