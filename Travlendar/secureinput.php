<?php
	function secureInput($txt){
		$txt = strip_tags($txt);
		$txt = htmlspecialchars($txt);
        $txt = stripslashes($txt);

        return $txt;
	}
?>