/***************************************************************************
 *   Copyright (C) 2008 by phpSysInfo - A PHP System Information Script    *
 *   http://phpsysinfo.sourceforge.net/                                    *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
 ***************************************************************************/
//
// $Id: phpsysinfo.js,v 1.48 2008/05/31 22:09:03 bigmichi1 Exp $
//

var cookie_language = readCookie("language");
var cookie_template = readCookie("template");
var plugin_liste = new Array();
if(cookie_template) switchStyle(cookie_template);

$(document).ready(function() {
  $.ajax({
    url: 'xml.php',
    dataType: 'xml',
    error: function(){
      alert('Error loading XML document');
    },
    success: function(xml){
      populateErrors(xml);
      populateVitals(xml);
      populateNetwork(xml);
      populateMemory(xml);
      populateFilesystems(xml);
      populateHardware(xml);
      populateTemp(xml);
      populateVoltage(xml);
      populateFan(xml);
      populateHddtemp(xml);
      populateUps(xml);
      displayPage(xml);
      getLanguage(cookie_language);
    }
  });

  $("#errors").nyroModal();

  $("#lang").change(function(){
    var language
    language = $("#lang").val();
    getLanguage(language);
    for(i = 0; i < plugin_liste.length; i++) {
      getLanguage(language, plugin_liste[i]);
    }
    return false;
  });

  $("#template").change(function(){
    switchStyle($("#template").val());
    return false;
  });

  $("#sPci").click(function(){
    $("#pciTable").slideDown("slow");
    $("#sPci").hide();
    $("#hPci").show();
  });
  $("#hPci").click(function(){
    $("#pciTable").slideUp("slow");
    $("#hPci").hide();
    $("#sPci").show();
  });

  $("#sIde").click(function(){
    $("#ideTable").slideDown("slow");
    $("#sIde").hide();
    $("#hIde").show();
  });
  $("#hIde").click(function(){
    $("#ideTable").slideUp("slow");
    $("#hIde").hide();
    $("#sIde").show();
  });

  $("#sScsi").click(function(){
    $("#scsiTable").slideDown("slow");
    $("#sScsi").hide();
    $("#hScsi").show();
  });
  $("#hScsi").click(function(){
    $("#scsiTable").slideUp("slow");
    $("#hScsi").hide();
    $("#sScsi").show();
  });

  $("#sUsb").click(function(){
    $("#usbTable").slideDown("slow");
    $("#sUsb").hide();
    $("#hUsb").show();
  });
  $("#hUsb").click(function(){
    $("#usbTable").slideUp("slow");
    $("#hUsb").hide();
    $("#sUsb").show();
  });
});


function getLanguage(lang, plugin) {
     if(lang != null) {
       createCookie('language', lang, 365);
       getLangUrl = 'language/language.php?lang=' + lang;
       if(plugin != null) {
         getLangUrl += "&plugin=" + plugin;
       }
     } else {
       getLangUrl = 'language/language.php';
     }
     $.ajax({
      url: getLangUrl,
      type: 'GET',
      dataType: 'xml',
      timeout: 100000,
      error: function(){
        alert('Error loading language.');
      },
      success: function(xml) {
        changeLanguage(xml);
      }
  });
}

function changeLanguage(lang) {
  $("[@lang]").each(function(i) {
    langId = this.lang;
    langStr = $("string[@id="+langId+"]",lang);
    if(langStr.length > 0) {
      this.innerHTML = langStr.text();
    }
  });
}

function switchStyle(template) {
  $('link[@rel*=style][@title]').each(function(i) {
    this.disabled = true;
    if (this.getAttribute('title') == template) this.disabled = false;
  });
  createCookie('template', template, 365);
}

