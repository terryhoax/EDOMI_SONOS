###[DEF]###
[name		= SONOS Baustein (v2.6)	]

[e#1	TRIGGER		= Starten	#init=1]
[e#2	IMPORTANT		= ipAddr	#init=192.168.0.15 			]	
[e#3	TRIGGER		= Play/Pause		]	
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
[e#18		=Radionamen über Namen	]
[e#19		=MP3	]
[e#20		=MP3 Volume	#init=0	]
[e#21		=Trace	#init=1]
[e#22	TRIGGER		=Volume up 	]
[e#23	TRIGGER		=Volume down]
[e#24		=Volume Step 	#init=1]



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

[v#1		= 2.5	] //Version
[v#2		= 0	] //LBS Exec
[v#3		= 0	] //Play/Pause
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
[v#18		= 0	] //Radio über Namen
[v#19		= 0	] //MP3 abspielen
[v#20		= 0	] //Vergleicher AlbumArt Playliste
[v#21		= 0	] //Vergleicher Radiosender
[v#22		= 0 ] //Lautstärke um Faktor E#22 erhöhen
[v#23		= 0 ] //Lautstärke um Faktor E#22 veringern
[v#24		= 0 ] //Faktor für die Lautstärkeveränderung


[v#100		= 0	]
[v#101		= 0	]
[v#102		= 0	]
[v#103		= 0	]
[v#104		= 0	]
[v#105		= 0	]
[v#106		= 0	]
[v#107		= 0	]
[v#108		= 0	]
[v#109		= 0	]
[v#110		= 0	]
[v#111		= 0	]
[v#112		= 0	]
[v#113		= 0	]
[v#114		= 0	]
[v#115		= 0	]
[v#116		= 0	]
[v#117		= 0	]
[v#118		= 0	]
[v#119		= 0	]
[v#120		= 0	]
[v#121		= 	]
[v#122		= 0 ]
[v#123		= 0 ]
[v#124		= 0 ]

###[/DEF]###


###[HELP]###
Die Datei sonosAccess.php wird benötigt. Datei ist im Download dabei.
Diese muss im Ordner /usr/local/edomi/main/include/php liegen.
Die Classen stammen von https://github.com/tkugelberg/SymconSonos. Aber bitte nicht genau diese Verwenden
von der Webseite. Hab Sie ein wenig angepasst!

Der SONOS Baustein wird automatisch gestartet, sobald EDOMI gestartet wird (E1 hat den Initialwert 1).
E1: Signal 1 = Startet Baustein (Dämon)
E2: KO für iP Adesse vom Sonos Gerät
E3: KO für Play (wird bei 1 ausgelöst, Pause bei 0)
E4: KO für Stop (wird bei 1 ausgelöst)
E5: KO für Pause (wird bei 1 ausgelöst)
E6: KO für Volume (0 bis 50)
E7: KO für Playmode (0= "NORMAL", 1= "REPEAT_ALL", 2= "REPEAT_ONE", 3= "SHUFFLE_NOREPEAT", 4= "SHUFFLE", 5= "SHUFFLE_REPEAT_ONE")
E8: KO für Mute (0=Off / 1=On)
E9: KO für Wahl Radiosender  (String per KO übergeben! == z.B. "x-sonosapi-stream:s68934?sid=254&flags=8224&sn=0")
E10: KO für Playlist (String per KO übergeben! == z.B. "x-rincon-queue:RINCON_B8E9378E827A01400#0")
E11: KO für Next (wird bei 1 ausgelöst)
E12: KO für Previous (wird bei 1 ausgelöst)
E13: KO für Rewind (wird bei 1 ausgelöst)
E14: KO für Loudness (0= Off / 1=On)
E15: KO für Bass (-10 bis 10)
E16: KO für Treble (-10 bis 10)
E17: KO für Crossfade (0= Off / 1=On)
E18: KO für Radisender über Namen die in der sonos.Access.php gespeichert sind. (z.B als KO "Bayern 3")
E19: KO für MP3 file (kann als "http://www.sounds.com/blubb.mp3" oder über einen Share "//mp3/sonos/bla.mp3" Übergeben werden)
E20: KO für MP3 Lautstärke (Hier kann die Lautstärke für das Abspielen vom MP3 File Übergeben werde)
E21: Logging aktivieren/deaktivieren
E22: Lautstärke um den Wert aus E24 erhöhen (wird bei 1 ausgelöst)
E23: Lautstärke um den Wert aus E24 veringern (wird bei 1 ausgelöst)
E24: Faktor für die Lautstärkeanpassung (Standard=1)
	
	
A1: Status Playmode (1 = Play, 2 = Pause, 3 = Stopp)
A2: Status Volume (0 bis 50)
A3: Hier wird der Radiosender als Text ausgegeben
A4: Titel 
A5: Artist
A6: Album
A7: url fÃ¼r Cover (Bei Radiosendern nur wenn der Sender über E18 Übergeben wird)
A8: Duration
A9: RelTime
A10: leer
A11: Status Loudness (0= Off / 1=On)
A12: Status Bass (-10 bis 10)
A13: Status Treble (-10 bis 10)
A14: Status Mute (0=Off / 1=On)
A15: Status Playmode (0= "NORMAL", 1= "REPEAT_ALL", 2= "REPEAT_ONE", 3= "SHUFFLE_NOREPEAT", 4= "SHUFFLE", 5= "SHUFFLE_REPEAT_ONE")
A16: Status Crossfade (0= Off / 1=On)
A17: CurrentURI Einfach an der Sonos App Radiosender oder Playliste auswÃ¤hlen. (dieser URI kann als Playliste oder Radiosender in ein KO kopiert werden und dann an E9 oder E10 Übergeben werden.  Wir nur im Debug ausgegeben!)

Changelog:
==========
v1.5: Initial version	
v2.0: Performance und Code Anpassung
v2.2: Send by change bei den Ausgängen
v2.5: Send by change bei den A1 Ausgang angepasst
v2.6: Play und Pause auf einen Eingang gelegt
v2.7: Lautstärke up/down hinzugefügt

###[/HELP]###


###[LBS]###
<?
function LB_LBSID($id) {
	if ($E=getLogicEingangDataAll($id)) {

		if ($E[1]['value']!=0 && $E[1]['refresh']==1) {
	
			if (getLogicElementVar($id,2)!=1) {    //dieses Konstrukt stellt sicher, dass das EXEC-Script nur einmal gestartet wird
				setLogicElementVar($id,2,1);
				callLogicFunctionExec(LBSID,$id);
			}	
		}
		if ($E[3]['refresh']==1 && ($E[3]['value']==1 || $E[3]['value']==0 )){ //Play/Pause
			setLogicElementVar($id,3,$E[3]['value']);
		}
		if ($E[4]['refresh']==1 && $E[4]['value']==1 ){ //Stop
			setLogicElementVar($id,4,1);
		}
		if ($E[5]['refresh']==1 && $E[5]['value']==1 ){ //Pause
			setLogicElementVar($id,5,1);
		}
		if ($E[6]['refresh']==1 ){ //Volume
			setLogicElementVar($id,6,1);
		}
		if ($E[7]['refresh']==1){//Playmode
			setLogicElementVar($id,7,1);
		}
		if ($E[8]['refresh']==1){
			setLogicElementVar($id,8,1);
		}
		if ($E[9]['refresh']==1){ //Play Radio von uri
			setLogicElementVar($id,9,1);
		}
		if ($E[10]['refresh']==1){ // Play Playliste von uri
			setLogicElementVar($id,10,1);
		}
		if ($E[11]['refresh']==1 && $E[11]['value']==1 ){ //Next
			setLogicElementVar($id,11,1);
		}
		if ($E[12]['refresh']==1 && $E[12]['value']==1 ){ //Previous
			setLogicElementVar($id,12,1);
		}
		if ($E[13]['refresh']==1 && $E[13]['value']==1 ){ //Rewind
			setLogicElementVar($id,13,1);
		}
		if ($E[14]['refresh']==1){ //Loudness
			setLogicElementVar($id,14,1);
		}
		if ($E[15]['refresh']==1){//Bass
				setLogicElementVar($id,15,1);
		}
		if ($E[16]['refresh']==1){ //Treble
			setLogicElementVar($id,16,1);
		}
		if ($E[17]['refresh']==1){ //Crossfade
			setLogicElementVar($id,17,1);
		}
		if ($E[18]['refresh']==1){ //Radio Ã¼ber Namen
			setLogicElementVar($id,18,1);
		}
		if ($E[19]['refresh']==1){ //MP3 abspielen
			setLogicElementVar($id,19,1);
		}
		if ($E[22]['refresh']==1 && $E[22]['value']==1){ //Volume up
			setLogicElementVar($id,22,1);
		}
		if ($E[23]['refresh']==1 && $E[23]['value']==1){ //Volume down
			setLogicElementVar($id,23,1);
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
global $ip;
global $setvol;
global $station;
if ($E=getLogicEingangDataAll($id)) {

$ip = $E[2]['value'];
$V=getLogicElementVarAll($id);

if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> SONOS EXEC started.");}
			exec("ping -c 2 $ip", $array, $return);
			if($return){
			debug($id,'SONOS - ECEX : Ping nicht erreichbar: '.$ip);
			setLogicElementVar($id,2,0);
			die();
			}


}
$sonos = new SonosAccess($ip);
while(getSysInfo(1)>=1) {	

if ($E=getLogicEingangDataAll($id)) {
		$V=getLogicElementVarAll($id);
  
	}
		
//$sonos = new SonosAccess($ip);
$transportInfo=$sonos->GetTransportInfo();

		if ($V[3]==1) {//Play
			$sonos->Play();
			if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Set Play");}
			setLogicElementVar($id,3,0);
		}
		//20161014 - nur einen Eingang für Play und Pause verwenden
		elseif ($V[3]==0) {//Play-Pause
			$sonos->Pause();
			if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Set Play-Pause");}
			setLogicElementVar($id,3,0);
		}
		if ($V[4]==1) {//Stop
			$sonos->Stop();
			if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Set Stop");}
			setLogicElementVar($id,4,0);
		}
		if ($V[5]==1) {//Pause
			$sonos->Pause();
			if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Set Pause");}
			setLogicElementVar($id,5,0);
		}
		if ($V[6]==1) {//Volume
			$E=getLogicEingangDataAll($id);
			$setvol=$E[6]['value'];
			$sonos->SetVolume($setvol);
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Volume: ".($setvol));}
			usleep(500);
			$volume=$sonos-> GetVolume();
			setLogicLinkAusgang($id,2,$volume);
			setLogicElementVar($id,6,0);
		}
		if ($V[7]==1) {//Playmode
			$E=getLogicEingangDataAll($id);
			$sonos->SetPlayMode(round($E[7]['value']));
			$playmode = $sonos->GetTransportSettings();
			if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Playmode: ($playmode)");}
			setLogicElementVar($id,7,0);
		}
		if ($V[8]==1) {//Mute
			$E=getLogicEingangDataAll($id);
			$sonos->SetMute($E[8]['value']);
			$mute = $sonos->GetMute();
			if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Mute: ($mute)");}
			setLogicElementVar($id,8,0);
		}
		if ($V[9]==1) { //Play Radio per uri
			$E=getLogicEingangDataAll($id);
			$uri=$E[9]['value'];
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Favoriten Name: ".$uri);}
			$sonos->SetRadio($uri);
			usleep(500);	
			$sonos->Play();
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> RadioStation: ".$station);};			
			setLogicElementVar($id,9,0);
		}
		if ($V[10]==1) { // Play Playliste per uri
			$zoneinfo=$sonos->GetZoneGroupAttributes();
			$sonos->ClearQueue();
			$E=getLogicEingangDataAll($id);
			usleep(500);	
			$uri=$E[10]['value'];
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Playliste Name: ".$uri);}
			$sonos->AddToQueue($uri);
			$sonos->SetAVTransportURI('x-rincon-queue:'.$zoneinfo['CurrentZonePlayerUUIDsInGroup'].'#0');
			$sonos->Play();
			setLogicElementVar($id,10,0);
		}
		if ($V[11]==1) {		//Next
			$sonos->Next();
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Next");}
			setLogicElementVar($id,11,0);
		}
		if ($V[12]==1) {		//Previous
			$sonos->Previous();
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Previous");}
			setLogicElementVar($id,12,0);
		}
		if ($V[13]==1) {		//Rewind
			$sonos->Rewind();
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Rewind");}
			setLogicElementVar($id,13,0);
		}
		if ($V[14]==1) {	 //Loudness
			$E=getLogicEingangDataAll($id);
			$sonos->SetLoudness($E[14]['value']);
			$loudness = $sonos->GetLoudness();
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Loudness: ($loudness)");}
			setLogicElementVar($id,14,0);
		}
		if ($V[15]==1) { //Bass
			$E=getLogicEingangDataAll($id);
			$sonos->SetBass($E[15]['value']);
			$bass = $sonos->GetBass();
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Bass: ($bass)");}
			setLogicElementVar($id,15,0);
		}
		if ($V[16]==1) { //Treble
			$E=getLogicEingangDataAll($id);
			$sonos->SetTreble($E[16]['value']);
			$treble = $sonos->GetTreble();
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Treble: ($treble)");}
			setLogicElementVar($id,16,0);
		}
		if ($V[17]==1) { //Crossfade
			$E=getLogicEingangDataAll($id); 
			$sonos->SetCrossfade($E[17]['value']);
			$crossfade = $sonos->GetLoudness();
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Crossfade: ($crossfade)");}
			setLogicElementVar($id,17,0);
		}
		if ($V[18]==1) { //Radio Ã¼ber Namen abspielen
			$E=getLogicEingangDataAll($id); 
			$RadioStations=$sonos->get_available_stations();
			$name=$E[18]['value'];
			$Radio = $sonos->get_station_url($name, $RadioStations);
			$sonos->SetRadio($Radio['url'], $Radio['name']);
			$sonos->Play();
					if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Radio: ($name)");}

			setLogicElementVar($id,18,0);
		}
		if ($V[19]==1) { //MP3 abspielen
			$E=getLogicEingangDataAll($id); 
					if($E[21]['value']==1){debug($id, "SONOS - EXEC - START: -> MP3: ($E[19]['value'])".($E[19]['value'])." Volume: ".(round($E[20]['value'])));}
			$sonos->PlayFiles(Array($E[19]['value']), round($E[20]['value']));
					if($E[21]['value']==1){debug($id, "SONOS - EXEC - ENDE: -> MP3: ($E[19]['value'])".($E[19]['value'])." Volume: ".(round($E[20]['value'])));}
			setLogicElementVar($id,19,0);
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
			//damit keine Verzögerung beim mehrfachen Erhöhen der LS entsteht, wird der berechnete Weert auf den Ausgang geschrieben.
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
			//damit keine Verzögerung beim mehrfachen Erhöhen der LS entsteht, wird der berechnete Weert auf den Ausgang geschrieben.
			setLogicLinkAusgang($id,2,$setvol);
			setLogicElementVar($id,23,0);
		}

$mediaInfo=$sonos->GetMediaInfo();
$positionInfo=$sonos->GetPositionInfo();
$transportInfo=$sonos->GetTransportInfo();
$volume=$sonos-> GetVolume();
$mute = $sonos->GetMute();
$bass = $sonos->GetBass();
$treble = $sonos->GetTreble();
$loudness = $sonos->GetLoudness();
$playmode = $sonos->GetTransportSettings();
$crossfade = $sonos->GetLoudness();
$zoneinfo=$sonos->GetZoneGroupAttributes();

	if (!empty($mediaInfo['title'])) {
			
			if ($V[118] != 1 && $transportInfo == 1) {
							setLogicElementVar($id,118,1);
							setLogicLinkAusgang($id,5," ");
							
							setLogicElementVar($id,120,$transportInfo);
							}
			if ($V[119] != 1 && $transportInfo == 1) {
							setLogicElementVar($id,119,1);
							setLogicLinkAusgang($id,6," ");
							}
							
			if ($mediaInfo['title']!==$V[100] && $transportInfo == 1 ) { 
						setLogicElementVar($id,100,$mediaInfo['title']);
						setLogicLinkAusgang($id,3,$mediaInfo['title']);
//						setLogicElementVar($id,110,$transportInfo);
						}
			if ($positionInfo["streamContent"]!==$V[101] && $transportInfo == 1) { 		
						setLogicElementVar($id,101,$positionInfo["streamContent"]);
						setLogicLinkAusgang($id,4,$positionInfo["streamContent"]);
						}
			$station=preg_replace("#(.*)x-sonosapi-stream:(.*?)\?sid(.*)#is",'$2',$mediaInfo['CurrentURI']);
					if ($station!==getLogicElementVar($id,21) && $transportInfo==1) { 
								$serial = substr($zoneinfo['CurrentZoneGroupID'] , 7,12);
								$sender = preg_replace('#(.*)<outline type="text" text="(.*?)\" guide_id(.*)#is','$2',@file_get_contents("http://opml.radiotime.com/Describe.ashx?c=nowplaying&id=".$station."&partnerId=IAeIhU42&serial=".$serial));
								if($E[21]['value']==1){debug($id, "SONOS - EXEC : ->  Radio Station: ".$station);}
								$logo = preg_replace('#(.*)image="(.*?)\"(.*)#is','$2',@file_get_contents("http://opml.radiotime.com/Describe.ashx?c=nowplaying&id=".$station."&partnerId=IAeIhU42&serial=".$serial));
								if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Radio Logo link: ".$logo);}
								setLogicElementVar($id,21,$station);
								setLogicLinkAusgang($id,7,$logo);
					}
			
			
	}
	else{
			

			if ($positionInfo['title']!=getLogicElementVar($id,111)  && $transportInfo == 1) {
							setLogicElementVar($id,111,$positionInfo['title']); 
							setLogicLinkAusgang($id,4,$positionInfo['title']);
							setLogicLinkAusgang($id,3," ");
							setLogicElementVar($id,120,$transportInfo);
							}
			if ($positionInfo['artist']!=getLogicElementVar($id,112) && $transportInfo == 1 ) {
							setLogicElementVar($id,112,$positionInfo['artist']);
							setLogicElementVar($id,118,0);
							setLogicElementVar($id,100,0);
							setLogicLinkAusgang($id,5,$positionInfo['artist']);
							}
			if ($positionInfo["album"]!=getLogicElementVar($id,113) && $transportInfo == 1) {
							setLogicElementVar($id,113,$positionInfo["album"]);
							setLogicElementVar($id,119,0);
							setLogicLinkAusgang($id,6,$positionInfo["album"]);
							}
			if ($positionInfo["albumArtURI"]!=getLogicElementVar($id,20) && $transportInfo == 1) { 
							setLogicElementVar($id,20,$positionInfo["albumArtURI"]);
							setLogicElementVar($id,21,0);
							setLogicLinkAusgang($id,7,$positionInfo["albumArtURI"]);
							}
			if ($positionInfo["TrackDuration"]!=getLogicElementVar($id,115) && $transportInfo == 1) { 
							setLogicElementVar($id,115,$positionInfo["TrackDuration"]);
							setLogicLinkAusgang($id,8,$positionInfo["TrackDuration"]);
							}
			if ($positionInfo["RelTime"]!=getLogicElementVar($id,116) && $transportInfo == 1) { 
							setLogicElementVar($id,116,$positionInfo["RelTime"]);
							setLogicLinkAusgang($id,9,$positionInfo["RelTime"]);
							}
		
	}



		if ($transportInfo!=$V[121] ) { 
//		if ($transportInfo!=getLogicElementVar($id,121) ) { 
						setLogicElementVar($id,121,$transportInfo);
						setLogicLinkAusgang($id,1,$transportInfo);
//						if($E[21]['value']==1){debug($id, "SONOS - EXEC : -> Transportinfo: $transportInfo");}
						}
		if ($volume!=getLogicElementVar($id,103) ) {
						setLogicElementVar($id,103,$volume); 
						setLogicLinkAusgang($id,2,$volume);	//Volume
						}
		if ($loudness!==getLogicElementVar($id,104) ) {
						setLogicElementVar($id,104,$loudness); 
						setLogicLinkAusgang($id,11,$loudness);
						}
		if ($bass!==getLogicElementVar($id,105) ) {
						setLogicElementVar($id,105,$bass); 
						setLogicLinkAusgang($id,12,$bass);
						}
		if ($treble!==getLogicElementVar($id,106) ) {
						setLogicElementVar($id,106,$treble); 
						setLogicLinkAusgang($id,13,$treble);
						}
		if ($mute!=getLogicElementVar($id,107) ) {
						setLogicElementVar($id,107,$mute); 
						setLogicLinkAusgang($id,14,$mute);
						}
		if ($playmode!=getLogicElementVar($id,108) ) {
						setLogicElementVar($id,108,$playmode);
						setLogicLinkAusgang($id,15,$playmode);
						}
		if ($crossfade!==getLogicElementVar($id,109) ) {
						setLogicElementVar($id,109,$crossfade);
						setLogicLinkAusgang($id,16,$crossfade);
						}

if ($transportInfo == 3 && $transportInfo!=getLogicElementVar($id,120) ) {
		setLogicElementVar($id,120,$transportInfo);
		setLogicElementVar($id,118,0);
		setLogicElementVar($id,119,0);
		setLogicElementVar($id,100,0);
		setLogicLinkAusgang($id,3," ");
		setLogicLinkAusgang($id,4," ");
		setLogicLinkAusgang($id,5," ");
		setLogicLinkAusgang($id,6," ");
		setLogicLinkAusgang($id,7,"-");
		setLogicLinkAusgang($id,8," ");
		setLogicLinkAusgang($id,9," ");
		setLogicLinkAusgang($id,11," ");
		setLogicLinkAusgang($id,12," ");
		setLogicLinkAusgang($id,13," ");
		setLogicLinkAusgang($id,17," ");
		setLogicElementVar($id,21,0);
	}

	usleep(1000000);		

}

sql_disconnect();

function debug($id,$msg)
{
$E=getLogicEingangDataAll($id);
$version = getLogicElementVar($id,1);
writeToTraceLog(0,true,"LBSLBSID($id): ".$msg.' [v'.$version.']');
}
?>
###[/EXEC]###