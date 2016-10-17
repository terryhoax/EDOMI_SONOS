<?php

// SONOS Acces Handler
// using PHO SoapClient

class SonosAccess{

  public function __construct( $address )
  {
    $this->address = $address;
  }
  
  
public function PlayFiles(array $files, $volumeChange)
{
	$positionInfo = $this->GetPositionInfo();
	$mediaInfo = $this->GetMediaInfo();
	$transportInfo = $this->GetTransportInfo();
  $volume = $this->GetVolume();

 if($volumeChange != 0)
	{
        // pause if playing
        if($transportInfo==1) $this->Pause(); 
        $this->SetVolume($volumeChange); 
	}

foreach ($files as $key => $file) {
          // only files on SMB share or http server can be used
          if (preg_match('/^\/\/[\w,.,\d,-]*\/\S*/',$file) == 1){
            $uri = "x-file-cifs:".$file;
          }
          elseif (preg_match('/^http:\/\/[\w,.,\d,-,:]*\/\S*/',$file) == 1){
            $uri = $file;
          }
          else{
            throw new Exception("File (".$file.") has to be located on a Samba share (e.g. //ipsymcon.fritz.box/tts/text.mp3) or a HTTP server (e.g. http://ipsymcon.fritz.box/tts/text.mp3)");
		      }		
			
	$this->SetAVTransportURI($uri);
	$this->Play();
  sleep(1);
  $fileTransportInfo = $this->GetTransportInfo();
        while ($fileTransportInfo==1 || $fileTransportInfo==5){ 
          	sleep(1);
           	$fileTransportInfo = $this->GetTransportInfo();	 
        }
}

$this->SetAVTransportURI($mediaInfo["CurrentURI"],$mediaInfo["CurrentURIMetaData"]);
        if($positionInfo["Track"] > 1 )
          $this->Seek("TRACK_NR",$positionInfo["Track"]);
        if($positionInfo["TrackDuration"] != "0:00:00" )
			$this->Seek("REL_TIME",$positionInfo["RelTime"]);
		
	  if($volumeChange != 0){
		   $this->SetVolume($volume); 
	   }

	if ($transportInfo==1){
         	 $this->Play();
	  } 

}
  
  
  public function GetSonosPlaylist()
 {
			$P_Associations = Array();
			$p_value = 1;
			$fav_playlist = $this->BrowseContentDirectory($objectID='SQ:',$browseFlag="BrowseDirectChildren",$requestedCount=100,$startingIndex=0,$filter="",$sortCriteria="");
			$xmlr = new SimpleXMLElement($fav_playlist['Result']);
			foreach ($xmlr->container as $container) {
         				$p_name=$container->xpath('dc:title');
				$P_Associations[] = Array($p_value++, (string)$p_name['0'], (string)$container->res, -1);
				
			}
			 return $P_Associations;
}

    public function GetMeineRadiosender()
 {
			$F_Associations = Array();
			$r_value = 1;
			$favoriten = $this->BrowseContentDirectory($objectID='R:0/0',$browseFlag="BrowseDirectChildren",$requestedCount=100,$startingIndex=0,$filter="",$sortCriteria="");
			$plxmlr = new SimpleXMLElement($favoriten['Result']);
			foreach ($plxmlr->item as $item) {
				$r_name=$item->xpath('dc:title');
                			$F_Associations[] = Array($r_value++,  (string)$r_name['0'],(string)$item->res);
						}
			 return $F_Associations;
		 }

