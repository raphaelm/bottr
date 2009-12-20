<?php
// Geladene Klassen
$loaded_classes = array();

//
// Auf dieser Klasse basieren alle anderen Klassen.
// Sie kann sich selbst zu 100% selbst aufbauen und generieren.
// (Teilweise portiert aus dem HappytecBot)
class pseudoclass {
	// Ordner, in denen die Funktionen der Klasse zu finden sind.
	private $the_name_of_class = false;
	
	// Namen, unter denen Funktionen erreichbar sind
	private $function_builds = array();
	
	// Alle Variablen, die die Klasse sonst noch braucht.
	// $this wird umgeleitet.
	private $vars = array();
	
	// Variablen, die immer in Global zu den Funktionen hinzugefügt werden
	private $add_globals = '';
	
	// Klasse wird initialisiert
	// $name - Name der Klasse (Ordnername)
	// $add_globals - Variablen, die immer per global $foo; verfügbar sein sollen.
	//
	// Nicht vergessen, init() aufzurufen, da Konstruktoren nicht funktionieren!
	// (Destruktoren hingegen schon)
	public function __construct($name, $add_globals=array()) {
		global $loaded_classes;
		
		// Ordner auslesen und Klasse zusammenbauen
		if (!$dir = @opendir('class/'.$name)) {
			trigger_error('Klasse <strong>'.$name.'</strong> kann nicht geladen werden', E_USER_WARNING);
			return;
		}
		
		// Werte Setzen
		$this->the_name_of_class = $name;
		$this->add_globals = (count($add_globals) > 0) ? (', $'.implode(', $', $add_globals)) : '';
		
		while ($file = readdir($dir)) {
			if ($file == '.' || $file == '..' || is_dir('class/'.$name.'/'.$file) || substr($file, -4) != '.php') continue;
			
			$this->add_function(substr($file, 0, -4), false);
		}
		
		$loaded_classes[] = '$'.$name;
	}
	
	public function add_function($func_name, $doit = true) {
		if (!$src = file_get_contents('class/'.$this->the_name_of_class.'/'.$func_name.'.php')) {
			trigger_error('Funktion <strong>'.$this->the_name_of_class.'->'.$func_name.'()</strong> kann nicht generiert werden', E_USER_WARNING);
			return false;
		}
		
		if ($doit) {
			// Parse Check an Funktion durchführen
			$output = parse_check('class/'.$this->the_name_of_class.'/'.$func_name.'.php');
			if (strpos($output, 'No syntax errors detected in') !== false) {
				if (strpos($src, '$'.$this->the_name_of_class.'-') !== false) {
					trigger_error('$this/$self-Fehler in neu zu ladender Funktion <strong>'.$this->the_name_of_class.'->'.$func_name.'()</strong> (Zugriff auf eigene Variable)', E_USER_WARNING);
					return false;
				} elseif (strpos($src, '$this-') !== false) {
					trigger_error('$this/$self-Fehler in neu zu ladender Funktion <strong>'.$this->the_name_of_class.'->'.$func_name.'()</strong> ($this statt $self verwendet!)', E_USER_WARNING);
					return false;
				}
			} else {
				trigger_error('Syntaxfehler in neu zu ladender Funktion <strong>'.$this->the_name_of_class.'->'.$func_name.'()</strong>', E_USER_WARNING);
				if (isset($this->function_builds[$func_name])) unset($this->function_builds[$func_name]);
				return false;
			}
	
			// Funktion indizieren
			if (isset($this->function_builds[$func_name])) {
				$this->function_builds[$func_name] ++;
			} else {
				$this->function_builds[$func_name] = 1;
			}
			
			// PHP-Tags entfernen
			$src = trim(preg_replace("#^[ \t\r\n]*<\\?php[ \t\r\n]*#i", '', $src));
			$src = trim(preg_replace("#[ \t\r\n]*\\?>[ \t\r\n]*$#i", '', $src));
	
			// Funktion erstellen
			$src = str_replace(array('<?php', '?>'), array('', ''), $src);
			
			file_put_contents('cache/class_'.$this->the_name_of_class.'_function_'.$func_name.'_'.$this->function_builds[$func_name].'.php', '<?php function class_'.$this->the_name_of_class.'_'.$func_name.'_'.$this->function_builds[$func_name].'(&$self, $argumente) {'."\n".'global $loaded_classes'.$this->add_globals.';'."\n".'eval(\'global \'.implode(\', \', $loaded_classes).\';\');'."\n".$src."\n".'} ?>');
			include('cache/class_'.$this->the_name_of_class.'_function_'.$func_name.'_'.$this->function_builds[$func_name].'.php');
			unlink('cache/class_'.$this->the_name_of_class.'_function_'.$func_name.'_'.$this->function_builds[$func_name].'.php');
		} else {
			$this->function_builds[$func_name] = 0;
		}
		
		return true;
	}
		
		
	
	public function __call($name, $args) {
		if (isset($this->function_builds[strtolower($name)])) {
			
			if ($this->function_builds[strtolower($name)] == 0) {
				if ($this->add_function(strtolower($name))) {
					flush();
					$return = call_user_func('class_'.$this->the_name_of_class.'_'.$name.'_'.$this->function_builds[strtolower($name)], $this, $args);
				}
			} else {
				// Dann können wir sie ja einfach aufrufen
				$return = call_user_func('class_'.$this->the_name_of_class.'_'.$name.'_'.$this->function_builds[strtolower($name)], $this, $args);
			}
			
			return $return;
			
		} else {
			// Nicht ändern! Der else-Teil soll die Datei NICHT nachladen!
			trigger_error('Funktion <strong>'.$this->the_name_of_class.'->'.$name.'()</strong> existiert nicht', E_USER_WARNING);
		}
		return false;
	}
}

// Parse-Check Funktion
function parse_check($file) {
	$command = 'php -l '.$file;
	$output = shell_exec($command);
	return $output;
}
?>