function populateVitals(xml) {
  var title;
  $("Vitals",xml).each(function(id) {
    vital = $("Vitals",xml).get(id);
    hostname = $("Hostname",vital).text();
    ip       = $("IPAddr",vital).text();
    kernel   = $("Kernel",vital).text();
    distro   = $("Distro",vital).text();
    icon     = $("Distroicon",vital).text();
    uptime   = uptime($("Uptime",vital).text());
    users    = $("Users",vital).text();
    loadavg  = $("LoadAvg",vital).text();
    if($("CPULoad",vital).length == 1) {
      cpuload = $("CPULoad",vital).text();
      loadavg = loadavg + createBar(cpuload);
    }
    document.title = "System information: " + hostname + " (" + ip + ")";
    title = "<span lang='001'>System information</span>: " + hostname + " (" + ip + ")";
    $("#title").append(title);
    $("#vitalsTable").append("<tr><td lang='003' style='width:160px;'>Hostname</td><td>" + hostname + "</td></tr>");
    $("#vitalsTable").append("<tr><td lang='004' style='width:160px;'>Listening IP</td><td>" + ip + "</td></tr>");
    $("#vitalsTable").append("<tr><td lang='005' style='width:160px;'>Kernel Version</td><td>" + kernel + "</td></tr>");
    $("#vitalsTable").append("<tr><td lang='006' style='width:160px;'>Distro Name</td><td><img src='images/" + icon + "' alt='' height='16' width='16' style='vertical-align:middle;'> " + distro + "</td></tr>");
    $("#vitalsTable").append("<tr><td lang='007' style='width:160px;'>Uptime</td><td>" + uptime + "</td></tr>");
    $("#vitalsTable").append("<tr><td lang='008' style='width:160px;'>Current Users</td><td>" + users + "</td></tr>");
    $("#vitalsTable").append("<tr><td lang='009' style='width:160px;'>Load Averages</td><td>" + loadavg + "</td></tr>");
  });
}

function populateNetwork(xml) {
  var network;
  var errors;
  $("#networkTable").append("<tr><th lang='022'>Interface</th><th class=\"right\" lang='023' style='width:60px;'>Recieved</th><th class=\"right\" lang='024' style='width:60px;'>Transfered</th><th class=\"right\" lang='025' style='width:60px;'>Error/Drops</th></tr>");
  $("Network",xml).each(function(id) {
    network = $("Network",xml).get(id);
    $("NetDevice",network).each(function(did) {
      device = $("NetDevice",network).get(did);
      name   = $("Name",device).text();
      rx     = $("RxBytes",device).text();
      tx     = $("TxBytes",device).text();
      errors = $("Err",device).text();
      drops  = $("Drops",device).text();
      $("#networkTable").append("<tr><td>" + name + "</td><td class=\"right\">" + formatBytes(rx/1024, xml) + "</td><td class=\"right\">" + formatBytes(tx/1024, xml) + "</td><td class=\"right\">" + errors + "/" + drops + "</td></tr>");
    });
  });
}

function displayPage(xml) {  
  $("#template").val(cookie_template);
  $("#lang").val(cookie_language);
  $("#loader").hide();
  $('.stripeMe tr:nth-child(even)').addClass('odd');
  $("#container").fadeIn("slow");
  versioni = $("Generation", xml).attr("version");
  $("#version").append(versioni);
}