  public function AddToQueue($file)
  {
    $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                           "urn:schemas-upnp-org:service:AVTransport:1",
                           "AddURIToQueue",
                           array( 
                                  new SoapParam("0"                     ,"InstanceID"                     ),
                                  new SoapParam(htmlspecialchars($file) ,"EnqueuedURI"                    ),
                                  new SoapParam(""                      ,"EnqueuedURIMetaData"            ),
                                  new SoapParam("0"                     ,"DesiredFirstTrackNumberEnqueued"),
                                  new SoapParam("1"                     ,"EnqueueAsNext"                  )
                                ));
  }

  public function BrowseContentDirectory($objectID='SQ:',$browseFlag='BrowseDirectChildren',$requestedCount=100,$startingIndex=0,$filter='',$sortCriteria='')
  {
    return $this->processSoapCall("/MediaServer/ContentDirectory/Control",
                                  "urn:schemas-upnp-org:service:ContentDirectory:1",
                                  "Browse",
                                  array(
                                         new SoapParam($objectID      ,"ObjectID"      ),
                                         new SoapParam($browseFlag    ,"BrowseFlag"    ),
                                         new SoapParam($filter        ,"Filter"        ),
                                         new SoapParam($startingIndex ,"StartingIndex" ),
                                         new SoapParam($requestedCount,"RequestedCount"),
                                         new SoapParam($sortCriteria  ,"SortCriteria"  )
                                       ));
  }

  public function ClearQueue()
  {
    $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                           "urn:schemas-upnp-org:service:AVTransport:1",
                           "RemoveAllTracksFromQueue",
                            array(
                                   new SoapParam("0","InstanceID")
                                 ));
  }

  public function GetBass()
  {
    return (int)$this->processSoapCall("/MediaRenderer/RenderingControl/Control",
                                       "urn:schemas-upnp-org:service:RenderingControl:1",
                                       "GetBass",
                                       array(
                                              new SoapParam("0"     ,"InstanceID"),
                                              new SoapParam("Master","Channel"   )
                                            ));
  }

  public function GetCrossfade()
  {
    return (int)$this->processSoapCall("/MediaRenderer/AVTransport/Control",
                                       "urn:schemas-upnp-org:service:AVTransport:1",
                                       "GetCrossfadeMode",
                                       array(
                                              new SoapParam("0","InstanceID")
                                            ));
  }

  public function GetLoudness()
  {
    return (int)$this->processSoapCall("/MediaRenderer/RenderingControl/Control",
                                       "urn:schemas-upnp-org:service:RenderingControl:1",
                                       "GetLoudness",
                                       array(
                                              new SoapParam("0"     ,"InstanceID"),
                                              new SoapParam("Master","Channel"   )
                                            )); 
  }

  public function GetMediaInfo()
  {
    $mediaInfo = $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                                        "urn:schemas-upnp-org:service:AVTransport:1",
                                        "GetMediaInfo",
                                        array(
                                               new SoapParam("0","InstanceID")
                                             ));

    $xmlParser = xml_parser_create("UTF-8");
    xml_parser_set_option($xmlParser, XML_OPTION_TARGET_ENCODING, "UTF-8");
    xml_parse_into_struct($xmlParser, $mediaInfo["CurrentURIMetaData"], $vals, $index);
    xml_parser_free($xmlParser);

    if (isset($index["DC:TITLE"]) and isset($vals[$index["DC:TITLE"][0]]["value"])){
      $mediaInfo["title"] = $vals[$index["DC:TITLE"][0]]["value"];
    }else{
      $mediaInfo["title"] = "";
    }

    return $mediaInfo;
  }

  public function GetMute()
  {
    return (int)$this->processSoapCall("/MediaRenderer/RenderingControl/Control",
                                       "urn:schemas-upnp-org:service:RenderingControl:1",
                                       "GetMute",
                                       array(
                                              new SoapParam("0"     ,"InstanceID"),
                                              new SoapParam("Master","Channel"   )
                                            ));
  }

  public function GetPositionInfo()
  {
    $positionInfo = $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                                           "urn:schemas-upnp-org:service:AVTransport:1",
                                           "GetPositionInfo",
                                           array(
                                                  new SoapParam("0","InstanceID")
                                                 ));

    $xmlParser = xml_parser_create("UTF-8");
    xml_parser_set_option($xmlParser, XML_OPTION_TARGET_ENCODING, "UTF-8");
    xml_parse_into_struct($xmlParser, $positionInfo["TrackMetaData"], $vals, $index);
    xml_parser_free($xmlParser);

    if (isset($index["DC:CREATOR"]) and isset($vals[$index["DC:CREATOR"][0]]["value"])){
      $positionInfo["artist"] = $vals[$index["DC:CREATOR"][0]]["value"];
    }else{
      $positionInfo["artist"] = "";
    }
    
    if (isset($index["DC:TITLE"]) and isset($vals[$index["DC:TITLE"][0]]["value"])){
      $positionInfo["title"] = $vals[$index["DC:TITLE"][0]]["value"];
    }else{
      $positionInfo["title"] = "";
    }

    if (isset($index["UPNP:ALBUM"]) and isset($vals[$index["UPNP:ALBUM"][0]]["value"])){
      $positionInfo["album"] = $vals[$index["UPNP:ALBUM"][0]]["value"];
    }else{
      $positionInfo["album"] = "";
    }

    if (isset($index["UPNP:ALBUMARTURI"]) and isset($vals[$index["UPNP:ALBUMARTURI"][0]]["value"])){
      $positionInfo["albumArtURI"] = "http://" . $this->address . ":1400" . $vals[$index["UPNP:ALBUMARTURI"][0]]["value"];
    }else{
      $positionInfo["albumArtURI"] = "";
    }

    if (isset($index["R:ALBUMARTIST"]) and isset($vals[$index["R:ALBUMARTIST"][0]]["value"])){
      $positionInfo["albumArtist"] = $vals[$index["R:ALBUMARTIST"][0]]["value"];
    }else{
      $positionInfo["albumArtist"] = "";
    }

    if (isset($index["R:STREAMCONTENT"]) and isset($vals[$index["R:STREAMCONTENT"][0]]["value"])){
      $positionInfo["streamContent"] = $vals[$index["R:STREAMCONTENT"][0]]["value"];
    }else{
      $positionInfo["streamContent"] = "";
    }

    return $positionInfo;
  }

  public function GetSleeptimer()
  {
    $remainingTimer = $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                                             "urn:schemas-upnp-org:service:AVTransport:1",
                                             "GetRemainingSleepTimerDuration",
                                             array(
                                                    new SoapParam("0","InstanceID")
                                                  ));
    return $remainingTimer["RemainingSleepTimerDuration"];
 
  }
  
  public function GetTransportInfo()
  {
    $returnContent = $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                                            "urn:schemas-upnp-org:service:AVTransport:1",
                                            "GetTransportInfo",
                                            array(
                                                   new SoapParam("0","InstanceID")
                                                 ));
    
    switch ($returnContent["CurrentTransportState"]){
      case "PLAYING":
        return 1;
      case "PAUSED_PLAYBACK":
        return 2;
      case "STOPPED":
        return 3;
      case "TRANSITIONING":
        return 5;
      default:
        throw new Exception("Unknown Transport State: ".$returnContent["CurrentTransportState"]); 
    }
  }

  public function GetTransportSettings()
  {
    $returnContent = $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                                            "urn:schemas-upnp-org:service:AVTransport:1",
                                            "GetTransportSettings",
                                            array(
                                                   new SoapParam("0","InstanceID")
                                                 ));

    switch ($returnContent["PlayMode"]){
      case "NORMAL":
        return 0;
      case "REPEAT_ALL":
        return 1;
      case "REPEAT_ONE":
        return 2;
      case "SHUFFLE_NOREPEAT":
        return 3;
      case "SHUFFLE":
        return 4;
      case "SHUFFLE_REPEAT_ONE":
        return 5;
      default:
        throw new Exception("Unknown Play Mode: ".$returnContent["CurrentTransportState"]);
    }
  }

  public function GetTreble()
  {
    return (int)$this->processSoapCall("/MediaRenderer/RenderingControl/Control",
                                       "urn:schemas-upnp-org:service:RenderingControl:1",
                                       "GetTreble",
                                       array(
                                              new SoapParam("0"     ,"InstanceID"),
                                              new SoapParam("Master","Channel"   )
                                            ));
                                       
  }

  public function GetVolume($channel = 'Master')
  {
    return (int)$this->processSoapCall("/MediaRenderer/RenderingControl/Control",
                                       "urn:schemas-upnp-org:service:RenderingControl:1",
                                       "GetVolume",
                                       array(
                                              new SoapParam("0"     ,"InstanceID"),
                                              new SoapParam($channel,"Channel"   )
                                            ));
  }

  public function GetZoneGroupAttributes()
  {
    return $this->processSoapCall("/ZoneGroupTopology/Control",
                                  "urn:schemas-upnp-org:service:ZoneGroupTopology:1",
                                  "GetZoneGroupAttributes",
                                  array() );
                                  
  }

  public function Next()
  {
    $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                           "urn:schemas-upnp-org:service:AVTransport:1",
                           "Next",
                           array(
                                  new SoapParam("0","InstanceID")
                                ));
  }

  public function Pause()
  {
    $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                           "urn:schemas-upnp-org:service:AVTransport:1",
                           "Pause",
                            array(
                                   new SoapParam("0","InstanceID")
                                 ));
  }

  public function Play()
  {
    $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                           "urn:schemas-upnp-org:service:AVTransport:1",
                           "Play",
                           array(
                                  new SoapParam("0","InstanceID"),
                                  new SoapParam("1","Speed"     )
                                ));
  }

  public function Previous()
  {
    $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                           "urn:schemas-upnp-org:service:AVTransport:1",
                           "Previous",
                            array(
                                   new SoapParam("0","InstanceID")
                                 ));
  }

  public function RemoveFromQueue($track)
  {
    $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                           "urn:schemas-upnp-org:service:AVTransport:1",
                           "RemoveTrackFromQueue",
                           array(
                                  new SoapParam("0"          ,"InstanceID"),
                                  new SoapParam("Q:0/".$track,"ObjectID"  )
                                ));
  }

  public function Rewind()
  {
    $this->Seek("REL_TIME","00:00:00");
  }

  public function Seek($unit,$target)
  {
    $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                           "urn:schemas-upnp-org:service:AVTransport:1",
                           "Seek",
                           array(
                                  new SoapParam("0"    ,"InstanceID"),
                                  new SoapParam($unit  ,"Unit"      ),
                                  new SoapParam($target,"Target"    )
                                ));
  }

  public function SetAVTransportURI($tspuri,$MetaData="")
  {
    $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                           "urn:schemas-upnp-org:service:AVTransport:1",
                           "SetAVTransportURI",
                           array(
                                  new SoapParam("0"                      ,"InstanceID"        ),
                                  new SoapParam(htmlspecialchars($tspuri),"CurrentURI"        ),
                                  new SoapParam($MetaData                ,"CurrentURIMetaData")
                                ));
  }

  public function SetBass($bass)
  {
    $this->processSoapCall("/MediaRenderer/RenderingControl/Control",
                           "urn:schemas-upnp-org:service:RenderingControl:1",
                           "SetBass",
                           array(
                                  new SoapParam("0"  ,"InstanceID" ),
                                  new SoapParam($bass,"DesiredBass")
                                ));
  }

  public function SetCrossfade($crossfade)
  {
    if($crossfade){
      $crossfade = "1";
    }else{
      $crossfade = "0";
    }

    $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                           "urn:schemas-upnp-org:service:AVTransport:1",
                           "SetCrossfadeMode",
                           array(
                                  new SoapParam("0"       ,"InstanceID"   ),
                                  new SoapParam($crossfade,"CrossfadeMode")
                                ));
  }

  public function SetLoudness($loud)
  {
    if($loud){
      $loud = "1";
    }else{
      $loud = "0";
    }

    $this->processSoapCall("/MediaRenderer/RenderingControl/Control",
                           "urn:schemas-upnp-org:service:RenderingControl:1",
                           "SetLoudness",
                           array(
                                  new SoapParam("0"     ,"InstanceID"     ),
                                  new SoapParam("Master","Channel"        ),
                                  new SoapParam($loud   ,"DesiredLoudness")
                                ));
  }

  public function SetMute($mute)
  {
    if($mute){
      $mute = "1";
    }else{
      $mute = "0";
    }

    $this->processSoapCall("/MediaRenderer/RenderingControl/Control",
                           "urn:schemas-upnp-org:service:RenderingControl:1",
                           "SetMute",
                           array(
                                  new SoapParam("0"     ,"InstanceID" ),
                                  new SoapParam("Master","Channel"    ),
                                  new SoapParam($mute   ,"DesiredMute")
                                ));
  }

  public function SetPlayMode($PlayMode)
  {
    switch ($PlayMode){
      case 0:
        $PlayMode = "NORMAL";
        break;
      case 1:
        $PlayMode = "REPEAT_ALL";
        break;
      case 2:
        $PlayMode = "REPEAT_ONE";
        break;
      case 3:
        $PlayMode = "SHUFFLE_NOREPEAT";
        break;
      case 4:
        $PlayMode = "SHUFFLE";
        break;
      case 5:
        $PlayMode = "SHUFFLE_REPEAT_ONE";
        break;
      default:
        throw new Exception("Unknown Play Mode: ".$PlayMode);
    }
  
    $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                           "urn:schemas-upnp-org:service:AVTransport:1",
                           "SetPlayMode",
                           array(
                                  new SoapParam("0"      ,"InstanceID"  ),
                                  new SoapParam($PlayMode,"NewPlayMode" )
                                ));
  }

  public function SetQueue($queue)
  {
    $this->SetAVTransportURI($queue);
  }

  public function SetRadio($radio, $radio_name = "IP-Symcon Radio" )
  {
    $metaData = '<DIDL-Lite xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:upnp="urn:schemas-upnp-org:metadata-1-0/upnp/" xmlns:r="urn:schemas-rinconnetworks-com:metadata-1-0/" xmlns="urn:schemas-upnp-org:metadata-1-0/DIDL-Lite/"><item id="-1" parentID="-1" restricted="true"><dc:title>'.htmlspecialchars($radio_name).'</dc:title><upnp:class>object.item.audioItem.audioBroadcast</upnp:class><desc id="cdudn" nameSpace="urn:schemas-rinconnetworks-com:metadata-1-0/">SA_RINCON65031_</desc></item></DIDL-Lite>';

    $this->SetAVTransportURI($radio,$metaData);
  }

  public function SetSleeptimer($hours,$minutes,$seconds)
  {
    if( $hours == 0 && $minutes == 0 && $seconds == 0 ){
      $sleeptimer = '';
    }else{
      $sleeptimer = $hours.':'.$minutes.':'.$seconds;
    }

    $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                           "urn:schemas-upnp-org:service:AVTransport:1",
                           "ConfigureSleepTimer",
                           array(
                                  new SoapParam("0"        ,"InstanceID"           ),
                                  new SoapParam($sleeptimer,"NewSleepTimerDuration")
                                ));
  }

  public function SetTrack($track)
  {
    $this->Seek("TRACK_NR",$track);
  }

  public function SetTreble($treble)
  {
    $this->processSoapCall("/MediaRenderer/RenderingControl/Control",
                           "urn:schemas-upnp-org:service:RenderingControl:1",
                           "SetTreble",
                           array(
                                  new SoapParam("0"    ,"InstanceID"   ),
                                  new SoapParam($treble,"DesiredTreble")
                                ));
  }

  public function SetVolume($volume, $channel = 'Master')
  {
    $this->processSoapCall("/MediaRenderer/RenderingControl/Control",
                           "urn:schemas-upnp-org:service:RenderingControl:1",
                           "SetVolume",
                           array(
                                  new SoapParam("0"     ,"InstanceID"   ),
                                  new SoapParam($channel,"Channel"      ),
                                  new SoapParam($volume ,"DesiredVolume")
                                ));
  }

  public function Stop()
  {
    $this->processSoapCall("/MediaRenderer/AVTransport/Control",
                           "urn:schemas-upnp-org:service:AVTransport:1",
                           "Stop",
                           array(
                                  new SoapParam("0","InstanceID")
                                ));
  }


  private function processSoapCall($path,$uri,$action,$parameter)
  {
    try{
      $client     = new SoapClient(null, array("location"   => "http://".$this->address.":1400".$path,
                                               "uri"        => $uri,
                                               "trace"      => true ));

      return $client->__soapCall($action,$parameter);
    }catch(Exception $e){
      $faultstring = $e->faultstring;
      $faultcode   = $e->faultcode;
      if(isset($e->detail->UPnPError->errorCode)){
        $errorCode   = $e->detail->UPnPError->errorCode;
        throw new Exception("Error during Soap Call: ".$faultstring." ".$faultcode." ".$errorCode." (".$this->resoveErrorCode($path,$errorCode).")");
      }else{
        throw new Exception("Error during Soap Call: ".$faultstring." ".$faultcode);
      }
    }
  }

  private function resoveErrorCode($path,$errorCode)
  {
   $errorList = array( "/MediaRenderer/AVTransport/Control"      => array(
                                                                           "701" => "ERROR_AV_UPNP_AVT_INVALID_TRANSITION",
                                                                           "702" => "ERROR_AV_UPNP_AVT_NO_CONTENTS",
                                                                           "703" => "ERROR_AV_UPNP_AVT_READ_ERROR",
                                                                           "704" => "ERROR_AV_UPNP_AVT_UNSUPPORTED_PLAY_FORMAT",
                                                                           "705" => "ERROR_AV_UPNP_AVT_TRANSPORT_LOCKED",
                                                                           "706" => "ERROR_AV_UPNP_AVT_WRITE_ERROR",
                                                                           "707" => "ERROR_AV_UPNP_AVT_PROTECTED_MEDIA",
                                                                           "708" => "ERROR_AV_UPNP_AVT_UNSUPPORTED_REC_FORMAT",
                                                                           "709" => "ERROR_AV_UPNP_AVT_FULL_MEDIA",
                                                                           "710" => "ERROR_AV_UPNP_AVT_UNSUPPORTED_SEEK_MODE",
                                                                           "711" => "ERROR_AV_UPNP_AVT_ILLEGAL_SEEK_TARGET",
                                                                           "712" => "ERROR_AV_UPNP_AVT_UNSUPPORTED_PLAY_MODE",
                                                                           "713" => "ERROR_AV_UPNP_AVT_UNSUPPORTED_REC_QUALITY",
                                                                           "714" => "ERROR_AV_UPNP_AVT_ILLEGAL_MIME",
                                                                           "715" => "ERROR_AV_UPNP_AVT_CONTENT_BUSY",
                                                                           "716" => "ERROR_AV_UPNP_AVT_RESOURCE_NOT_FOUND",
                                                                           "717" => "ERROR_AV_UPNP_AVT_UNSUPPORTED_PLAY_SPEED",
                                                                           "718" => "ERROR_AV_UPNP_AVT_INVALID_INSTANCE_ID"
                                                                         ),
                       "/MediaRenderer/RenderingControl/Control" => array(
                                                                           "701" => "ERROR_AV_UPNP_RC_INVALID_PRESET_NAME",
                                                                           "702" => "ERROR_AV_UPNP_RC_INVALID_INSTANCE_ID"
                                                                         ),
                       "/MediaServer/ContentDirectory/Control"   => array(
                                                                           "701" => "ERROR_AV_UPNP_CD_NO_SUCH_OBJECT",
                                                                           "702" => "ERROR_AV_UPNP_CD_INVALID_CURRENTTAGVALUE",
                                                                           "703" => "ERROR_AV_UPNP_CD_INVALID_NEWTAGVALUE",
                                                                           "704" => "ERROR_AV_UPNP_CD_REQUIRED_TAG_DELETE",
                                                                           "705" => "ERROR_AV_UPNP_CD_READONLY_TAG_UPDATE",
                                                                           "706" => "ERROR_AV_UPNP_CD_PARAMETER_NUM_MISMATCH",
                                                                           "708" => "ERROR_AV_UPNP_CD_BAD_SEARCH_CRITERIA",
                                                                           "709" => "ERROR_AV_UPNP_CD_BAD_SORT_CRITERIA",
                                                                           "710" => "ERROR_AV_UPNP_CD_NO_SUCH_CONTAINER",
                                                                           "711" => "ERROR_AV_UPNP_CD_RESTRICTED_OBJECT",
                                                                           "712" => "ERROR_AV_UPNP_CD_BAD_METADATA",
                                                                           "713" => "ERROR_AV_UPNP_CD_RESTRICTED_PARENT_OBJECT",
                                                                           "714" => "ERROR_AV_UPNP_CD_NO_SUCH_SOURCE_RESOURCE",
                                                                           "715" => "ERROR_AV_UPNP_CD_SOURCE_RESOURCE_ACCESS_DENIED",
                                                                           "716" => "ERROR_AV_UPNP_CD_TRANSFER_BUSY",
                                                                           "717" => "ERROR_AV_UPNP_CD_NO_SUCH_FILE_TRANSFER",
                                                                           "718" => "ERROR_AV_UPNP_CD_NO_SUCH_DESTINATION_RESOURCE",
                                                                           "719" => "ERROR_AV_UPNP_CD_DESTINATION_RESOURCE_ACCESS_DENIED",
                                                                           "720" => "ERROR_AV_UPNP_CD_REQUEST_FAILED"
                                                                         ) ); 

    if (isset($errorList[$path][$errorCode])){
      return $errorList[$path][$errorCode] ;
    }else{
      return "UNKNOWN";
    }
  }

  public function get_available_stations(){
  $RadioStations =  Array(
            Array( ('name') => "1LIVE",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s25260.png",
                   ('url')  => "x-sonosapi-stream:s25260?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "1LIVE DIGGI", 
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s45087.png",
                   ('url')  => "x-sonosapi-stream:s45087?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "917xfm",   
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s85104.png",
                   ('url')  => "x-sonosapi-stream:s85104?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Antenne 1",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s25770.png",
                   ('url')  => "x-sonosapi-stream:s25770?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Antenne Bayern",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s42824.png",
                   ('url')  => "x-sonosapi-stream:s42824?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Antenne MV",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s16539.png",
                   ('url')  => "x-sonosapi-stream:s16539?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Antenne Thueringen",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s25774.png",
                   ('url')  => "x-sonosapi-stream:s25774?sid=254&flags=8224&sn=0"), 
            Array( ('name') => "Bayern 3",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s14991.png",
                   ('url')  => "x-sonosapi-stream:s14991?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "bigFM",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s84203.png",
                   ('url')  => "x-sonosapi-stream:s84203?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Bremen Vier",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s25565.png",
                   ('url')  => "x-sonosapi-stream:s25565?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Energy",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s17697.png",
                   ('url')  => "x-sonosapi-stream:s17697?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "FFH",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s17490.png",
                   ('url')  => "x-sonosapi-stream:s17490?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "FFN",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s8954.png",
                   ('url')  => "x-sonosapi-stream:s8954?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Hitradio N1",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s25946.png",
                   ('url')  => "x-sonosapi-stream:s25946?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "HR3",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s57109.png",
                   ('url')  => "x-sonosapi-stream:s57109?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "HR-Info",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s17489.png",
                   ('url')  => "x-sonosapi-stream:s17489?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "KiRaKa",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s55365.png",
                   ('url')  => "x-sonosapi-stream:s55365?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "MDR1",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s1346.png",
                   ('url')  => "x-sonosapi-stream:s1346?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "MDR Jump",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s6634.png",
                   ('url')  => "x-sonosapi-stream:s6634?sid=254&flags=8224&sn=0"),
            Array( ('name') => "NDR2",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s17492.png",
                   ('url')  => "x-sonosapi-stream:s56857?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "N-JOY",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s25531.png",
                   ('url')  => "x-sonosapi-stream:s25531?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Radio 91.2",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s8923.png",
                   ('url')  => "x-sonosapi-stream:s8923?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Radio Duisburg",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s78341.png",
                   ('url')  => "x-sonosapi-stream:s78341?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Radio Essen",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s47789.png",
                   ('url')  => "x-sonosapi-stream:s47789?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Radio K.W.",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s84621.png",
                   ('url')  => "x-sonosapi-stream:s84621?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Radio Lippe",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s96523.png",
                   ('url')  => "x-sonosapi-stream:s96523?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Radio Top40",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s18355.png",
                   ('url')  => "x-sonosapi-stream:s18355?sid=254&flags=8224&sn=0"),
            Array( ('name') => "RPR1",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s9014.png",
                   ('url')  => "x-sonosapi-stream:s9014?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "SWR1 BW",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s20291.png",
                   ('url')  => "x-sonosapi-stream:s20291?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "SWR1 RP",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s1561.png",
                   ('url')  => "x-sonosapi-stream:s124200?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "SWR3",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s24896.png",
                   ('url')  => "x-sonosapi-stream:s24896?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "WDR2",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s2680.png",
                   ('url')  => "x-sonosapi-stream:s213886?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "YouFM",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s24878.png",
                   ('url')  => "x-sonosapi-stream:s114128?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Deutschlandfunk",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s42828.png",
                   ('url')  => "x-sonosapi-stream:s42828?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "OE3",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s8007q.png",
                   ('url')  => "x-sonosapi-stream:s8007?sid=254&flags=8224&sn=0" ),
	 Array( ('name') => "Radio Arabella",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s68934q.png",
                   ('url')  => "x-sonosapi-stream:s68934?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Sunshine Live",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s10637.png",
                   ('url')  => "x-sonosapi-stream:s10637?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Sunshine Live - classics",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s237965.png",
                   ('url')  => "x-sonosapi-stream:s237965?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "Sunshine Live - trance",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s237967.png",
                   ('url')  => "x-sonosapi-stream:s237967?sid=254&flags=8224&sn=0" ),
            Array( ('name') => "RevivalKult",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s186710.png",
                   ('url')  => "x-sonosapi-stream:s186710?sid=254&flags=8224&sn=0" ),
	Array( ('name') => "ANTENNE BAYERN Oldies but Goldies",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s96749q.png",
                   ('url')  => "x-sonosapi-stream:s96749?sid=254&flags=8224&sn=0" ),
	Array( ('name') => "ANTENNE BAYERN Chillout",
                   ('logo') => "http://cdn-radiotime-logos.tunein.com/s96745q.png",
                   ('url')  => "x-sonosapi-stream:s96745?sid=254&flags=8224&sn=0" )
                         );

  return  $RadioStations ;
}

public function get_station_url($name, $RadioStations = null){

  if ( $RadioStations === null ){ $RadioStations = $this->get_available_stations(); };

  foreach ( $RadioStations as $key => $val ) {
      if ($val['name'] === $name) {
         return $RadioStations[$key];
//		return  $image = $RadioStation["logo"];

}
}

  }
  
  
}
?>
