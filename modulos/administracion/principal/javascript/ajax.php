<?php

switch($_GET['mode'])
{
	case 'eg1':
			$data=$_POST['usercomment'];
			//now that you have data do whatever you want to do with it.
			echo $data; // so that users see the updated data and know that changes have been saved.
			break;
	case 'eg2':
			$data=$_POST['usercomment2'];
			echo $data;
			break;

}
