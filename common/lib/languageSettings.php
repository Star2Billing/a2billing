<?php

function SetLocalLanguage()
{
	$slectedLanguage = "";
	$languageEncoding = "";
	$charEncoding = "";
	switch (LANGUAGE)
	{
		case "arabic":
			$languageEncoding = "en_US.iso88591";
			$slectedLanguage = "en_US";
			$charEncoding = "iso88591";
			break;
		case "brazilian":
			$languageEncoding = "pt_BR.UTF-8";
			$slectedLanguage = "pt_BR";
			$charEncoding = "UTF-8";
			break;
		case "chinese":
			$languageEncoding = "zh_TW.UTF-8";
			$slectedLanguage = "zh_TW";
			$charEncoding = "UTF-8";
			break;
		case "english":
			$languageEncoding = "en_US.iso88591";
			$slectedLanguage = "en_US";
			$charEncoding = "iso88591";
			break;
		case "spanish":
			$languageEncoding = "es_ES.iso88591";
			$slectedLanguage = "es_ES";
			$charEncoding = "iso88591";
			break;
		case "french":
			$languageEncoding = "fr_FR.iso88591";
			$slectedLanguage = "fr_FR";
			$charEncoding = "iso-8859-1";
			break;
		case "german":
			$languageEncoding = "en_US.iso88591";
			$slectedLanguage = "en_US";
			$charEncoding = "iso88591";
			break;
		case "italian":
			$languageEncoding = "it_IT.iso8859-1";
			$slectedLanguage = "it_IT";
			$charEncoding = "iso88591";
			break;
		case "polish":
			$languageEncoding = "pt_PT.iso88591";
			$slectedLanguage = "pl_PL";
			$charEncoding = "iso88591";
			break;
		case "romanian":
			$languageEncoding = "ro_RO.iso88591";
			$slectedLanguage = "ro_RO";
			$charEncoding = "iso88591";
			break;
		case "russian":
			$languageEncoding = "ru_RU.UTF-8";
			$slectedLanguage = "ru_RU";
			$charEncoding = "UTF-8";
			break;
		case "turkish":
			$languageEncoding = "tr_TR.iso88599";
			$slectedLanguage = "tr_TR";
			$charEncoding = "iso88599";
			break;
		case "urdu":
			$languageEncoding = "ur.UTF-8";
			$slectedLanguage = "ur_PK";
			$charEncoding = "UTF-8";
			break;
		case "ukrainian": // provided by Oleh Miniv  email: oleg-min@ukr.net
			$languageEncoding = "uk_UA.UTF8";
			$slectedLanguage = "uk_UA";
			$charEncoding = "UTF8";
			break;
		default:
			$languageEncoding = "en_US.iso88591";
			$slectedLanguage = "en_US";
			$charEncoding = "iso88591";
			break;
	}
	/*
	Code here to set the Encoding of the Lanuages and its Envirnoment Variables
	*/
	// echo "languageEncoding=$languageEncoding - slectedLanguage=$slectedLanguage - path=".BINDTEXTDOMAIN;
	@setlocale(LC_TIME,$languageEncoding);
	putenv("LANG=$slectedLanguage");
	putenv("LANGUAGE=$slectedLanguage");
	setlocale(LC_ALL, $slectedLanguage);
	$domain = 'messages';
	bindtextdomain("messages", BINDTEXTDOMAIN);
	textdomain($domain);
	bind_textdomain_codeset($domain, $charEncoding);
	define('CHARSET', $charEncoding);
}

