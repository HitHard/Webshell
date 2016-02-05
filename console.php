<?php
	session_start();

	$exec_binary = "exec_cmd";
	
	if (isset($_POST["Reset"])) {
		unset($_SESSION["cmds"]);
		unset($_SESSION["dir"]);
	} else {
		// If no home is set, set it to current directory
		if (!isset($_SESSION["HOME"])){
			$_SESSION["HOME"] = exec("pwd");
		}
		
		// Force error display
		if(!isset($_SESSION["Error"])) {
			$_SESSION["Error"] = 1;
		}
		/*if(isset($_POST["Error"])) {
			if(isset($_SESSION["Error"])) {
				if($_SESSION["Error"] === 0) {
					$_SESSION["Error"] = 1;
				} else {
					$_SESSION["Error"] = 0;
				}
			} else {
				$_SESSION["Error"] = 1;
			}
		}*/
		
		if (!isset($_SESSION["dir"])){
			$_SESSION["dir"] = ".";
		}
		
		chdir($_SESSION["dir"]);
		if (isset($_POST["cmd"])) {
			$command = explode(" ", filter_input(INPUT_POST, "cmd"));
			if ($command[0] === "cd") {
				if (count($command) > 1){
					$_SESSION["dir"] = $command[1];
				}
				else {
					$_SESSION["dir"] = $_SESSION["HOME"];
				}
				$dirChanged = true;
				//$_SESSION["cmds"] = "<br />Directory changed to " . $_SESSION["dir"] . "<br />" . $_SESSION["cmds"];
				chdir($_SESSION["dir"]);
				//exit(0);
			}

			$command=filter_input(INPUT_POST, "cmd");
			if(isset($_SESSION["Error"]) && $_SESSION["Error"]===1) {
				$command=$command.' 2>&1';
			}
			// binary_path <user> <command>
			$resultat = shell_exec($_SESSION["HOME"]."/".$exec_binary." ".$_SERVER['REMOTE_USER']." \"".$command."\"");
			//echo "./exec_cmd ".$_SERVER['REMOTE_USER']." \"".$command."\"";

			if (isset($_SESSION["cmds"])){
				if(isset($dirChanged) && $dirChanged == true) {
					$_SESSION["cmds"] = $_SERVER['REMOTE_USER']."\$ ".substr($command,0,-5)."<br />".
							    "Directory changed to " . $_SESSION["dir"] . "<br />".
							    str_replace(" ", "&nbsp;", htmlentities($resultat)) . "<br /><hr/>" . $_SESSION["cmds"];
				} else {
					$_SESSION["cmds"] = $_SERVER['REMOTE_USER']."\$ ".substr($command,0,-5)."<br />".
                                                            str_replace(" ", "&nbsp;", htmlentities($resultat)) . "<br /><hr/>" . $_SESSION["cmds"];
				}
			}
			else {
				if(isset($dirChanged) && $dirChanged == true) {
					$_SESSION["cmds"] = $_SERVER['REMOTE_USER']."\$ ".substr($command,0,-5)."<br />".
							    "Directory changed to " . $_SESSION["dir"] . "<br />".
							    str_replace(" ", "&nbsp;", htmlentities($resultat));
				} else {
					$_SESSION["cmds"] = $_SERVER['REMOTE_USER']."\$ ".substr($command,0,-5)."<br />".
                                                            str_replace(" ", "&nbsp;", htmlentities($resultat));
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body bgcolor="#000000" style="color:#19DA00">
	<div style="margin-top:20px; font-family: monospace; font-size:14px;">
		<?php
			//if(isset($_POST["cmd"]) && $command[0] == "cd")
				//echo("Directory changed to ".$command[1]);

			if(isset($_SESSION['cmds'])) {
				echo nl2br($_SESSION['cmds']);
			}
		?>
	</div>
</body>
</html>
