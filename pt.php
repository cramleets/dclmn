<?php

/*
	Phantasy Tour scraper
	- Pulls 15-minute intervals from 2026-01-01 00:00:00 to now
	- Inserts JSON fields into MySQL
	- Ignores the "body" field
	- Skips duplicates using INSERT IGNORE

	Table should already exist with matching column names.
*/

date_default_timezone_set('America/New_York');

/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/

set_time_limit(0);

$mysqli = new mysqli(
	'localhost',
	'dclmn',
	'dclmn',
	'dclmn'
);

if ($mysqli->connect_errno) {
	die("MySQL connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');

/*
|--------------------------------------------------------------------------
| SETTINGS
|--------------------------------------------------------------------------
*/

$base_url = 'https://www.phantasytour.com/api/bands/1/posts/search';

$start = new DateTime('2026-05-28 00:00:00');
$now = new DateTime();

$interval_minutes = 15;

/*
|--------------------------------------------------------------------------
| MAIN LOOP
|--------------------------------------------------------------------------
*/

while ($start < $now) {

	$end = clone $start;
	$end->modify("+{$interval_minutes} minutes");

	if ($end > $now) {
		$end = clone $now;
	}

	$start_iso = urlencode($start->format('Y-m-d\TH:i:sP'));
	$end_iso   = urlencode($end->format('Y-m-d\TH:i:sP'));

	$url = $base_url .
		'?searchTerm=' .
		'&startDate=' . $start_iso .
		'&endDate=' . $end_iso .
		'&authorId=' .
		'&pageSize=100';

	// echo "Fetching: {$start->format('Y-m-d H:i:s')} -> {$end->format('Y-m-d H:i:s')}\n";

	$ch = curl_init();

	curl_setopt_array($ch, [
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_USERAGENT => 'Mozilla/5.0'
	]);

	$response = curl_exec($ch);

	if (curl_errno($ch)) {
		echo "cURL error: " . curl_error($ch) . "\n";
		curl_close($ch);

		$start = $end;
		continue;
	}

	curl_close($ch);

	$data = json_decode($response, true);

	if (!is_array($data)) {
		echo "Invalid JSON\n";

		$start = $end;
		continue;
	}

	/*
	|--------------------------------------------------------------------------
	| FIND POSTS ARRAY
	|--------------------------------------------------------------------------
	|
	| Adjust this if their JSON structure changes.
	|
	*/

	$posts = [];

	if (isset($data['data']) && is_array($data['data'])) {
		$posts = $data['data'];
	}
	elseif (isset($data['posts']) && is_array($data['posts'])) {
		$posts = $data['posts'];
	}
	elseif (array_is_list($data)) {
		$posts = $data;
	}

	foreach ($posts as $post) {

		if (!is_array($post)) {
			continue;
		}

		// Ignore body
		unset($post['body']);

		if (empty($post)) {
			continue;
		}

		$columns = [];
		$placeholders = [];
		$values = [];
		$types = '';

		foreach ($post as $column => $value) {

			$columns[] = "`" . $mysqli->real_escape_string($column) . "`";
			$placeholders[] = '?';

			// Convert arrays/objects to JSON strings
			if (is_array($value) || is_object($value)) {
				$value = json_encode($value);
			}

			$values[] = $value;
			$types .= 's';
		}

		$sql = "
			INSERT IGNORE INTO phantasytour_posts
			(" . implode(',', $columns) . ")
			VALUES
			(" . implode(',', $placeholders) . ")
		";

		$stmt = $mysqli->prepare($sql);

		if (!$stmt) {
			echo "Prepare failed: " . $mysqli->error . "\n";
			continue;
		}

		$stmt->bind_param($types, ...$values);

		if (!$stmt->execute()) {
			echo "Insert failed: " . $stmt->error . "\n";
		}

		$stmt->close();
	}

	// Tiny delay so you don't hammer the API like a raccoon attacking a vending machine
	usleep(250000);

	$start = $end;
}

echo "Done.\n";



/**
 SELECT
	p.authorUsername,
	COUNT(*) AS post_count,
	ROUND(
		COUNT(*) * 100.0 / MAX(totals.total_posts),
		2
	) AS percentage
FROM phantasytour_posts p

JOIN (
	SELECT COUNT(*) AS total_posts
	FROM phantasytour_posts
	WHERE authorUsername IN (
		SELECT authorUsername
		FROM phantasytour_posts
		WHERE authorUsername IS NOT NULL
		AND authorUsername != ''
		GROUP BY authorUsername
		HAVING COUNT(*) > 1
	)
) totals

WHERE p.authorUsername IS NOT NULL
AND p.authorUsername != ''
AND CAST(p.dateCreated AS DATE) = CAST(NOW() as DATE)

GROUP BY p.authorUsername

HAVING COUNT(*) > 1

ORDER BY post_count DESC;
 */