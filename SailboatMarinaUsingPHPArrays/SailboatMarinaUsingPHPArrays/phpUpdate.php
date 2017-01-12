<?php
	// Function to check if the values of the arguments are "EMPTY"
	function checkEmpty($toCheck){
		if(!strcasecmp($toCheck,"EMPTY"))
		{
			return 0;
		}
		else
		{
        return 1;
		}
	}  
  
	// Retrieve values from the query strings
	$ARG[0] = $_REQUEST["SlipNo"];
	$ARG[1] = $_REQUEST["Type"];
	$ARG[2] = $_REQUEST["Year"];
	$ARG[3] = $_REQUEST["Length"];
	$ARG[4] = $_REQUEST["Paid"];
	$ARG[5] = $_REQUEST["Motor"];
	$ARG[6] = $_REQUEST["Fname"];
	$ARG[7] = $_REQUEST["Lname"];
  
	// Call the empty method and check if we need to query or insert/update   
	for($x = 0; $x < 8; $x++) {
      $ret_value = checkEmpty($ARG[$x]);
      if($ret_value == 0){
          break;
      }
	}
	
	// Get the values from the file into 2D array
	$count = 0;
	$handle = fopen("inputFile.txt", "r");
	if ($handle) {
		while (($line = fgets($handle)) !== false) {
			$input = explode("|", $line);
			for($a = 0 ; $a < count($input); $a++)
			{
				$parts=explode(" ", $input[$a]);
				for($x = 0; $x < 8; $x++)
				{
					$data[$count][$x] = $parts[$x];
				}
				$count++;
			}
		}
		fclose($handle);
	} else {
		print "Prestored file not found";
	} 
  
	// Querying: print all data in 2D array
	if($ret_value == 0)
	{
		print "Querying: <br>";
		//Check with the values provided through the form and set the flag to true if search criteria matches
		for($y = 0; $y < $count; $y++){
			$flag1[$y]=1;
			for($x = 0; $x < 8; $x++)
			{
				if($ARG[$x] != "EMPTY")
				{
					if($ARG[$x]!=$data[$y][$x])
						$flag1[$y]=0;
				}
				
			}			
		}
		//Print the values if the flag is true
		for($y = 0; $y < $count; $y++){
			if($flag1[$y]==1)
			{
				for($x = 0; $x < 8; $x++){
					print $data[$y][$x]." ";
				}
				print "<br>";
			}
		}
	}
	else
	{
		//To check if it is update or insert
		$flag =0;
		for($y = 0; $y < $count; $y++){
			if($data[$y][0] == $ARG[0])
			{
				$flag = 1;
				break;
			}		
	}
    if ($flag == 1) {
		print "Updated";
		$handle = fopen("inputFile.txt", "w");
		//Update the row
		for($z=0;$z < 8; $z++)
		{
		  $data[$y][$z]=$ARG[$z];
		}
		//Store the file with updated value
		for($y = 0; $y < $count ; $y++){
			for($x = 0; $x < 8; $x++)
			{
					fwrite($handle, $data[$y][$x]." ");
			}
			if($y != $count - 1)
				fwrite($handle,"|");
		}	
		fclose($handle);
	}
    else {
		print "Inserted";
		$handle = fopen("inputFile.txt", "w");
		//Insert the row
		for($z=0;$z < 8; $z++)
		{
		  $data[$count][$z]=$ARG[$z];
		}
		$count++;
		//Store the file with inserted value
		 for($y = 0; $y < $count ; $y++){
			for($x = 0; $x < 8; $x++)
			{
					fwrite($handle, $data[$y][$x]." ");
			}	
			if($y != $count - 1)
				fwrite($handle,"|");
		}	
		fclose($handle);
	
		}

  }
?>