function populateMemory(xml) {
  $("#memoryTable").append("<tr><th lang='034' style='width:200px;'>Type</th><th lang='033' style=\"width:285px;\">Percent</th><th class=\"right\" lang='035' style='width:100px;'>Free</th><th class=\"right\" lang='036' style='width:100px;'>Used</th><th class=\"right\" lang='037' style='width:100px;'>Total</th></tr>");
  $("Memory",xml).each(function(id) {
    vital = $("Memory",xml).get(id);
    free    = $("Free",vital).text();
    used    = $("Used",vital).text();
    total   = $("Total",vital).text();
    percent = $("Percent",vital).text();

    if($("App", vital).length > 0) {
      memSwitch = "<span style=\"cursor: pointer\" id=\"sMem\"><img src=\"gfx/bullet_toggle_plus.png\" alt=\"plus\" style=\"vertical-align:middle;\" /></span>";
      memSwitch = memSwitch + "<span style=\"cursor: pointer; display: none;\" id=\"hMem\"><img src=\"gfx/bullet_toggle_minus.png\" alt=\"minus\" style=\"vertical-align:middle;\" /></span>";
    } else {
      memSwitch = "";
    }
    $("#memoryTable").append("<tr><td style=\"width:200px;\">" + memSwitch + "<span lang='028'>Physical Memory</span></td><td style=\"width:285px;\">" + createBar(percent) + "</td><td class=\"right\" style=\"width:100px;\">" + formatBytes(free, xml) + "</td><td class=\"right\" style=\"width:100px;\">" + formatBytes(used, xml) + "</td><td class=\"right\" style=\"width:100px;\">" + formatBytes(total, xml) + "</td></tr>");

    if($("App", vital).length > 0) {
      $("#sMem").click(function(){
        $("#MemTable").slideDown("slow");
        $("#sMem").hide();
        $("#hMem").show();
      });
      $("#hMem").click(function(){
        $("#MemTable").slideUp("slow");
        $("#hMem").hide();
        $("#sMem").show();
      });
      app     = $("App", vital).text();
      appp    = $("AppPercent", vital).text();
      buff    = $("Buffers", vital).text();
      buffp   = $("BuffersPercent", vital).text();
      cached  = $("Cached", vital).text();
      cachedp = $("CachedPercent", vital).text();
      $("#MemTable").append("<tr><td style=\"width:184px;padding-left:26px;\"><span lang='064'>Kernel + applications</span></td><td style=\"width:285px;\">" + createBar(appp) + "</td><td class=\"right\" style=\"width:100px;\">&nbsp;</td><td class=\"right\" style=\"width:100px\">" + formatBytes(app, xml) + "</td><td class=\"right\" style=\"width:100px;\">&nbsp;</td></tr>");
      $("#MemTable").append("<tr><td style=\"width:184px;padding-left:26px;\"><span lang='065'>Buffers</span></td><td style=\"width:285px\">" + createBar(buffp) + "</td><td class=\"rigth\" style=\"width:100px;\">&nbsp;</td><td class=\"right\" style=\"width:100px;\">" + formatBytes(buff, xml) + "</td><td class=\"right\" style=\"width:100px;\">&nbsp;</td></tr>");
      $("#MemTable").append("<tr><td style=\"width:184px;padding-left:26px;\"><span lang='066'>Cached</span></td><td style=\"width:285px;\">" + createBar(cachedp) + "</td><td class=\"right\" style=\"width:100px;\">&nbsp;</td><td class=\"right\" style=\"width:100px;\">" + formatBytes(cached, xml) + "</td><td class=\"right\" style=\"width:100px;\">&nbsp;</td></tr>");
    }
  });

  $("Swap",xml).each(function(id) {
    vital = $("Swap",xml).get(id);
    if($("Total",vital).length > 0) {
      free    = $("Free",vital).text();
      used    = $("Used",vital).text();
      total   = $("Total",vital).text();
      percent = $("Percent",vital).text();
      if($("Swapdevices",xml).length > 0) {
        swapSwitch = "<span style=\"cursor: pointer\" id=\"sSwap\"><img src=\"gfx/bullet_toggle_plus.png\" alt=\"plus\" style=\"vertical-align:middle;\" /></span>";
        swapSwitch = swapSwitch + "<span style=\"cursor: pointer; display: none;\" id=\"hSwap\"><img src=\"gfx/bullet_toggle_minus.png\" alt=\"minus\" style=\"vertical-align:middle;\" /></span>";
      } else {
        swapSwitch = "";
      }
      $("#swapTable").append("<tr><td style=\"width:200px;\">" + swapSwitch + "<span lang='029'>Disk swap</span></td><td style=\"width:285px;\">" + createBar(percent) + "</td><td class=\"right\" style=\"width:100px;\">" + formatBytes(free, xml) + "</td><td class=\"right\" style=\"width:100px;\">" + formatBytes(used, xml) + "</td><td class=\"right\" style=\"width:100px;\">" + formatBytes(total, xml) + "</td></tr>");
      if($("Swapdevices", xml).length > 0) {
        $("#sSwap").click(function(){
          $("#swapdevTable").slideDown("slow");
          $("#sSwap").hide();
          $("#hSwap").show();
        });
        $("#hSwap").click(function(){
          $("#swapdevTable").slideUp("slow");
          $("#hSwap").hide();
          $("#sSwap").show();
        });
      }
      $("Swapdevices",xml).each(function(id) {
        alldev = $("Swapdevices",xml).get(id);
        $("Mount",alldev).each(function(id) {
          dev = $("Mount",alldev).get(id);
          free = $("Free",dev).text();
          used = $("Used",dev).text();
          total = $("Size",dev).text();
          percent = $("Percent",dev).text();
          $("Device",dev).each(function(id) {
            devname = $("Device",dev).get(id);
            name = $("Name", devname).text();
          });
          $("#swapdevTable").append("<tr><td style=\"width:184px;padding-left:26px;\">" + name + "</td><td style=\"width:285px;\">" + createBar(percent) + "</td><td class=\"right\" style=\"width:100px\">" + formatBytes(free, xml) + "</td><td class=\"right\" style=\"width:100px;\">" + formatBytes(used, xml) + "</td><td class=\"right\" style=\"width:100px;\">" + formatBytes(total, xml) + "</td></tr>");
        });
      });
    }
  });
}

