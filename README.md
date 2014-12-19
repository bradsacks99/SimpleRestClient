<h3>Simple Rest Client</h3>
<li>Supports Basic Authentication only
<li>Provides simple syntax
<li>Supports POST, PUT, DELETE, and GET


<h4>Easy examples:<h4>
<pre>
require_once ('cRestClient.php');

$headers = array('Accept' => 'application/json', 'Content-Type' => 'application/json');
$user = 'test';
$pass = 'test';

$c = new cRestClient($user, $pass, $headers);

$response = $c->get('http://requestb.in/sbqw6ksb'); 
</pre>

<pre>
require_once ('cRestClient.php');

$headers = array('Accept' => 'application/json', 'Content-Type' => 'application/json');
$user = 'test';
$pass = 'test';

$c = new cRestClient($user, $pass, $headers);

$response = $c->post('http://requestb.in/sbqw6ksb', '{"json":true}'); 
</pre>

<h4>More complex example:</h4>
<pre>
require_once ('cRestClient.php');


$user = 'test';
$pass = 'test';

$c = new cRestClient();
$c->addHeader('Accept', 'application/json');
$c->addHeader('Content-Type', 'application/json');

$c->setMethod('POST');
$c->setBasicAuth($user, $pass);
$c->setVerbose(true);

$c->setPayload('{"json": true}');

$c->setUrl('http://requestb.in/sbqw6ksb');

$c->go();

$response = $c->get('http://requestb.in/sbqw6ksb'); 
</pre>