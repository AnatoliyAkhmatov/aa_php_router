class Router {

	function __construct() {


		/*
		default options
		*/
		$options = [
			'lang' => 'ru'
		];
		$url = strtok($_SERVER["REQUEST_URI"], '?');
		$method = $_SERVER['REQUEST_METHOD'];

		$options = array_merge($options, ($method === 'GET' ? $_GET : $method === 'PUT' ? $_PUT : $_POST));

		$this->options = (object) $options;
		$this->url = $url;
		$this->method = $method;


		$tokens = [
			'XCrb1TDueG' // vilatiy
		];
		$token = getallheaders()['api_token'];
		$this->autorize = in_array($token, $tokens) ? true : false;
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

	private function route($url, $method, $handler) {
		$matches = '';
		preg_match($url, $this->url, $matches);
		if ($this->autorize && $matches && $this->method === $method) {

			header('Access-Control-Allow-Origin: *');
			header('Content-Type: application/json');
			header("HTTP/1.1 200 OK");

			$handler($this->options);

			die();
		}
	}
}



$router = new Router;
$router->get('/api\/jobs\/tags$/', function($options) {

	$response = [];

	$items = get_tags([
		'hide_empty' => false,
		'lang'			 => $options->lang
	]);

	foreach( $items as $item ):
		$response[] = [
			'name' => $item->name,
			'id' => $item->term_id
		];
	endforeach;

	wp_send_json($response);
});