function populateFilesystems(xml) {
  var total_usage = 0;
  var total_used = 0;
  var total_free = 0;
  var total_size = 0;
  var inodes_text = "";
  var filesystem;

  $("#filesystemTable").append("<thead><tr><th lang='031' style='width:100px;'>Mount</th><th lang='034' style='width:50px;'>Type</th><th lang='032' style='width:120px;'>Partition</th><th lang='033'>Percent used</th><th class=\"right\" lang='035' style='width:100px;'>Free</th><th class=\"right\" lang='036' style='width:100px;'>Used</th><th class=\"right\" lang='037' style='width:100px;'>Total</th></tr></thead><tfoot></tfoot>");
  $("FileSystem",xml).each(function(id) {
    filesystem = $("FileSystem",xml).get(id);
    $("Mount",filesystem).each(function(mid) {
      mount = $("Mount",filesystem).get(mid);
      $("Device",mount).each(function(did) {
        dev = $("Device",mount).get(did);
        name = $("Name",dev).text();
      });
      mpid    = $("MountPointID",mount).text();
      mpoint  = $("MountPoint",mount).text();
      type    = $("Type",mount).text();
      percent = $("Percent",mount).text();
      free    = $("Free",mount).text();
      used    = $("Used",mount).text();
      size    = $("Size",mount).text();
      inodes  = $("Inodes",mount).text();

      if(mpoint == "")	mpoint = mpid;
      if(inodes != "")	inodes_text = "<span style='font-style:italic'>&nbsp;(" + inodes + "%)</span>";

      $("#filesystemTable").append("<tr><td val=\"" + mpoint + "\">" + mpoint + "</td><td val=\"" + type + "\">" + type + "</td><td val=\"" + name + "\">" + name + "</td><td val=\"" + percent + "\">" + createBar(percent) + inodes_text + "</td><td class=\"right\" val=\"" + free + "\">" + formatBytes(free, xml) + "</td><td class=\"right\" val=\"" + used + "\">" + formatBytes(used, xml) + "</td><td class=\"right\" val=\"" + size + "\">" + formatBytes(size, xml) + "</td></tr>");

      total_used += parseInt(used);
      total_free += parseInt(free);
      total_size += parseInt(size);
      total_usage = Math.round( (total_used / total_size) * 100 );
    });

    $("#filesystemTable tfoot").append("<tr style='font-weight : bold'><td>&nbsp;</td><td>&nbsp;</td><td lang='038'>Totals</td><td>" + createBar(total_usage) + "</td><td class=\"right\">" + formatBytes(total_free, xml) + "</td><td class=\"right\">" + formatBytes(total_used, xml) + "</td><td class=\"right\">" + formatBytes(total_size, xml) + "</td></tr>");
  });

  var myTextExtraction = function(node)
  {
    if($(node).attr("val"))
      return $(node).attr("val");
    else
      return "";
  }

  if($("#filesystemTable tbody tr").length >0)
  {
    $("#filesystemTable").tablesorter({
      textExtraction: myTextExtraction,
      widgets: ['zebra']
    });
  }
}

