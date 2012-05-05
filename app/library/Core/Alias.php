<?php
class Core_Alias
{
	protected
		$_model,
		$_chars = array (
			"'"    => '-',
			"`"    => '-',
			"?"    => '',
			"\xa0" => '',
			"\xa1" => '',
			"\xa2" => '',
			"\xa3" => '',
			"\xa4" => '',
			"\xa5" => '',
			"\xa6" => '',
			"\xa7" => '',
			"\xa8" => '',
			"\xa9" => '',
			"\xaa" => '',
			"\xab" => '',
			"\xac" => '',
			"\xad" => '',
			"\xae" => '',
			"\xaf" => '',
			"\xb0" => '',
			"\xb1" => '',
			"\xb2" => '',
			"\xb3" => '',
			"\xb4" => '',
			"\xb5" => '',
			"\xb6" => '',
			"\xb7" => '',
			"\xb8" => '',
			"\xb9" => '',
			"\xba" => '',
			"\xbb" => '',
			"\xbc" => '',
			"\xbd" => '',
			"\xbe" => '',
			"\xbf" => '',
			"\xd7" => '',
			"\xde" => '',
			"\xdf" => '',
			"\xf7" => '',
			"\xfe" => '',
			"\xc6" => '',
			"\xe6" => '',
			"\xf0" => '',
			"\xc0" => 'A',
			"\xc1" => 'A',
			"\xc2" => 'A',
			"\xc3" => 'A',
			"\xc4" => 'A',
			"\xc5" => 'A',
			"\xe0" => 'a',
			"\xe1" => 'a',
			"\xe2" => 'a',
			"\xe3" => 'a',
			"\xe4" => 'a',
			"\xe5" => 'a',
			"\xc7" => 'C',
			"\xe7" => 'c',
			"\xc8" => 'E',
			"\xc9" => 'E',
			"\xca" => 'E',
			"\xcb" => 'E',
			"\xe8" => 'e',
			"\xe9" => 'e',
			"\xea" => 'e',
			"\xeb" => 'e',
			"\xcc" => 'I',
			"\xcd" => 'I',
			"\xce" => 'I',
			"\xcf" => 'I',
			"\xec" => 'i',
			"\xed" => 'i',
			"\xee" => 'i',
			"\xef" => 'i',
			"\xd0" => 'D',
			"\xd1" => 'N',
			"\xf1" => 'n',
			"\xd2" => 'O',
			"\xd3" => 'O',
			"\xd4" => 'O',
			"\xd5" => 'O',
			"\xd6" => 'O',
			"\xd8" => 'O',
			"\xf2" => 'o',
			"\xf3" => 'o',
			"\xf4" => 'o',
			"\xf5" => 'o',
			"\xf6" => 'o',
			"\xf8" => 'o',
			"\xd9" => 'U',
			"\xda" => 'U',
			"\xdb" => 'U',
			"\xdc" => 'U',
			"\xf9" => 'u',
			"\xfa" => 'u',
			"\xfb" => 'u',
			"\xfc" => 'u',
			"\xdd" => 'Y',
			"\xfd" => 'y',
			"\xff" => 'y',
	);

	public function __construct(Core_Db_Table $model = null)
	{
		$this->_model = $model;
	}

	public function generate($title, $excludeParam = array(), $coding = false)
	{
		if ($coding) {
			$title = iconv($coding, 'UTF-8', $title);
		}
		$toLowerFilter = new Zend_Filter_StringToLower;
		$toLowerFilter->setEncoding('UTF-8');
		$title = $toLowerFilter->filter($title);
		$title = $this->translit($title);
		$alias = $this->prepare($title);
		$aliasTemp = $alias;
		$i = 0;
		do {
			$aliasTemp = $this->make($alias, $i);
		} while($this->check($aliasTemp, $excludeParam));
		return $aliasTemp;
	}

	private function make($alias, &$index)
	{
		if ($index==0) {
			$index++;
			return $alias;
		} else {
			return $alias .'-'. $index++;
		}
	}

	private function translit($title)
	{
		$re_ar = array("а"=>"a", "А"=>"A", "б"=>"b", "Б"=>"B", "в"=>"v", "В"=>"V", "г"=>"g", "Г"=>"G", "ґ"=>"g", "Ґ"=>"G", "д"=>"d", "Д"=>"D", "е"=>"e", "Е"=>"E", "Є"=>"E", "є"=>"e", "ё"=>"e", "Ё"=>"E", "ж"=>"j", "Ж"=>"J", "з"=>"z", "З"=>"Z", "І"=>"I", "і"=>"i", "Ї"=>"I", "ї"=>"i", "и"=>"i", "И"=>"I", "й"=>"i", "Й"=>"I", "к"=>"k", "К"=>"K", "л"=>"l", "Л"=>"L", "м"=>"m", "М"=>"M", "н"=>"n", "Н"=>"N", "о"=>"o", "О"=>"O", "п"=>"p", "П"=>"P", "р"=>"r", "Р"=>"R", "с"=>"s", "С"=>"S", "т"=>"t", "Т"=>"T", "у"=>"y", "У"=>"Y", "ф"=>"f", "Ф"=>"F", "х"=>"h", "Х"=>"H", "ц"=>"c", "Ц"=>"C", "ч"=>"ch", "Ч"=>"CH", "ш"=>"sh", "Ш"=>"SH", "щ"=>"sh", "Щ"=>"SH", "ъ"=>"'", "Ъ"=>"'", "ы"=>"y", "Ы"=>"Y", "ь"=>"'", "Ь"=>"'", "э"=>"e", "Э"=>"E", "ю"=>"u", "Ю"=>"U", "я"=>"ia", "Я"=>"IA");
		foreach ($re_ar as $key=>$val) {
			$title = preg_replace ("/{$key}/", "{$val}", $title);
		}
		return $title;
	}

	private function prepare($title)
	{
		$result = preg_replace(
				array ('/[^0-9a-zA-Z_\.-]/', '/-+/'),
				array ('', '-'),
				str_replace(' ', '-', strtr(utf8_decode(trim($title)), $this->_chars))
		);
		return $result;
	}

	private function check($alias, $excludeParam = array())
	{
		if ($this->_model) {
			return $this->_model->isExists(array('alias'=>$alias), $excludeParam);
		} else {
			return false;
		}
	}
}