<?php

	$account = "3464"; // Insert your account name here

	$password = "johfb42q"; // Insert your password here

	

	$URL = "http://ws1.postcodesoftware.co.uk/lookup.asmx/getAddress?account=" . $account . "&password=" . $password . "&postcode=" . $_POST["postcode"];

		

	$xml = simplexml_load_file(str_replace(' ','', $URL)); // Removes unnecessary spaces

	

	If ($xml->ErrorNumber <> 0) // If an error has occured show message

	{

		print "<span class=\"text\">" . $xml->ErrorMessage . "</span>";

		echo "<p><span class=\"h3\">Postcode&nbsp;</span> <input class=\"text\" name=\"postcode\" id=\"postcode\" type=\"text\">\n";

		echo "<input class=\"button\" value=\"Find\" type=\"submit\" onclick='JavaScript:xmlhttpPost(\"result.php\")'>\n";

	}

	else

	{

  

    print "<table>\n<tr>\n<td class=\"h3\">Address</td>\n";



		if ($xml->PremiseData <> "") 

		{

	

		    $chunks = explode (";", $xml->PremiseData); // Splits up premise data

		

		    print "<td><select class=\"text\" name=\"address\" style=\"width:300px\">\n";

		    foreach ($chunks as $v) // Adds premises to combo box

		

		    {

			

			    if ($v <> "")

			

			    {

				    list($organisation, $building , $number) = explode ('|', $v); // Splits premises into organisation, building and number

				    echo "<option>";

				    if ($organisation <> "")echo $organisation . ", ";

				    if ($building <> "")echo  str_replace("/",", ",$building) . ", ";

				    if ($number <> "")echo $number . " ";

				    print $xml->Address1;

				    print "</option>\n";		

			    }

		

		    }

		

		    print "</select>\n</td>\n</tr>";



		}

		else {



			echo "<td><input class=\"text\" style=\"width:300px\" type=\"text\" value=\"$xml->Address1\"></td>\n";

			echo "</tr>\n";

			

		}

		

		If ($xml->Address2<> "") 

		{

			echo "<tr>\n";

			echo "<td></td><td><input class=\"text\" style=\"width:300px\" type=\"text\" value=\"$xml->Address2\"></td>\n";

			echo "</tr>\n";

		}

		

		If ($xml->Address3 <> "") 

		{

			echo "<tr>\n";

			echo "<td></td><td><input class=\"text\" style=\"width:300px\" type=\"text\" value=\"$xml->Address3\"></td>\n";

			echo "</tr>\n";

		}

		If ($xml->Address4 <> "") 

		{

			echo "<tr>\n";

			echo "<td></td><td><input class=\"text\" style=\"width:300px\" type=\"text\" value=\"$xml->Address4\"></td>\n";

			echo "</tr>\n";

		}

		

		echo "<tr>\n";

		echo "<td class=\"h3\">Town</td><td><input class=\"text\" style=\"width:300px\" type=\"text\" value=\"$xml->Town\"></td>\n";

		echo "</tr>\n";

		echo "<tr>\n";

		echo "<td class=\"h3\">County</td><td><input class=\"text\" style=\"width:300px\" type=\"text\" value=\"$xml->County\"></td>\n";

		echo "</tr>\n";

		echo "<tr>\n";

		echo "<td class=\"h3\">Postcode</td><td><input class=\"text\" style=\"width:250px\" id=\"postcode\" type=\"text\"  value=\"$xml->Postcode\">&nbsp;<input class=\"button\" value=\"Find\" type=\"submit\" onclick='JavaScript:xmlhttpPost(\"result.php\")'></td>\n";

		echo "</tr>\n";

		echo "</table>";	

	

	}		

	





?>