function populateHardware(xml) {
  var hardware;
  $("Hardware",xml).each(function(id) {
    hardware = $("Hardware",xml).get(id);
    $("CPU",hardware).each(function(id) {
      cpu = $("CPU",hardware).get(id);
      num   = $("Number",cpu).text();
      model = $("Model",cpu).text();
      speed = $("Cpuspeed",cpu).text();
      bus   = $("Busspeed",cpu).text();
      cache = $("Cache",cpu).text();
      bogo  = $("Bogomips",cpu).text();
      $("#cpuTable").append("<tr><td lang='011' style='width:160px'>Processors</td><td>" + num + "</td></tr>");
      $("#cpuTable").append("<tr><td lang='012' style='width:160px'>Model</td><td>" + model + "</td></tr>");
      $("#cpuTable").append("<tr><td lang='013' style='width:160px'>CPU Speed</td><td>" + formatHertz(speed) + "</td></tr>");
      $("#cpuTable").append("<tr><td lang='014' style='width:160px'>BUS Speed</td><td>" + formatHertz(bus) + "</td></tr>");
      $("#cpuTable").append("<tr><td lang='015' style='width:160px'>Cache Size</td><td>" + cache + "</td></tr>");
      $("#cpuTable").append("<tr><td lang='016' style='width:160px'>Bogomips</td><td>" + bogo + "</td></tr>");
    });
    popDevices('PCI:', 'pciTable', 'PCI', hardware);
    popDevices('IDE:', 'ideTable', 'IDE', hardware);
    popDevices('SCSI:', 'scsiTable', 'SCSI', hardware);
    popDevices('USB:', 'usbTable', 'USB', hardware);
  });
}

function popDevices(header, table, type, xml) {
  var text = '';
  $(type,xml).each(function(id) {
    alldev = $(type,xml).get(id);
    $("Device",alldev).each(function(id) {
      dev = $("Device",alldev).get(id);
      text = text + "<li>" + $("Name",dev).text();
      capacity = $("Capacity",dev).length;
      if(capacity > 0) {
        text = text + "&nbsp;(" + formatBytes($("Capacity",dev).text(),xml) + ")";
      }
      text = text +"</li>";
    });
  });
  if(text == "") {
    $("#" + table).append("<tr><td><ul><li><span lang='042'>none</span></li></ul></td></tr>");
  } else {
    $("#" + table).append("<tr><td><ul>" + text + "</ul></td></tr>" );
  }
}

function checkForVoltage(xml) {
  var voltage;
  voltage = $("Voltage",xml).length;
  if(voltage > 0) {
    return true;
  }
  return false;
}

function checkForTemp(xml) {
  var voltage;
  voltage = $("Temperature",xml).length;
  if(voltage > 0) {
    return true;
  }
  return false;
}

function checkForFan(xml) {
  var fan;
  fan = $("Fans",xml).length;
  if(fan > 0) {
    return true;
  }
  return false;
}

