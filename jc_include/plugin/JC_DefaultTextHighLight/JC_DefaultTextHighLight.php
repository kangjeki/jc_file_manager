<?php
// class harus sama dengan folder
class JC_DefaultTextHighLight {

	// public function untuk auto load adalah start plugin | ==> return 
	public function start_plugin( $data ) {
		$data 	= str_replace("</", ":TA2:", $data);
		$data 	= str_replace("<", ":TA1:", $data);
		$data 	= str_replace(">", ":TE:", $data);

		preg_match_all('!"[a-z-A-Z-0-9- -.-/]*"!', $data, $mat2);
		foreach ($mat2[0] as $fin2) {
			$data 	= str_replace($fin2, ':of:'.$fin2.':eof:', $data);
		}
		$data 	= str_replace(':of::of:', ':of:', $data);
		$data 	= str_replace(':eof::eof:', ':eof:', $data);

		preg_match_all('! [a-z-A-Z-0-9- -.-/]*=!', $data, $mat1);
		foreach ($mat1[0] as $fin1) {
			$data 	= str_replace($fin1, ':lf:'.$fin1.':elf:', $data);
		}
		$data 	= str_replace(':lf::lf:', ':lf:', $data);
		$data 	= str_replace(':elf::elf:', ':elf:', $data);


		preg_match_all('!:TA1:[a-z-A-Z-0-9- -.-/]*:lf:!', $data, $mat3);
		foreach ($mat3[0] as $fin3) {
			$re 	= str_replace(":lf:", "", $fin3);
			$data 	= str_replace($fin3, ':bf:'.$re.':ebf::lf:', $data);
		}

		preg_match_all('!:TA2:[a-z-A-Z-0-9- -.-/]*:TE:!', $data, $mat4);
		foreach ($mat4[0] as $fin4) {
			$re2 	= str_replace(":TA2:", ":TA1:/", $fin4);
			$re2 	= str_replace(":TE:", "", $re2);
			$data 	= str_replace($fin4, ':bf:'.$re2.':ebf::TE:', $data);
		}

		$data = str_replace(":elf:", '</span>', $data);
		$data = str_replace(":eof:", '</span>', $data);
		$data = str_replace(":ebf:", '</span>', $data);

		$data = str_replace(":lf:", '<span class="cl">', $data);
		$data = str_replace(":of:", '<span class="co">', $data);
		$data = str_replace(":bf:", '<span class="cb">', $data);

		$data = str_replace(":TE:", '<span class="cb">></span>', $data);
		$data = str_replace(":TA1:", '<span class="cb"><</span>', $data);


		$data 	= str_replace("\n", "<br>", $data);
		$hasil 	= str_replace("	", "&nbsp;", $data);

		return $hasil;
	}
}