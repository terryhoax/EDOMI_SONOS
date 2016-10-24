###[DEF]###
[name		= SONOS Baustein (v2.8)	]

[e#1	TRIGGER		= Starten	#init=1]
[e#2	IMPORTANT		= ipAddr	#init=192.168.0.214 			]	
[e#3	TRIGGER		= Play		]	
[e#4	TRIGGER		= Stop		]	
[e#5	TRIGGER		= Pause		]	
[e#6	TRIGGER		= Volume	]	
[e#7	TRIGGER		= PlayMode	]
[e#8	TRIGGER		= Mute		]
[e#9		=Radio KO	]
[e#10		=Playliste	]
[e#11		=Next	]
[e#12		=Previous	]
[e#13		=Rewind	]
[e#14		=Loudness	]
[e#15		=Bass	]
[e#16		=Treble	]
[e#17		=Crossfade	]
[e#18		=Radionamen ďż˝ber Namen	]
[e#19		=MP3	]
[e#20		=MP3 Volume	#init=0	]
[e#21		=LogLevel	#init=0]
[e#21		=Trace	#init=1]
[e#22	TRIGGER		=Volume up 	]
[e#23	TRIGGER		=Volume down]
[e#24		=Volume Step 	#init=3]




[a#1		= Status	 ]	// 1 = Play, 2 = Pause, 3 = Stopp
[a#2		= Volume ]
[a#3		= Radiosender ]
[a#4		= Titel ]
[a#5		= Artist ]
[a#6		= Album ]
[a#7		= url Cover ]
[a#8		= Duration ]
[a#9		= RelTime ]
[a#10		= leer ]
[a#11		= Loudness ]
[a#12		= Bass ]
[a#13		= Treble ]
[a#14		= Mute ]
[a#15		= PlayMode ]
[a#16		= Crossfade]
[a#17		= CurrentURI]
[a#18		= TRIGGER Status]

[v#1		= 0	] //LBS Exec
[v#2		= 0	] // frei
[v#3		= 0	] //Play
[v#4		= 0	] //Stop
[v#5		= 0	] //Pause
[v#6		= 0	] //Stop
[v#7		= 0	] //Playmode
[v#8		= 0	] //Mute
[v#9		= 0	] //Play Radio von uri
[v#10		= 0	] //Play Playliste von uri
[v#11		= 0	] //Next
[v#12		= 0	] //Previous
[v#13		= 0	] //Rewind
[v#14		= 0	] //Loudness
[v#15		= 0	] //Bass
[v#16		= 0	] //Treble
[v#17		= 0	] //Crossfade
[v#18		= 0	] //Radio ďż˝ber Namen
[v#19		= 0	] //MP3 abspielen
[v#20		= 0	] //Vergleicher AlbumArt Playliste
[v#21		= 0	] //Vergleicher Radiosender
[v#22		= 0 ] //Lautstďż˝rke um Faktor E#22 erhďż˝hen
[v#23		= 0 ] //Lautstďż˝rke um Faktor E#22 veringern
[v#24		= 0 ] //Faktor fďż˝r die Lautstďż˝rkeverďż˝nderung


[v#100		= 0	] //Titel
[v#101		= 0	]	//streamContent
[v#102		= 0	]
[v#103		= 0	]	//Volume
[v#104		= 0	]	//Loudness
[v#105		= 0	]	//Bass
[v#106		= 0	]
[v#107		= 0	]	//Mute
[v#108		= 0	]	//Playmode
[v#109		= 0	]	//Crossfade
[v#110		= 0	]
[v#111		= 0	]	//Titel
[v#112		= 0	]	//Artist
[v#113		= 0	]	//Album
[v#114		= 0	]
[v#115		= 0	]	//TrackDuration
[v#116		= 0	]	//RelTime
[v#117		= 0	]
[v#118		= 0	]
[v#119		= 0	]
[v#120		= 0	]
[v#121		= 0	]

[v#130		= 0	]
[v#131		= 0	]

[v#200	= 2.8 ]
[v#201	= 19000027 ]
[v#202	= SONOS Baustein ]
[v#203	= 0 ] 

###[/DEF]###


###[HELP]###
<h3>PHP Classen</h3>
Die Datei sonosAccess.php wird benďż˝tigt. Datei ist im Download dabei.
Diese muss im Ordner /usr/local/edomi/main/include/php liegen.
Die Classen stammen von https://github.com/tkugelberg/SymconSonos. Aber bitte nicht genau diese Verwenden
von der Webseite. Hab Sie ein wenig angepasst!

<h2>Ein- und Ausgďż˝nge</h2>
<h3>Eingďż˝ne</h3>
E1: Signal 1 = Startet Baustein (Dďż˝mon)
E2: KO fďż˝r iP Adesse vom Sonos Gerďż˝t
E3: KO fďż˝r Play (wird bei 1 ausgelďż˝st)
E4: KO fďż˝r Stop (wird bei 1 ausgelďż˝st)
E5: KO fďż˝r Pause (wird bei 1 ausgelďż˝st)
E6: KO fďż˝r Volume (0 bis 50)
E7: KO fďż˝r Playmode (0= "NORMAL", 1= "REPEAT_ALL", 2= "REPEAT_ONE", 3= "SHUFFLE_NOREPEAT", 4= "SHUFFLE", 5= "SHUFFLE_REPEAT_ONE")
E8: KO fďż˝r Mute (0=Off / 1=On)
E9: KO fďż˝r Wahl Radiosender  (String per KO ďż˝bergeben! == z.B. "x-sonosapi-stream:s68934?sid=254&flags=8224&sn=0")
E10: KO fďż˝r Playlist (String per KO ďż˝bergeben! == z.B. "x-rincon-queue:RINCON_B8E9378E827A01400#0")
E11: KO fďż˝r Next (wird bei 1 ausgelďż˝st)
E12: KO fďż˝r Previous (wird bei 1 ausgelďż˝st)
E13: KO fďż˝r Rewind (wird bei 1 ausgelďż˝st)
E14: KO fďż˝r Loudness (0= Off / 1=On)
E15: KO fďż˝r Bass (-10 bis 10)
E16: KO fďż˝r Treble (-10 bis 10)
E17: KO fďż˝r Crossfade (0= Off / 1=On)
E18: KO fďż˝r Radisender ďż˝ber Namen die in der sonos.Access.php gespeichert sind. (z.B als KO "Bayern 3")
E19: KO fďż˝r MP3 file (kann als "http://www.sounds.com/blubb.mp3" oder ďż˝ber einen Share "//mp3/sonos/bla.mp3" ďż˝bergeben werden)
E20: KO fďż˝r MP3 Lautstďż˝rke (Hier kann die Lautstďż˝rke fďż˝r das Abspielen vom MP3 File ďż˝bergeben werde)
E21: Logging aktivieren/deaktivieren
E22: Lautstďż˝rke um den Wert aus E24 erhďż˝hen (wird bei 1 ausgelďż˝st)
E23: Lautstďż˝rke um den Wert aus E24 veringern (wird bei 1 ausgelďż˝st)
E24: Faktor fďż˝r die Lautstďż˝rkeanpassung (Standard=1)
	
<h3>Ausgďż˝nge</h3>	
A1: Status Playmode (1 = Play, 2 = Pause, 3 = Stopp)
A2: Status Volume (0 bis 50)
A3: Hier wird der Radiosender als Text ausgegeben
A4: Titel 
A5: Artist
A6: Album
A7: url fďż˝r Cover 
A8: Duration
A9: RelTime
A10: leer
A11: Status Loudness (0= Off / 1=On)
A12: Status Bass (-10 bis 10)
A13: Status Treble (-10 bis 10)
A14: Status Mute (0=Off / 1=On)
A15: Status Playmode (0= "NORMAL", 1= "REPEAT_ALL", 2= "REPEAT_ONE", 3= "SHUFFLE_NOREPEAT", 4= "SHUFFLE", 5= "SHUFFLE_REPEAT_ONE")
A16: Status Crossfade (0= Off / 1=On)
A17: CurrentURI Einfach an der Sonos App Radiosender oder Playliste auswďż˝hlen. (dieser URI kann als Playliste oder Radiosender in ein KO kopiert werden und dann an E9 oder E10 ďż˝bergeben werden.  Wir nur im Debug ausgegeben!)

<h3>Changelog:</h3>
v1.5: Initial version	
v2.0: Performance und Code Anpassung
v2.2: Send by change bei den Ausgďż˝ngen
v2.5: Send by change bei den A1 Ausgang angepasst
v2.8: Diverse Anpassungen, LogLevel geďż˝ndert.

###[/HELP]###


###[LBS]###
<?
function LB_LBSID($id) {
	if ($E=logic_getInputs($id)) {

		if ($E[1]['value']!=0 && $E[1]['refresh']==1) {
			
			//dieses Konstrukt stellt sicher, dass das EXEC-Script nur einmal gestartet wird
			//----------------------------------------------------------
			if (logic_getVar($id,1)!=1) {    
				logic_setVar($id,1,1);
				$stat=logic_getVar($id,1);
				logic_setOutput($id,18,$stat);
				logic_callExec(LBSID,$id);
			}	
		}
		
		//Play
		//----------------------------------------------------------
		if ($E[3]['refresh']==1 && $E[3]['value']==1 ){
			logic_setVar($id,3,1);
		}
		
		//Stop
		//----------------------------------------------------------
		if ($E[4]['refresh']==1 && $E[4]['value']==1 ){
			logic_setVar($id,4,1);
		}
		
		//Pause
		//----------------------------------------------------------
		if ($E[5]['refresh']==1 && $E[5]['value']==1 ){
			logic_setVar($id,5,1);
		}
		
		//Volume
		//----------------------------------------------------------
		if ($E[6]['refresh']==1 ){
			logic_setVar($id,6,1);
		}
		
		//Playmode
		//----------------------------------------------------------
		if ($E[7]['refresh']==1){
			logic_setVar($id,7,1);
		}
		
		//Mute
		//----------------------------------------------------------
		if ($E[8]['refresh']==1){
			logic_setVar($id,8,1);
		}
		
		//Play Radio von uri
		//----------------------------------------------------------
		if ($E[9]['refresh']==1){
			logic_setVar($id,9,1);
		}
		
		//Play Playliste von uri
		//----------------------------------------------------------
		if ($E[10]['refresh']==1){
			logic_setVar($id,10,1);
		}
		
		//Next
		//----------------------------------------------------------
		if ($E[11]['refresh']==1 && $E[11]['value']==1 ){
			logic_setVar($id,11,1);
		}
		
		//Previous
		//----------------------------------------------------------
		if ($E[12]['refresh']==1 && $E[12]['value']==1 ){
			logic_setVar($id,12,1);
		}
		
		//Rewind
		//----------------------------------------------------------
		if ($E[13]['refresh']==1 && $E[13]['value']==1 ){
			logic_setVar($id,13,1);
		}
		
		//Loudness
		//----------------------------------------------------------
		if ($E[14]['refresh']==1){
			logic_setVar($id,14,1);
		}
		
		//Bass
		//----------------------------------------------------------
		if ($E[15]['refresh']==1){
				logic_setVar($id,15,1);
		}
		
		//Treble
		//----------------------------------------------------------
		if ($E[16]['refresh']==1){
			logic_setVar($id,16,1);
		}
		
		//Crossfade
		//----------------------------------------------------------
		if ($E[17]['refresh']==1){
			logic_setVar($id,17,1);
		}
		
		//Radio ďż˝ber Namen
		//----------------------------------------------------------
		if ($E[18]['refresh']==1){
			logic_setVar($id,18,1);
		}
		
		//MP3 abspielen
		//----------------------------------------------------------
		if ($E[19]['refresh']==1){
			logic_setVar($id,19,1);
		}

		//Volume up/down setzen
		//----------------------------------------------------------
		if ($E[22]['refresh']==1 && $E[22]['value']==1){ //Volume up
			logic_setVar($id,22,1);
		}
		if ($E[23]['refresh']==1 && $E[23]['value']==1){ //Volume down
			logic_setVar($id,23,1);
		}
	}
}
?>
###[/LBS]###


###[EXEC]###
<?
require(dirname(__FILE__)."/../../../../main/include/php/incl_lbsexec.php");
require(dirname(__FILE__)."/../../../../main/include/php/sonosAccess.php");

set_time_limit(0);
sql_connect();
restore_error_handler();
error_reporting(0);
ini_set('default_socket_timeout', 600);


function LB_LBSID_Debug($id,$message,$LBS_loglevel,$USER_loglevel, $exit = FALSE) {
$E=logic_getInputs($id);
	$version = logic_getVar($id,200);
	$logName = logic_getVar($id,202);
	$a = array("LOG_EMERG","LOG_ALERT","LOG_CRIT","LOG_ERR","LOG_WARNING","LOG_NOTICE","LOG_INFO","LOG_DEBUG");
	$LBS_loglevel<=$USER_loglevel && writeToCustomLog("LBSLBSID" . " - \t[$logName]",$LBS_loglevel,"(ID$id) - " . " " .$a[$LBS_loglevel].": ".$message."\t[v$version]");
	if($LBS_loglevel<=3){
	logic_setOutput($id,28,strftime("%A. %X %Z - %Y. %B %d.").$message);   
	}
	if($exit) {
		writeToCustomLog("LBSLBSID" . " - \t[$logName]",$LBS_loglevel,"(ID$id) ".$a[$LBS_loglevel].": LBS wird beendet."."\t[v$version]");
		LB_LBSID_Finish($id);
	}
}



global $ip;
global $setvol;
global $station;

if ($E=logic_getInputs($id)) {
//	logic_setVar($id,203,$E[21]['value']); //set loglevel to #VAR 203
	$ip = $E[2]['value'];
	$user_log_level = $E[21]['value'];
	$V=logic_getVars($id);
		LB_LBSID_Debug($id, "SONOS EXEC -> gestartet \n",6, $user_log_level, FALSE);
}

//SONOS Erreichbarkeit ďż˝berprďż˝fen
//----------------------------------------------------------
try
	{
    	exec("ping -c 2 $ip", $array, $return);
		LB_LBSID_Debug($id, "SONSO -> Bing erreichbar: " .$ip."\n",6, $user_log_level, FALSE);
	} 
	catch (Exception $e)
	{
    	
		LB_LBSID_Debug($id, "SONOS -> Bing nicht erreichbar: $ip" .$e->getMessage()."\n",3, $user_log_level, TRUE);
		LB_LBSID_Finish($id);
	}

	

//SONOS starten while schleife
//----------------------------------------------------------
try
	{
$sonos = new SonosAccess($ip);



while(getSysInfo(1)>=1) {	

	if ($E=logic_getInputs($id)){
		$V=logic_getVars($id);
  	}
		
	$transportInfo=$sonos->GetTransportInfo();

		//Play
		//----------------------------------------------------------
		if ($V[3]==1) {
			$sonos->Play();
				LB_LBSID_Debug($id, "SONOS : ->  Set Play : " .$ip."\n",6, $user_log_level, FALSE);			
			logic_setVar($id,3,0);
		}
		
		//Stop
		//----------------------------------------------------------		
		if ($V[4]==1) {
			$sonos->Stop();
			LB_LBSID_Debug($id, "SONOS : ->  Set Stop : " .$ip."\n",6, $user_log_level, FALSE);	
			logic_setVar($id,4,0);
		}
		
		//Pause
		//----------------------------------------------------------	
		if ($V[5]==1) {
			$sonos->Pause();
				LB_LBSID_Debug($id, "SONOS : ->  Set Pause : " .$ip."\n",6, $user_log_level, FALSE);		
			logic_setVar($id,5,0);
		}
		
		//Volume
		//----------------------------------------------------------	
		if ($V[6]==1) {
			$E=logic_getInputs($id);
			$setvol=$E[6]['value'];
			$sonos->SetVolume($setvol);
				LB_LBSID_Debug($id, "SONOS : ->  Set Volume : ".$setvol."\n",6, $user_log_level, FALSE);		
			usleep(500);
			$volume=$sonos-> GetVolume();
			logic_setOutput($id,2,$volume);
			logic_setVar($id,6,0);
		}
		
		//Playmode (0= "NORMAL", 1= "REPEAT_ALL", 2= "REPEAT_ONE", 3= "SHUFFLE_NOREPEAT", 4= "SHUFFLE", 5= "SHUFFLE_REPEAT_ONE")
		//----------------------------------------------------------
		if ($V[7]==1) {
			$E=logic_getInputs($id);
			$sonos->SetPlayMode(round($E[7]['value']));
			$playmode = $sonos->GetTransportSettings();
				LB_LBSID_Debug($id, "SONOS : ->  Set Playmode : ".$playmode."\n",6, $user_log_level, FALSE);
			logic_setVar($id,7,0);
		}
		
		//Mute
		//----------------------------------------------------------
		if ($V[8]==1) {
			$E=logic_getInputs($id);
			$sonos->SetMute($E[8]['value']);
			$mute = $sonos->GetMute();
				LB_LBSID_Debug($id, "SONOS : ->  Set Mute : ".$mutel."\n",6, $user_log_level, FALSE);		
			logic_setVar($id,8,0);
		}
		
		//Play Radio per uri
		//----------------------------------------------------------
		if ($V[9]==1) {
			$E=logic_getInputs($id);
			$uri=$E[9]['value'];
				LB_LBSID_Debug($id, "SONOS : ->  Set RadioStation uri : ".$uri."\n",6, $user_log_level, FALSE);	
			$sonos->SetRadio($uri);
			usleep(500);	
			$sonos->Play();
				LB_LBSID_Debug($id, "SONOS : ->  Set RadioStation : ".$station."\n",6, $user_log_level, FALSE);				
			logic_setVar($id,9,0);
		}
		
		//Playliste per uri
		//----------------------------------------------------------
		if ($V[10]==1) { 
			$zoneinfo=$sonos->GetZoneGroupAttributes();
			$sonos->ClearQueue();
			$E=logic_getInputs($id);
			usleep(500);	
			$uri=$E[10]['value'];
				LB_LBSID_Debug($id, "SONOS : ->  Set Playlist uri : ".$uri."\n",6, $user_log_level, FALSE);
			$sonos->AddToQueue($uri);
			$sonos->SetAVTransportURI('x-rincon-queue:'.$zoneinfo['CurrentZonePlayerUUIDsInGroup'].'#0');
			$sonos->Play();
			logic_setVar($id,10,0);
		}
		
		//Next
		//----------------------------------------------------------
		if ($V[11]==1) {
			$sonos->Next();
				LB_LBSID_Debug($id, "SONOS : ->  Set Next : "."\n",6, $user_log_level, FALSE);
			logic_setVar($id,11,0);
		}
		if ($V[12]==1) {		//Previous
			$sonos->Previous();
					_log('SONOS : ->  Set Previous');
			logic_setVar($id,12,0);
		}
		if ($V[13]==1) {		//Rewind
			$sonos->Rewind();
					_log('SONOS : ->  Set Rewind');
			logic_setVar($id,13,0);
		}
		if ($V[14]==1) {	 //Loudness
			$E=logic_getInputs($id);
			$sonos->SetLoudness($E[14]['value']);
			$loudness = $sonos->GetLoudness();
					_log('SONOS : ->  Set Loudness: ($loudness)');
			logic_setVar($id,14,0);
		}
		
		//Bass
		//----------------------------------------------------------
		if ($V[15]==1) {
			$E=logic_getInputs($id);
			$sonos->SetBass($E[15]['value']);
			$bass = $sonos->GetBass();
				LB_LBSID_Debug($id, "SONOS : ->  Set Bass : ".$bass."\n",6, $user_log_level, FALSE);
			logic_setVar($id,15,0);
		}
		
		//Treble
		//----------------------------------------------------------
		if ($V[16]==1) {
			$E=logic_getInputs($id);
			$sonos->SetTreble($E[16]['value']);
			$treble = $sonos->GetTreble();
				LB_LBSID_Debug($id, "SONOS : ->  Set Treble : ".$treble."\n",6, $user_log_level, FALSE);
			logic_setVar($id,16,0);
		}
		
		//Crossfade
		//----------------------------------------------------------
		if ($V[17]==1) {
			$E=logic_getInputs($id); 
			$sonos->SetCrossfade($E[17]['value']);
			$crossfade = $sonos->GetCrossfade();
				LB_LBSID_Debug($id, "SONOS : ->  Set Crossfade : ".$crossfade."\n",6, $user_log_level, FALSE);
			logic_setVar($id,17,0);
		}
		
		//Radio ďż˝ber Namen abspielen
		//----------------------------------------------------------
		if ($V[18]==1) { 
			$E=logic_getInputs($id); 
			$RadioStations=$sonos->get_available_stations();
			$name=$E[18]['value'];
			$Radio = $sonos->get_station_url($name, $RadioStations);
			$sonos->SetRadio($Radio['url'], $Radio['name']);
			$sonos->Play();
				LB_LBSID_Debug($id, "SONOS : ->  Set Radio ďż˝ber Namen : ".$name."\n",6, $user_log_level, FALSE);
			logic_setVar($id,18,0);
		}
		
		//MP3 abspielen
		//----------------------------------------------------------
		if ($V[19]==1) {
			$E=logic_getInputs($id); 
			$sonos->PlayFiles(Array($E[19]['value']), round($E[20]['value']));
				LB_LBSID_Debug($id, "SONOS : ->  Set MP3 : ".$E[19]['value']."\n",6, $user_log_level, FALSE);
			logic_setVar($id,19,0);
		}
		if ($V[22]==1) {//Volume up
			$E=getLogicEingangDataAll($id);
			$volume=$sonos-> GetVolume();
			$setvol=($volume + $E[24]['value']);
			$sonos->SetVolume($setvol);
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Volume up(".$E[24]['value']."): ".($volume)." -> ".($setvol));}
			//20161014 kr:
			//usleep(500);
			//$volume=$sonos-> GetVolume();
			//damit keine Verzďż˝gerung beim mehrfachen Erhďż˝hen der LS entsteht, wird der berechnete Weert auf den Ausgang geschrieben.
			setLogicLinkAusgang($id,2,$setvol);
			setLogicElementVar($id,22,0);
		}
		if ($V[23]==1) {//Volume down
			$E=getLogicEingangDataAll($id);
			$volume=$sonos-> GetVolume();
			$setvol=($volume - $E[24]['value']);
			$sonos->SetVolume($setvol);
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Volume down(".$E[24]['value']."): ".($volume)." -> ".($setvol));}
			//20161014 kr:
			//usleep(500);
			//$volume=$sonos-> GetVolume();
			//damit keine Verzďż˝gerung beim mehrfachen Erhďż˝hen der LS entsteht, wird der berechnete Weert auf den Ausgang geschrieben.
			setLogicLinkAusgang($id,2,$setvol);
			setLogicElementVar($id,23,0);
		}
				

//$transportInfo=$sonos->GetTransportInfo();
	if ($transportInfo == 1) {
		$mediaInfo=$sonos->GetMediaInfo();
		$positionInfo=$sonos->GetPositionInfo();
		$volume=$sonos-> GetVolume();
		$mute = $sonos->GetMute();
		$bass = $sonos->GetBass();
		$treble = $sonos->GetTreble();
		$loudness = $sonos->GetLoudness();
		$playmode = $sonos->GetTransportSettings();
		$crossfade = $sonos->GetCrossfade();
		$zoneinfo=$sonos->GetZoneGroupAttributes();
	}
	
	
	if (!empty($mediaInfo['title'])) {
			
		if ($V[118] != 1 && $transportInfo == 1) {
			logic_setVar($id,118,1);
			logic_setOutput($id,5," ");		
			logic_setVar($id,120,$transportInfo);
		}
			
		if ($V[119] != 1 && $transportInfo == 1) {
			logic_setVar($id,119,1);
			logic_setOutput($id,6," ");
		}
							
		if ($mediaInfo['title']!==$V[100] && $transportInfo == 1 ) { 
			logic_setVar($id,100,$mediaInfo['title']);
			logic_setOutput($id,3,$mediaInfo['title']);
//			logic_setVar($id,110,$transportInfo);
		}
			
		if ($positionInfo["streamContent"]!==$V[101] && $transportInfo == 1) { 
			logic_setVar($id,101,$positionInfo["streamContent"]);
			$teiler = "-";
			$n=explode($teiler,$positionInfo["streamContent"],11);
//			$t=4;
			for ($t=0;$t<count($n);$t++) {
				logic_setOutput($id,(5),$n[0]);
				logic_setOutput($id,(4),$n[1]);
		}
		
		
				
			
//			logic_setOutput($id,4,$positionInfo["streamContent"]);
				LB_LBSID_Debug($id, "SONOS : ->  Radio Artist - Titel: " .$positionInfo['streamContent']."\n",6, $user_log_level, FALSE);
		}
			
		$station=preg_replace("#(.*)x-sonosapi-stream:(.*?)\?sid(.*)#is",'$2',$mediaInfo['CurrentURI']);
			if ($station!==logic_getVar($id,21) && $transportInfo==1) { 
				$serial = substr($zoneinfo['CurrentZoneGroupID'] , 7,12);
				$sender = preg_replace('#(.*)<outline type="text" text="(.*?)\" guide_id(.*)#is','$2',@file_get_contents("http://opml.radiotime.com/Describe.ashx?c=nowplaying&id=".$station."&partnerId=IAeIhU42&serial=".$serial));
					LB_LBSID_Debug($id, "SONOS : ->  Radio Station: " .$station."\n",6, $user_log_level, FALSE);
				$logo = preg_replace('#(.*)image="(.*?)\"(.*)#is','$2',@file_get_contents("http://opml.radiotime.com/Describe.ashx?c=nowplaying&id=".$station."&partnerId=IAeIhU42&serial=".$serial));
					LB_LBSID_Debug($id, "SONOS : ->  Radio Logo link: " .$logo."\n",6, $user_log_level, FALSE);
				logic_setVar($id,21,$station);
				logic_setOutput($id,7,$logo);
			}		
	}
	else{
		if ($positionInfo['title']!=logic_getVar($id,111)  && $transportInfo == 1){
			logic_setVar($id,111,$positionInfo['title']); 
			logic_setOutput($id,4,$positionInfo['title']);
			logic_setOutput($id,3," ");
			logic_setVar($id,120,$transportInfo);
		}
		
		if ($positionInfo['artist']!=logic_getVar($id,112) && $transportInfo == 1 ){
			logic_setVar($id,112,$positionInfo['artist']);
			logic_setVar($id,118,0);
			logic_setVar($id,100,0);
			logic_setOutput($id,5,$positionInfo['artist']);
		}
		
		if ($positionInfo["album"]!=logic_getVar($id,113) && $transportInfo == 1){
			logic_setVar($id,113,$positionInfo["album"]);
			logic_setVar($id,119,0);
			logic_setOutput($id,6,$positionInfo["album"]);
		}
		
		if ($positionInfo["albumArtURI"]!=logic_getVar($id,20) && $transportInfo == 1){ 
			logic_setVar($id,20,$positionInfo["albumArtURI"]);
			logic_setVar($id,21,0);
			logic_setOutput($id,7,$positionInfo["albumArtURI"]);
		}
		
		if ($positionInfo["TrackDuration"]!=logic_getVar($id,115) && $transportInfo == 1){ 
			logic_setVar($id,115,$positionInfo["TrackDuration"]);
			logic_setOutput($id,8,$positionInfo["TrackDuration"]);
		}
		
		if ($positionInfo["RelTime"]!=logic_getVar($id,116) && $transportInfo == 1){ 
			logic_setVar($id,116,$positionInfo["RelTime"]);
			logic_setOutput($id,9,$positionInfo["RelTime"]);
		}
		
	}



		if ($transportInfo!=$V[121] ) { 
			logic_setVar($id,121,$transportInfo);
			logic_setOutput($id,1,$transportInfo);
		}
		
		if ($volume!=logic_getVar($id,103) ) {
			logic_setVar($id,103,$volume); 
			logic_setOutput($id,2,$volume);
		}
		
		if ($loudness!==logic_getVar($id,104) ) {
			logic_setVar($id,104,$loudness); 
			logic_setOutput($id,11,$loudness);
		}
		
		if ($bass != $V[105] && $transportInfo == 1 ) {
			logic_setVar($id,105,$bass); 
			logic_setOutput($id,12,$bass);
		}
		
		if ($treble != $V[130] && $transportInfo == 1) {
			logic_setVar($id,130,$treble); 
			logic_setOutput($id,13,$treble);
		}
		
		if ($mute!=logic_getVar($id,107) ) {
			logic_setVar($id,107,$mute); 
			logic_setOutput($id,14,$mute);
		}
		
		if ($playmode!=logic_getVar($id,108) ) {
			logic_setVar($id,108,$playmode);
			logic_setOutput($id,15,$playmode);
		}
		
		if ($crossfade!==logic_getVar($id,109) ) {
			logic_setVar($id,109,$crossfade);
			logic_setOutput($id,16,$crossfade);
		}

	if ($transportInfo == 3 && $transportInfo!=logic_getVar($id,120) ) {
		logic_setVar($id,120,$transportInfo);
		logic_setVar($id,118,0);
		logic_setVar($id,119,0);
		logic_setVar($id,100,0);
		logic_setOutput($id,3," ");
		logic_setOutput($id,4," ");
		logic_setOutput($id,5," ");
		logic_setOutput($id,6," ");
		logic_setOutput($id,7,"-");
		logic_setOutput($id,8," ");
		logic_setOutput($id,9," ");
		logic_setOutput($id,11," ");
		logic_setOutput($id,12," ");
		logic_setOutput($id,13," ");
		logic_setOutput($id,17," ");
		logic_setVar($id,21,0);
	}

	usleep(2000000);		

	}
}
catch (Exception $e)
	{
    	LB_LBSID_Debug($id, "SONOS -> Fehler: $ip ".$e->getMessage()."\n",3, $user_log_level, TRUE);
		LB_LBSID_Finish($id);
	}
sql_disconnect();


function LB_LBSID_Finish($id) {
	logic_setVar($id,1,0);
	$stat=logic_getVar($id,1);
	logic_setOutput($id,18,$stat);
	LB_LBSID_Debug($id, "SONOS EXEC -> wurde beendet \n",6, $user_log_level, FALSE);
	sql_disconnect();
	exit();
}

?>
###[/EXEC]###