function checkForHddtemp(xml) {
  var voltage;
  voltage = $("HDDTemp",xml).length;
  if(voltage > 0) {
    return true;
  }
  return false;
}

function checkForUPSinfo(xml) {
  var voltage;
  voltage = $("UPSinfo",xml).length;
  if(voltage > 0) {
    return true;
  }
  return false;
}

function populateVoltage(xml) {
  var voltage, item;
  if(checkForVoltage(xml)) {
    $("Voltage",xml).each(function(id) {
      voltage = $("Voltage",xml).get(id);
      $("Item",voltage).each(function(iid) {
        item = $("Item",voltage).get(iid);
        label = $("Label",item).text();
        value = $("Value",item).text();
        max   = $("Max",item).text();
        min   = $("Min",item).text();
        $("#voltageTable").append("<tr><td>" + label + "</td><td class=\"right\">" + round(value,2) + "&nbsp;<span lang='062'>V</span></td><td class=\"right\">" + round(min,2) + "&nbsp;<span lang='062'>V</span></td><td class=\"right\">" + round(max,2) + "&nbsp;<span lang='062'>V</span></td></tr>");
      });
    });
    $("#voltage").show();
  }
}

function populateTemp(xml) {
  var temp, item;
  if(checkForTemp(xml)) {
    $("Temperature",xml).each(function(id) {
      temp = $("Temperature",xml).get(id);
      $("Item",temp).each(function(iid) {
        item = $("Item",temp).get(iid);
        label = $("Label",item).text();
        value = $("Value",item).text();
        limit = $("Limit",item).text();
        value = value.replace(/\+/g,"");
        limit = limit.replace(/\+/g,"");
        $("#tempTable").append("<tr><td>" + label + "</td><td class=\"right\">" + formatTemp(value, xml) + "</td><td class=\"right\">" + formatTemp(limit, xml) + "</td></tr>");
      });
    });
    $("#temp").show();
  }
}

function populateFan(xml) {
  var fan, item;
  if(checkForFan(xml)) {
    $("Fans",xml).each(function(id) {
      fan = $("Fans",xml).get(id);
      $("Item",fan).each(function(iid) {
        item = $("Item",fan).get(iid);
        label = $("Label",item).text();
        value = $("Value",item).text();
        min   = $("Min",item).text();
        $("#fanTable").append("<tr><td>" + label + "</td><td class=\"right\">" + value + "&nbsp;<span lang='063'>RPM</span></td><td class=\"right\">" + min + "&nbsp;<span lang='063'>RPM</span></td></tr>");
      });
    });
    $("#fan").show();
  }
}

function populateHddtemp(xml) {
  var temp, item;
  if(checkForHddtemp(xml)) {
    $("HDDTemp",xml).each(function(id) {
      temp = $("HDDTemp",xml).get(id);
      $("Item",temp).each(function(iid) {
        item = $("Item",temp).get(iid);
        label = $("Label",item).text();
        value = $("Value",item).text();
        model = $("Model",item).text();
        if(value != 'NA') {
          $("#tempTable").append("<tr><td>" + model + "</td><td class=\"right\">" + formatTemp(value, xml) + "</td><td>&nbsp;</td></tr>");
        }
      });
    });
    $("#temp").show();
  }
}

