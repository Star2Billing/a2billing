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
// $Id: mdstat.js,v 1.3 2008/05/31 22:23:28 bigmichi1 Exp $
//

$(document).ready(function(){
  var show = false;

  $("#plugin_mdstat").append(buildBlock("mdstat", "RAID Status", "001", true));

  request();

  $("#reload_mdstatTable").click(function(id) {
    request();
    $("#DateTime").html("(<span lang=\"plugin_mdstat_016\">Last Refresh</span>:&nbsp;" + datetime() + ")");
  });

  function request(){
    $.ajax({
      url: "xml.php?plugin=mdstat",
      dataType: "xml",
      error: function() {
        alert("Error loading XML document for Plugin mdstat");
      },
      success: function(xml) {
        populateMdstat(xml);
        if(show) {
          plugin_translate("mdstat");
          $("#plugin_mdstat").show();
        }
      }
    });
  }

  function populateMdstat(xml) {
    var htmltypes = "";
    var htmldisks = "";
    var htmldisklist = "";
    var i = 0;
    $("#plugin_mdstatTable").html(" ");

    $("Plugins",xml).each(function(id){
      plugins = $("Plugins", xml).get(id);
      $("plugin_mdstat", plugins).each(function(id){
        mdstat = $("plugin_mdstat", plugins).get(id);
        $("Supported_Types", mdstat).each(function(id){
          types = $("Supported_Types", mdstat).get(id);
          $("Type", types).each(function(id){
            type = $("Type",types).get(id);
            html = $("Name",type).text();
            htmltypes += "<li>" + html + "</li>";
          });
        });
        if(htmltypes.length > 0) {
          htmltypes = "<ul>" + htmltypes + "</ul>";
          $("#plugin_mdstatTable").append("<tr><td style=\"width:160px;\"><span lang=\"plugin_mdstat_002\">Supported Types</span></td><td>" + htmltypes + "</td></tr>");
          show = true;
        };
        $("Device",mdstat).each(function(id){
          show = true;
          dev = $("Device",mdstat).get(id);
          name = $("Device_Name",dev).text();
          $("Disks",dev).each(function(id){
            disks = $("Disks",dev).get(id);
            htmldisklist += diskicon(disks);
          });
          htmldisks += "<table style=\"width:100%;\">";
          htmldisks += "<tr><td>" + htmldisklist + "</td></tr>";
          buildedaction = buildaction(dev);
          if(buildedaction != "") {
            htmldisks += "<tr><td>" + buildaction(dev) + "</td></tr>";
          }
          htmldisks += "<tr><td>" + buildinfos(dev,id) + "<td></tr>";
          htmldisks += "</table>";
          if(id>0) {
            topic = "";
          } else {
            topic = "<span lang=\"plugin_mdstat_003\">RAID-Devices</span>";
          }
          $("#plugin_mdstatTable").append("<tr><td>" + topic + "</td><td><div class=\"plugin_mdstat_biun\" style=\"text-align:left;\"><b>" + name + "</b></div>" + htmldisks + "</td></tr>")
          htmldisks = "";
          htmldisklist = "";
          $("#splugin_mdstat_info" + id).click(function(){
            $("#plugin_mdstat_InfoTable" + id).slideDown("slow");
            $("#splugin_mdstat_info" + id).hide();
            $("#hplugin_mdstat_info" + id).show();
          });
          $("#hplugin_mdstat_info" + id).click(function(){
            $("#plugin_mdstat_InfoTable" + id).slideUp("slow");
            $("#hplugin_mdstat_info" + id).hide();
            $("#splugin_mdstat_info" + id).show();
          });
        })
        if($("Unused_Devices",mdstat).length > 0) {
          $("#plugin_mdstatTable").append("<tr><td><span lang=\"plugin_mdstat_015\">Unused Devices</span></td><td>" + $("Unused_Devices",mdstat).text() + "</td></tr>");
          show = true;
        }
      });
    });
  }

  function diskicon(xml) {
    var html = "";
    $("Disk",xml).each(function(id) {
      disk = $("Disk",xml).get(id);
      html += "<div class=\"plugin_mdstat_biun\">";
      diskstatus = $("Status",disk).text();
      diskname = $("Name",disk).text();
      switch(diskstatus){
        case " ":
        case "":
          img = "harddriveok.png";
          alt = "ok";
          break;
        case "F":
          img = "harddrivefail.png";
          alt = "fail";
          break;
        case "S":
          img = "harddrivespare.png";
          alt = "spare";
          break;
        default:alert("--"+diskstatus+"--");
          img = "error.png";
          alt = "error";
          break;
      }
      html += "<img class=\"plugin_mdstat_biun\" src=\"./plugins/mdstat/gfx/" + img + "\" alt=\"" + alt + "\" />";
      html += "<small>" + diskname + "</small>";
      html += "</div>";
    });
    return html;
  }

  function buildinfos(xml,id) {
    var html = "";
    devstatus = $("Disk_Status",xml).text();
    devlevel  = $("Level",xml).text();
    devchunk  = $("Chunk_Size",xml).text();
    devsuper  = $("Persistent_Superblock",xml).text();
    devalgo   = $("Algorithm",xml).text();
    devactive = $("Disks_Active",xml).text();
    devregis  = $("Disks_Registered",xml).text();
    html += "<tr><td><span lang=\"plugin_mdstat_005\">Status</span></td><td>" + devstatus + "</td></tr>";
    html += "<tr><td><span lang=\"plugin_mdstat_006\">RAID-Level</span></td><td>" + devlevel + "</td></tr>";
    if(devchunk != -1) {
      html += "<tr><td><span lang=\"plugin_mdstat_007\">Chunk Size</span></td><td>" + devchunk + "K</td></tr>";
    }
    if(devalgo != -1) {
      html += "<tr><td><span lang=\"plugin_mdstat_008\">Algorithm</span></td><td>" + devalgo + "</td></tr>";
    }
    if(devsuper != -1) {
      html += "<tr><td><span lang=\"plugin_mdstat_009\">Persistent Superblock</span></td><td><span lang=\"lang_plugin_mdstat_010\">available</span></td></tr>";
    } else {
      html += "<tr><td><span lang=\"plugin_mdstat_009\">Persistent Superblock</span></td><td><span lang=\"lang_plugin_mdstat_011\">not available</span></td></tr>";
    }
    if(devactive != -1 && devregis != -1) {
      html += "<tr><td><span lang=\"plugin_mdstat_012\">Registered/Active Disks</span></td><td>" + devregis + "/" + devactive + "</td></tr>";
    }
    button  = "<h3 style=\"cursor: pointer\" id=\"splugin_mdstat_info" + id + "\"><img src=\"./gfx/bullet_toggle_plus.png\" alt=\"plus\" style=\"vertical-align:middle;\" /><span lang=\"plugin_mdstat_004\">Additional Infos</span></h3>";
    button += "<h3 style=\"cursor: pointer; display: none;\" id=\"hplugin_mdstat_info" + id + "\"><img src=\"./gfx/bullet_toggle_minus.png\" alt=\"minus\" style=\"vertical-align:middle;\" /><span lang=\"plugin_mdstat_004\">Additional Infos</span></h3>";
    button += "<table id=\"plugin_mdstat_InfoTable" + id + "\" cellspacing=\"0\" style=\"display: none;\">" + html + "</table>";
    return button;
  }

  function buildaction(xml) {
    var html = "";
    $("Action",xml).each(function(id){
      entry = $("Action",xml).get(id);
      action = $("Name",entry).text();
      if(action != -1 ) {
        time    = $("Time_To_Finish",entry).text();
        tunit   = $("Time_Unit",entry).text();
        percent = $("Percent",entry).text();
        html += "<div style=\"padding-left:10px;\">";
        html += "<span lang=\"plugin_mdstat_013\">Current action</span>:&nbsp;" + action + "<br/>";
        html += createBar(percent);
        html += "<br/>";
        html += "<span lang=\"plugin_mdstat_014\">Finishing in</span>&nbsp;" + time + "&nbsp;" + tunit;
        html += "</div>";
      }
    });
    return html;
  }

  function datetime() {
    var date = new (Date);
    var day = date.getDate();
    var month = date.getMonth();
    var year = date.getFullYear();
    var hour = date.getHours();
    var minute = date.getMinutes();
    if(minute < 10){
      minute = "0" + minute;
    }
    if(hour < 10){
      hour = "0" + hour;
    }
    return day + "." + month + "." + year + "&nbsp;" + hour + ":" + minute;
  }
});
