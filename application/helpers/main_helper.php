<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('current_datetime_indo')) {
	function current_datetime_indo()
	{
		$date = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
		$dateString = $date->format('Y-m-d H:i:s');

		return $dateString;
	}
};

if (!function_exists('hitung_uang_makan')) {
	function hitung_uang_makan($hari_kerja)
	{
		$total = 15000 * $hari_kerja;
		return $total;
	}
};

if (!function_exists('hitung_total')) {
	function hitung_total($values, $operations) {
		$total = 0;
	
		foreach ($values as $index => $subValues) {
			$operation = $operations[$index] ?? 'add';
	
			foreach ($subValues as $value) {
				switch ($operation) {
					case 'add':
						$total += $value;
						break;
					case 'subtract':
						$total -= $value;
						break;
					case 'multiply':
						$total *= $value;
						break;
					case 'divide':
						if ($value != 0) {
							$total /= $value;
						} else {
							// Handle division by zero error
							return "Error: Division by zero";
						}
						break;
					default:
						return "Error: Invalid operation";
				}
			}
		}
	
		return $total;
	}	
};
