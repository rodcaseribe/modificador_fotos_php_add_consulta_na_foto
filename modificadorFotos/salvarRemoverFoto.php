<?php
if(isset($_POST['img']) && isset($_POST['nome'])){
	$source=fopen($_POST['img'],"r");
	$destination=fopen('imagensReprocessadas/'.$_POST['nome'],"w");
	stream_copy_to_stream($source,$destination);
	fclose($source);
	fclose($destination);

	$imgpath = "imagensBaixadas/".$_POST['nome'];
	unlink($imgpath);

}


?>