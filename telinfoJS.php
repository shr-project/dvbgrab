<?
require_once("language.inc.php");
?>
<script type="text/javascript">
<!--
function telInfos() {
  this.infos = new Array();
  this.show = show
  this.hide = hide
  this.loadMe = loadMe
  this.getTelInfo = getTelInfo
  
  function show(tel_id) {
    var gi = this.getTelInfo(tel_id);
    gi.show();
  }
  function hide(tel_id) {
    var gi = this.getTelInfo(tel_id);
    gi.hide();
  }
  function loadMe(tel_id) {
    var gi = this.getTelInfo(tel_id);
    gi.loadMe();
  }
  function getTelInfo(tel_id) {
    var gi = this.infos[tel_id];
    if (gi == null) {
      this.infos[tel_id] = new telInfo(tel_id);
      gi = this.infos[tel_id];
    }
    return gi;
  }
}

function telInfo(tel_id) {
  this.tel_id=tel_id;
  this.loaded = false;
  this.loading = false;
  this.loadingShown = false;
  this.url = 'telinfo.php?telId='+this.tel_id
  this.my_div = document.getElementById('telinfo_'+this.tel_id);
  this.show = show; 
  this.showLoading = showLoading; 
  this.load = load; 
  this.loadMe = loadMe; 
  this.state = state; 
  this.fillDiv = fillDiv; 
  this.hide = hide; 

  function show() {
    this.my_div.style.display="";
    if (!this.loaded) {
      if (!this.loading) {
        this.showLoading();
  //      setTimeout("load()",1000);
      }
    }
  }

  function loadMe() {
    if (!(this.loaded || this.loading)) {
      this.load();
    }
  }

  function showLoading() {
    if (!this.loadingShown) {
      this.loadingShown = true;
      this.my_div.innerHTML='<img src="images/loading.gif" alt="loading"/><span class="jsonKey"><? echo _MsgJsonLoading ?></span>';
    }
  }

  function load() {
    this.xmlhttp=null
    // code for Mozilla, etc.
    if (window.XMLHttpRequest) {
      xmlhttp=new XMLHttpRequest()
    } else if (window.ActiveXObject) {
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP")
    }
    if (xmlhttp != null) {
      this.loading=true;
      xmlhttp.onreadystatechange=this.state
      xmlhttp.open("GET",this.url,true)
      xmlhttp.send(null)
    } else {
      alert("Your browser does not support XMLHTTP.")
    }
  }

  function hide() {
    this.my_div.style.display="none";
  }

  function state() {
    if (xmlhttp.readyState==4) {
      if (xmlhttp.status==200) {
        // alert(xmlhttp.responseText);
        this.data = eval('(' + xmlhttp.responseText + ')');
        // alert(data);
        this.loaded=true;
        fillDiv(this.data);
      } else {
        alert("Problem retrieving XML data "+xmlhttp.statusText);
      }
    }
  }
}
  function fillDiv(obj) {
    var div = document.getElementById('telinfo_'+obj.tel_id);
    var inner = '<table class="telInfo" width="450pt" style="white-space: nowrap">';
    inner += '<tr><td class="jsonKey"><? echo _MsgJsonTelName ?></td><td class="jsonVal">'+obj.tel_name+'</td></tr>';
    if (obj.tel_series != null && obj.tel_series != '') {
      inner += '<tr><td class="jsonKey"><? echo _MsgJsonTelSeries ?></td><td class="jsonVal">'+obj.tel_series+'</td></tr>';
    }
    if (obj.tel_episode != null && obj.tel_episode != '') {
      inner += '<tr><td class="jsonKey"><? echo _MsgJsonTelEpisode ?></td><td class="jsonVal">'+obj.tel_episode+'</td></tr>';
    }
    if (obj.tel_part != null && obj.tel_part != '') {
      inner += '<tr><td class="jsonKey"><? echo _MsgJsonTelPart ?></td><td class="jsonVal">'+obj.tel_part+'</td></tr>';
    }
    if (obj.chn_name != null && obj.chn_name != '') {
      inner += '<tr><td class="jsonKey"><? echo  _MsgJsonChnName ?></td><td class="jsonVal">'+obj.chn_name+'</td></tr>';
    }
    if (obj.grb_name != null && obj.grb_name != '') {
      inner += '<tr><td class="jsonKey"><? echo  _MsgJsonGrbName ?></td><td class="jsonVal">'+obj.grb_name+'</td></tr>';
    }
    if (obj.tel_date_start != null && obj.tel_date_start != '') {
      inner += '<tr><td class="jsonKey"><? echo  _MsgJsonTelDateStart ?></td><td class="jsonVal">'+obj.tel_date_start+'</td></tr>';
    }
    if (obj.tel_date_end != null && obj.tel_date_end != '') {
      inner += '<tr><td class="jsonKey"><? echo  _MsgJsonTelDateEnd ?></td><td class="jsonVal">'+obj.tel_date_end+'</td></tr>';
    }
    if (obj.grb_date_start != null && obj.grb_date_start != '') {
      inner += '<tr><td class="jsonKey"><? echo  _MsgJsonGrbDateStart ?></td><td class="jsonVal">'+obj.grb_date_start+'</td></tr>';
    }
    if (obj.grb_date_end != null && obj.grb_date_end != '') {
      inner += '<tr><td class="jsonKey"><? echo  _MsgJsonGrbDateEnd ?></td><td class="jsonVal">'+obj.grb_date_end+'</td></tr>';
    }
    for(i in obj.req_outputs) {
      inner +=  '<tr><td class="jsonKey"><? echo  _MsgJsonReqOutputEnc ?>: '+obj.req_outputs[i].enc;
      inner +=  '<br />&nbsp;&nbsp;'+writeStatusText(obj.req_outputs[i].status)+'</td><td class="jsonVal">';
      if (obj.req_outputs[i].filename != null && obj.req_outputs[i].filename != '') {
        inner += '<br /><? echo _MsgJsonReqOutput ?>: '+obj.req_outputs[i].filename;
      }
      if (obj.req_outputs[i].size != null && obj.req_outputs[i].size != '' && obj.req_outputs[i].size != '0') {
        inner += '<br /><? echo _MsgJsonReqOutputSize ?>: '+obj.req_outputs[i].size+'MB';
      }
      if (obj.req_outputs[i].md5 != null && obj.req_outputs[i].md5 != '') {
        inner += '<br /><? echo _MsgJsonReqOutputMd5 ?>: '+obj.req_outputs[i].md5;
      }
      inner += '</td></tr>';
    }
    inner += '</table>';
    div.innerHTML=inner;
  }

  function writeStatusText(text) {
    switch(text) {
      case "scheduled" : return '<?php echo _MsgStatusScheduled ?>';
      case "saving" : return '<?php echo _MsgStatusSaving ?>';
      case "saved" : return '<?php echo _MsgStatusSaved ?>';
      case "encoding" : return '<?php echo _MsgStatusEncoding ?>';
      case "encoded" : return '<?php echo _MsgStatusEncoded ?>';
      case "done" : return '<?php echo _MsgStatusDone ?>';
      case "deleted" : return '<?php echo _MsgStatusDeleted ?>';
      case "missed" : return '<?php echo _MsgStatusMissed ?>';
      case "error" : return '<?php echo _MsgStatusError ?>';
      case "undefined" : return '<?php echo _MsgStatusUndefined ?>';
    }
  }
// -->
</script>
