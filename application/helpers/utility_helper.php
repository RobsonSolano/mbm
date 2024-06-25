<?php

if (!function_exists('format_decimal_br_to_us')) {
	function format_decimal_br_to_us($value, $locale = 'pt_BR', $pattern = '#,##0.00')
	{
		$fmtdec = numfmt_create($locale, NumberFormatter::PATTERN_DECIMAL, $pattern);
		return numfmt_parse($fmtdec, $value);
	}
}


if (!function_exists('format_decimal_us_to_br')) {

	function format_decimal_us_to_br($value, $locale = 'pt_BR', $pattern = '#,##0.00')
	{
		$fmtdec = numfmt_create($locale, NumberFormatter::PATTERN_DECIMAL, $pattern);
		return numfmt_format($fmtdec, $value);
	}
}


if (!function_exists('get_client_ip')) {
	function get_client_ip()
	{

		$ipaddress = $_SERVER['REMOTE_ADDR'];

		return $ipaddress;
	}
}

if (!function_exists('validaCPF')) {
	function validaCPF($cpf)
	{

		// Extrai somente os números
		$cpf = preg_replace('/[^0-9]/is', '', $cpf);

		// Verifica se foi informado todos os digitos corretamente
		if (strlen($cpf) != 11) {
			return false;
		}

		// Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
		if (preg_match('/(\d)\1{10}/', $cpf)) {
			return false;
		}

		// Faz o calculo para validar o CPF
		for ($t = 9; $t < 11; $t++) {
			for ($d = 0, $c = 0; $c < $t; $c++) {
				$d += $cpf[$c] * (($t + 1) - $c);
			}
			$d = ((10 * $d) % 11) % 10;
			if ($cpf[$c] != $d) {
				return false;
			}
		}
		return true;
	}
}

if (!function_exists('get_cidades_proximas')) {
	function get_cidades_proximas($txt_padrao = "Selecione sua cidade", $selected = "")
	{
		$cidades_proximas = [
			'' => $txt_padrao,
			'Americana' => 'Americana',
			'Araçariguama' => 'Araçariguama',
			'Araçoiaba da Serra' => 'Araçoiaba da Serra',
			'Boituva' => 'Boituva',
			'Cabreúva' => 'Cabreúva',
			'Campinas' => 'Campinas',
			'Capivari' => 'Capivari',
			'Cerquilho' => 'Cerquilho',
			'Conchal' => 'Conchal',
			'Cordeirópolis' => 'Cordeirópolis',
			'Elias Fausto' => 'Elias Fausto',
			'Hortolândia' => 'Hortolândia',
			'Indaiatuba' => 'Indaiatuba',
			'Itu' => 'Itu',
			'Itupeva' => 'Itupeva',
			'Jaguariúna' => 'Jaguariúna',
			'Jarinu' => 'Jarinu',
			'Jundiaí' => 'Jundiaí',
			'Louveira' => 'Louveira',
			'Monte Mor' => 'Monte Mor',
			'Nova Odessa' => 'Nova Odessa',
			'Paulínia' => 'Paulínia',
			'Piedade' => 'Piedade',
			'Pilar do Sul' => 'Pilar do Sul',
			'Piracicaba' => 'Piracicaba',
			'Porto Feliz' => 'Porto Feliz',
			'Rafard' => 'Rafard',
			'Rio das Pedras' => 'Rio das Pedras',
			'Salto' => 'Salto',
			'Salto de Pirapora' => 'Salto de Pirapora',
			'Santa Bárbara dOeste' => 'Santa Bárbara dOeste',
			'Santa Gertrudes' => 'Santa Gertrudes',
			'Santa Maria da Serra' => 'Santa Maria da Serra',
			'Santo Antônio de Posse' => 'Santo Antônio de Posse',
			'Sorocaba' => 'Sorocaba',
			'Sumaré' => 'Sumaré',
			'Tatuí' => 'Tatuí',
			'Tietê' => 'Tietê',
			'Valinhos' => 'Valinhos',
			'Vinhedo' => 'Vinhedo',
			'Votorantim' => 'Votorantim'
		];

		if(!empty($selected)){
			return $cidades_proximas[$selected];
		}

		return $cidades_proximas;
	}
}
