<?php
error_reporting(E_ALL);

require_once 'GameQ.php';

$servers = array(
	'Test' =>array('cs', '94.156.174.134', 27021)
);

$gq = new GameQ();
$gq->addServers($servers);

$gq->setOption('timeout', 200);

$gq->setFilter('normalise');
$gq->setFilter('sortplayers', 'gq_ping');

$results = $gq->requestData();

function print_results($results) {
    foreach ($results as $id => $data) {
        printf("<h2>%s</h2>\n", $id);
        print_table($data);
    }
}

function print_table($data) {
	$gqs = array('gq_online', 'gq_address', 'gq_port', 'gq_prot', 'gq_type');
	
	if (!$data['gq_online']) {
		printf("<p>The server did not respond within the specified time.</p>\n");
		return;
	}
	
	print("<table><thead><tr><td>Variable</td><td>Value</td></tr></thead><tbody>\n");
	
	foreach ($data as $key => $val) {
		if (is_array($val)) continue;
		
		$cls = empty($cls) ? ' class="uneven"' : '';
		
		if (substr($key, 0, 3) == 'gq_') {
			$kcls = (in_array($key, $gqs)) ? 'always' : 'normalise';
			$key = sprintf("<span class=\"key-%s\">%s</span>", $kcls, $key);
		}
		printf("<tr%s><td>%s</td><td>%s</td></tr>\n", $cls, $key, $val);
	}
	print("</tbody></table>\n");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>GameQ - Example script</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            * {
                font-size: 9pt;
                font-family: Verdana, sans-serif;
            }
            h1 {
                font-size: 12pt;
            }
            h2 {
                margin-top:2em;
                font-size: 10pt;
            }
            table {
                border: 1px solid #000;
                background-color: #DDD;
                border-spacing:1px 1px;
            }
            table thead {
                font-weight: bold;
                background-color: #CCC;
            }
            table tr.uneven td {
                background-color:#FFF;
            }
            table td {
                padding: 5px 8px;
            }
            table tbody {
                background-color: #F9F9F9;
            }
            .note {
                color: #333;
                font-style:italic;
            }
            .key-always {
                color:red;
                font-weight:bold;
            }
            .key-normalise {
                color:red;
            }
        </style>
    </head>
    <body>
    <h1>GameQ - Example script</h1>
    <div class="note">
    Players are never displayed in this example. <br/>
    <span class="key-always">Bold, red</span> variables are always set by gameq. 
    Additionally, the normal <span class="key-normalise">red</span> variables are always set when the normalise filter is enabled.<br/>
    gq_online will always contain a boolean indicating if the server responded to the request.<br/>
    <br/>
    Click <a href="list.php">here</a> for a list of supported games.
    </div>
<?php
    print_results($results);
?>
    </body>
</html>