function populateUps(xml) {
  var upses, ups;

  if(checkForUPSinfo(xml)) {
    $("UPSinfo",xml).each(function(id) {
      upses = $("UPSinfo",xml).get(id);
      $("Ups", upses).each(function(did) {
        ups = $("Ups", upses).get(did);
        name = $("Name", ups).text();
        model = $("Model", ups).text();
        mode = $("Mode", ups).text();
        start_time = $("StartTime", ups).text();
        upsstatus = $("Status", ups).text();
        temperature = $("UPSTemperature", ups).text();
        outages_count = $("OutagesCount", ups).text();
        last_outage = $("LastOutage", ups).text();
        last_outage_finish = $("LastOutageFinish", ups).text();
        line_voltage = $("LineVoltage", ups).text();
        load_percent = $("LoadPercent", ups).text();
        battery_voltage = $("BatteryVoltage", ups).text();
        battery_charge_percent = $("BatteryChargePercent", ups).text();
        time_left_minutes = $("TimeLeftMinutes", ups).text();

        $("#upsTable").append('<tr><th colspan="2" style="text-align: center"><strong>' + name + ' (' + mode + ')</strong></th></tr>');
        $("#upsTable").append('<tr><td style="width:160px" lang="070">Model</td><td>' + model + '</td></tr>');
        $("#upsTable").append('<tr><td style="width:160px" lang="072">Started</td><td>' + start_time + '</td></tr>');
        $("#upsTable").append('<tr><td style="width:160px" lang="073">Status</td><td>' + upsstatus + '</td></tr>');

        if (temperature != '') $("#upsTable").append('<tr><td style="width:160px" lang="084">Temperature</td><td>' + temperature + '</td></tr>');
        if (outages_count != '') $("#upsTable").append('<tr><td style="width:160px" lang="074">Outages</td><td>' + outages_count + '</td></tr>');
        if (last_outage != '') $("#upsTable").append('<tr><td style="width:160px" lang="075">Last outage cause</td><td>' + last_outage + '</td></tr>');
        if (last_outage_finish != '') $("#upsTable").append('<tr><td style="width:160px" lang="076">Last outage timestamp</td><td>' + last_outage_finish + '</td></tr>');
        if (line_voltage != '') $("#upsTable").append('<tr><td style="width:160px" lang="077">Line voltage</td><td>' + line_voltage + '&nbsp;<span lang="082">V</span></td></tr>');
        if (load_percent != '') $("#upsTable").append('<tr><td style="width:160px" lang="078">Load percent</td><td>' + createBar(load_percent) + '</td></tr>');
        if (battery_voltage != '') $("#upsTable").append('<tr><td style="width:160px" lang="079">Battery voltage</td><td>' + battery_voltage + '&nbsp;<span lang="082">V</span></td></tr>');
        $("#upsTable").append('<tr><td style="width:160px" lang="080">Battery charge</td><td>' + createBar(battery_charge_percent) + '</td></tr>');
        $("#upsTable").append('<tr><td style="width:160px" lang="081">Time left on batteries</td><td>' + time_left_minutes + '&nbsp;<span lang="083">minutes</span></td></tr>');
      });
    });
    $("#ups").show();
  } //if we have upses
}

function populateErrors(xml) {
  var errors;
  var error;
  var message;
  var fn;  
  errors = $("Error",xml).length;
  if(errors > 0) {
    $("Error",xml).each(function(id) {
      error = $("Error",xml).get(id);
      message = $("Message",error).text();
      fn = $("Function",error).text();
      $("#errorlist").append("<b>" + fn + "</b><br/><br/><pre>" + message + "</pre><hr>");
    });
    $("#warn").css("display", "inline");
  }
}

function uptime(sec) {
  txt = '';
  intMin = sec / 60;
  intHours = intMin / 60;
  intDays = Math.floor(intHours/24);
  intHours = Math.floor(intHours-(intDays*24));
  intMin = Math.floor(intMin-(intDays*60*24)-(intHours*60));

  if(intDays != 0 ) {
    txt = txt + intDays + "&nbsp;<span lang='048'>days</span> ";
  }
  if(intHours != 0 ) {
    txt = txt + intHours + "&nbsp;<span lang='049'>hours</span> ";
  }
  txt = txt +  intMin + "&nbsp;<span lang='050'>minutes</span>";
  return txt;
}

