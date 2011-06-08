<!DOCTYPE HTML>
<?php
require 'lib/conf.php';
require 'lib/auth.php';
require 'lib/common.php';
require 'lib/probes.php';
?>
<html>
<head>
	<title>SolarStatus v0.3</title>
	<link href="css/style.css" rel="stylesheet" type="text/css"></link>
	<script src="js/lib.js" type="text/javascript"></script>
	<script src="js/jquery.js" type="text/javascript"></script>
	<script src="js/jquery.sha1.js" type="text/javascript"></script>
	<script src="js/auth.js" type="text/javascript"></script>
	<script src="js/solar.js" type="text/javascript"></script>
	<script src="js/TableTransformer.js" type="text/javascript"></script>
	<script src="js/parsers.js" type="text/javascript"></script>
</head>
<body>
<?php
try {
	loadConfig();
} catch (Exception $e) {
	displayException($e);
	exit;
}

$password = $challenge = $response = $token = NULL;

if (isPasswordLogin($password)) {
	if (!loginPWD($password, $token)) {
		echo getLoginForm(true); echo "</body></html>";
		exit;
	} else {
		reloadTokenized($token);
	}
}

if (isChallengeResponseLogin($challenge, $response)) {
	if (!loginCR($challenge, $response, $token)) {
		echo getLoginForm(true); echo "</body></html>";
		exit;
	} else {
		reloadTokenized($token);
	}
}

if (!isAuthorized($token)) {
	echo getLoginForm(false); echo "</body></html>";
	exit;
}

?>
<script type="text/javascript"> var TOKEN = "<?php echo $token; ?>"; </script>
<?php unset($password, $challenge, $response, $token); ?>


<nav id="main-panel">
	<ul id="probe-filters">
		<li class="overview"><a href="#overview" title="Show overview" data-filter="#overview">Overview</a></li>
		<?php
		try {
			$filters = $_SERVER['SOLAR_CONFIG']['FILTERS'];
			
			foreach ($filters as $filterID => $filter) {
				$label    = $filter['LABEL'];
				$selector = $filter['SELECTOR'];
				$clazzSel = (isset($filter['DEFAULT']) && $filter['DEFAULT']) ? 'selected' : '';

				echo <<<EOC
		<li class="${clazzSel}"><a href="#filter" title="Filter ${label}" data-filter="${selector}">${label}</a></li>

EOC;
			}
		} catch (Exception $e) {
			displayException($e);
			exit;
		}
		?>
		<li><a href="#filter" title="Show all probes" data-filter=".probe">All</a></li>
	</ul>
	
	<div id="probe-refresh">
		<label><input id="probe-refresh-active" type="checkbox" name="probe_refresh_active" value="1" /> Auto refresh</label>
		<label> every <input id="probe-refresh-freq" type="number" name="probe_refresh_freq" min="1" value="3" /> seconds</label>
	</div>
</nav>

<section id="overview" class="hide">
	<ul>
		<li>T O D O</li>
		<li><label>CPU #1 <meter min="0" max="100" value="25" title="Non-Idleness"></meter></label></li>
		<li><label>CPU #2 <meter min="0" max="100" value="50" title="Non-Idleness"></meter></label></li>
	</ul>
</section>

<section id="probes">
	<?php
	$probes  = $_SERVER['SOLAR_CONFIG']['PROBES'];
	$clazzes = array();

	foreach ($probes as $probeID => $probeConf) {
		// LABEL
		if (isset($probeConf['LABEL'])) {
			$label = $probeConf['LABEL'];
		} else {
			$label = $probeID;
		}
		
		// CLASS
		if (isset($probeConf['CLASS'])) {
			$probeClazzes = splitTrim($probeConf['CLASS'], ' ');
			$clazzes      = array_unique(array_merge($clazzes, $probeClazzes));
		} else {
			$probeClazzes = array();
		}
		$probeClazz = implode(' ', $probeClazzes);
		
		// SCRIPT / CMD
		$script = $cmdID = NULL;
		if (isset($probeConf['SCRIPT'])) {
			$script = $probeConf['SCRIPT'];
		} else {
			$cmdID = $probeConf['CMD'];
		}
		
		// OUTPUT
		echo <<<EOC
	<div id="${probeID}" class="probe ${probeClazz} hide" data-script="${script}" data-cmd="${cmdID}">
		<header>
			<h1>${label}</h1>
			<ul class="view-selector">
				<li class="view-raw"><a href="#raw" title="View raw data" data-filter=".raw">Raw</a></li>
				<li class="view-data hide"><a href="#data" title="View parsed data" data-filter=".data">Parsed</a></li>
			</ul>
			<a href="#refresh" class="refresh" title="refresh data"></a>
			<div class="failure hide"></div>
		</header>
		<div class="raw"></div>
		<div class="data hide"></div>
		<footer>
			<time datetime="" data-timestamp=""></time>
		</footer>
	</div>

EOC;
	}
	?>
</section>

</body>
</html>