function formatBytes(kbytes, xml) {
  var byteFormat;

  $("Options",xml).each(function(id) {
    options = $("Options",xml).get(id);
    byteFormat = $("byteFormat",options).text().toUpperCase();
  });

  switch( byteFormat ){
    case "G":
      show = round(kbytes/1048576, 2);
      show += "&nbsp;<span lang='041'>GB</span>";
      break;
    case "M":
      show = round(kbytes/1024, 2); 
      show += "&nbsp;<span lang='040'>MB</span>";
      break;
    case "K":
      show = round(kbytes, 2);
      show += "&nbsp;<span lang='039'>KB</span>";
      break;
    default:
      if(kbytes > 1048576) {
        show = round(kbytes/1048576, 2);
        show += "&nbsp;<span lang='041'>GB</span>";
      } else if(kbytes > 1024) {
        show = round(kbytes/1024, 2); 
        show += "&nbsp;<span lang='040'>MB</span>";
      } else {    
        show = round(kbytes, 2);
        show += "&nbsp;<span lang='039'>KB</span>";
     }
  }
  return show;
}

function formatHertz(mhertz) {
  if(mhertz != "" && mhertz < 1000) {
    return mhertz + "&nbsp;Mhz";
  } else if(mhertz != "" && mhertz >=1000) {
    return  Math.round(mhertz/1000*100)/100 + "&nbsp;GHz";
  } else {
    return "";
  }
}

function formatTemp(degreeC, xml){
  var tempFormat;
  var degreeF = ( ( 9 * degreeC ) / 5 ) + 32;

  $("Options",xml).each(function(id) {
    options = $("Options",xml).get(id);
    tempFormat = $("tempFormat",options).text();
  });

  if (parseFloat(1 * degreeC).toString() == "NaN") return "---";

  if(tempFormat == "f"){
    return round(degreeF,1) + "&nbsp;<span lang='061'>F</span>";
  }
  else if(tempFormat == "c-f"){
    return round(degreeC,1) + "&nbsp;<span lang='060'>C</span>&nbsp;(" + round(degreeF,1) + "&nbsp;<span lang='061'>F</span>)";
  }
  else if(tempFormat == "f-c"){
    return round(degreeF,1) + "&nbsp;<span lang='061'>F</span>&nbsp;(" + round(degreeC,1) + "&nbsp;<span lang='060'>C</span>)";
  }
  else{
    return round(degreeC,1) + "&nbsp;<span lang='060'>C</span>";
  }
}

function createBar(percent) {
  h = '<div class="bar" style="float:left; width: ' + percent + 'px "> &nbsp;</div> <div style="float: left">&nbsp; ' + percent + '%</div>';
  return h;
}

function round(x, n) {
  if (n < 1 || n > 14) return x;
  var e = Math.pow(10, n);
  var k = (Math.round(x * e) / e).toString();
  if (k.indexOf('.') == -1) k += '.';
  k += e.toString().substring(1);
  return k.substring(0, k.indexOf('.') + n+1);
}

// cookie functions http://www.quirksmode.org/js/cookies.html
function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else var expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
  }

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}

function eraseCookie(name) {
  createCookie(name,"",-1);
}
// cookie functions

function buildBlock(plugin, headline, translationid, reload) {
  var block;
  var reloadpic = "";
  if(reload) {
    reloadpic = "<img id=\"reload_" + plugin + "Table\" src=\"./gfx/reload.png\" alt=\"reload\" style=\"vertical-align:middle;border=0px;\" />&nbsp;";
  }
  block  = "        <h2>" + reloadpic + "<span lang=\"plugin_" + plugin + "_" + translationid + "\">" + headline + "</span></h2>\n<span id=\"DateTime\" style=\"margin-left:10px;\"></span>";
  block += "        <table class=\"stripeMe\" id=\"plugin_" + plugin + "Table\" cellspacing=\"0\">\n";
  block += "        </table>\n";
  return block;
}

function plugin_translate(plugin) {
  plugin_liste.push(plugin);
  getLanguage(cookie_language,plugin);
}
