<?php
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
// $Id: xml.class.php,v 1.32 2008/05/31 20:38:58 bigmichi1 Exp $
//
class xml {
  private $sysinfo;
  private $mbinfo;
  private $hddtemp;
  private $upsinfo;
  private $xml;
  private $errors;
  private $plugins;
  private $plugin_request = false;
  private $complete_request = false;
  public function __construct($pluginname = "", $complete = false) {
    ($pluginname == "") ? $this->plugin_request = false : $this->plugin_request = true;
    ($complete) ? $this->complete_request = true : $this->complete_request = false;
    $this->errors = Error::singleton();
    if (!$this->plugin_request || $this->complete_request) {
      $this->sysinfo = new sysinfo();
      if (PSI_MBINFO) {
        $this->mbinfo = new mbinfo;
      }
      if (PSI_HDDTEMP) {
        $this->hddtemp = new hddtemp;
      }
      if (PSI_UPSINFO) {
        $this->upsinfo = new upsinfo;
      }
    }
    $this->xml = simplexml_load_string("<?xml version='1.0'?>\n<phpsysinfo></phpsysinfo>");
    $generation = $this->xml->addChild('Generation');
    $generation->addAttribute('version', PSI_VERSION);
    $generation->addAttribute('timestamp', time());
    $options = $this->xml->addChild('Options');
    $options->addChild('tempFormat', tempFormat);
    $options->addChild('byteFormat', byteFormat);
    if (!$this->plugin_request || $this->complete_request) {
      $this->plugins = explode(",", PSI_PLUGINS);
      $plug = $options->addChild('Used_Plugins');
      foreach($this->plugins as $plugin) {
        $plug->addChild('Plugin', $plugin);
      }
    } else {
      $this->plugins = array($pluginname);
    }
  }
  private function buildVitals() {
    $strLoadavg = '';
    $arrBuf = $this->sysinfo->loadavg(loadBar);
    foreach($arrBuf['avg'] as $strValue) {
      $strLoadavg.= $strValue . ' ';
    }
    $vitals = $this->xml->addChild('Vitals');
    if (useVhost === true) {
      $vitals->addChild('Hostname', utf8_encode(trim(htmlspecialchars($this->sysinfo->vhostname()))));
      $vitals->addChild('IPAddr', $this->sysinfo->vip_addr());
    } else {
      $vitals->addChild('Hostname', utf8_encode(trim(htmlspecialchars($this->sysinfo->chostname()))));
      $vitals->addChild('IPAddr', $this->sysinfo->ip_addr());
    }
    $vitals->addChild('Kernel', utf8_encode(trim(htmlspecialchars($this->sysinfo->kernel()))));
    $vitals->addChild('Distro', utf8_encode(trim(htmlspecialchars($this->sysinfo->distro()))));
    $vitals->addChild('Distroicon', utf8_encode(trim(htmlspecialchars($this->sysinfo->distroicon()))));
    $vitals->addChild('Uptime', $this->sysinfo->uptime());
    $vitals->addChild('Users', $this->sysinfo->users());
    $vitals->addChild('LoadAvg', $strLoadavg);
    if (isset($arrBuf['cpupercent'])) {
      $vitals->addChild('CPULoad', round($arrBuf['cpupercent'], 2));
    }
  }
  private function buildNetwork() {
    $arrNet = $this->sysinfo->network();
    $network = $this->xml->addChild('Network');
    $hideDevices = explode(',', hideNetworkInterface);
    foreach($arrNet as $strDev => $arrStats) {
      if (!in_array($strDev, $hideDevices)) {
        $device = $network->addChild('NetDevice');
        $device->addChild('Name', utf8_encode(trim(htmlspecialchars($strDev))));
        $device->addChild('RxBytes', $arrStats['rx_bytes']);
        $device->addChild('TxBytes', $arrStats['tx_bytes']);
        $device->addChild('Err', $arrStats['errs']);
        $device->addChild('Drops', $arrStats['drop']);
      }
    }
  }
  private function buildHardware() {
    $hardware = $this->xml->addChild('Hardware');
    $cpu = $hardware->addChild('CPU');
    $pci = $hardware->addChild('PCI');
    $ide = $hardware->addChild('IDE');
    $scsi = $hardware->addChild('SCSI');
    $usb = $hardware->addChild('USB');
    $arrSys = $this->sysinfo->cpu_info();
    $arrBuf = finddups($this->sysinfo->pci());
    if (count($arrBuf)) {
      for ($i = 0, $max = sizeof($arrBuf);$i < $max;$i++) {
        if ($arrBuf[$i]) {
          $tmp = $pci->addChild('Device');
          $tmp->addChild('Name', utf8_encode(trim(htmlspecialchars($arrBuf[$i]))));
        }
      }
    }
    $arrBuf = $this->sysinfo->ide();
    if (count($arrBuf)) {
      foreach($arrBuf as $strKey => $arrValue) {
        $tmp = $ide->addChild('Device');
        $tmp->addChild('Name', $strKey . ': ' . utf8_encode(trim(htmlspecialchars($arrValue['model']))));
        if (isset($arrValue['capacity'])) {
          $tmp->addChild('Capacity', $arrValue['capacity']);
        }
      }
    }
    $arrBuf = $this->sysinfo->scsi();
    if (count($arrBuf)) {
      foreach($arrBuf as $strKey => $arrValue) {
        $tmp = $scsi->addChild('Device');
        if ($strKey >= '0' && $strKey <= '9') {
          $tmp->addChild('Name', utf8_encode(trim(htmlspecialchars($arrValue['model']))));
        } else {
          $tmp->addChild('Name', $strKey . ': ' . utf8_encode(trim(htmlspecialchars($arrValue['model']))));
        }
        if (isset($arrrValue['capacity'])) {
          $tmp->addChild('Capacity', $arrValue['capacity']);
        }
      }
    }
    $arrBuf = finddups($this->sysinfo->usb());
    if (count($arrBuf)) {
      for ($i = 0, $max = sizeof($arrBuf);$i < $max;$i++) {
        if (trim($arrBuf[$i]) != "") {
          $tmp = $usb->addChild('Device');
          $tmp->addChild('Name', utf8_encode(trim(htmlspecialchars($arrBuf[$i]))));
        }
      }
    }
    if (isset($arrSys['cpus'])) {
      $cpu->addChild('Number', $arrSys['cpus']);
    }
    if (isset($arrSys['model'])) {
      $cpu->addChild('Model', utf8_encode(trim(htmlspecialchars($arrSys['model']))));
    }
    if (isset($arrSys['temp'])) {
      $cpu->addChild('Cputemp', $arrSys['temp']);
    }
    if (isset($arrSys['cpuspeed'])) {
      $cpu->addChild('Cpuspeed', $arrSys['cpuspeed']);
    }
    if (isset($arrSys['busspeed'])) {
      $cpu->addChild('Busspeed', $arrSys['busspeed']);
    }
    if (isset($arrSys['cache'])) {
      $cpu->addChild('Cache', $arrSys['cache']);
    }
    if (isset($arrSys['bogomips'])) {
      $cpu->addChild('Bogomips', $arrSys['bogomips']);
    }
  }
  private function buildMemory() {
    $arrMem = $this->sysinfo->memory();
    $i = 0;
    $memory = $this->xml->addChild('Memory');
    $memory->addChild('Free', $arrMem['ram']['free']);
    $memory->addChild('Used', $arrMem['ram']['used']);
    $memory->addChild('Total', $arrMem['ram']['total']);
    $memory->addChild('Percent', $arrMem['ram']['percent']);
    if (isset($arrMem['ram']['app'])) {
      $memory->addChild('App', $arrMem['ram']['app']);
      $memory->addChild('AppPercent', $arrMem['ram']['app_percent']);
      $memory->addChild('Buffers', $arrMem['ram']['buffers']);
      $memory->addChild('BuffersPercent', $arrMem['ram']['buffers_percent']);
      $memory->addChild('Cached', $arrMem['ram']['cached']);
      $memory->addChild('CachedPercent', $arrMem['ram']['cached_percent']);
    }
    $swap = $this->xml->addChild('Swap');
    if (count($arrMem['devswap']) > 0) {
      $swap->addChild('Free', $arrMem['swap']['free']);
      $swap->addChild('Used', $arrMem['swap']['used']);
      $swap->addChild('Total', $arrMem['swap']['total']);
      $swap->addChild('Percent', $arrMem['swap']['percent']);
    }
    $swapDev = $this->xml->addChild('Swapdevices');
    foreach($arrMem['devswap'] as $arrDevice) {
      $swapMount = $swapDev->addChild('Mount');
      $swapMount->addChild('MountPointID', $i++);
      $swapMount->addChild('Type', 'Swap');
      $dev = $swapMount->addChild('Device');
      $dev->addChild('Name', utf8_encode(trim(htmlspecialchars($arrDevice['dev']))));
      $swapMount->addChild('Percent', $arrDevice['percent']);
      $swapMount->addChild('Free', $arrDevice['free']);
      $swapMount->addChild('Used', $arrDevice['used']);
      $swapMount->addChild('Size', $arrDevice['total']);
    }
  }
  private function buildFilesystems() {
    $arrFs = $this->sysinfo->filesystems();
    $fs = $this->xml->addChild('FileSystem');
    for ($i = 0, $max = sizeof($arrFs);$i < $max;$i++) {
      $hideMounts = explode(',', hideMounts);
      $hideFstypes = array();
      if (hideFstypes != "") {
        $hideFstypes = explode(',', hideFstypes);
      }
      if (!in_array($arrFs[$i]['mount'], $hideMounts) && !in_array($arrFs[$i]['fstype'], $hideFstypes, true)) {
        $mount = $fs->addChild('Mount');
        $mount->addchild('MountPointID', $i);
        if (showMountPoint === true) {
          $mount->addchild('MountPoint', utf8_encode(trim(htmlspecialchars($arrFs[$i]['mount']))));
        }
        $mount->addchild('Type', $arrFs[$i]['fstype']);
        $dev = $mount->addchild('Device');
        $dev->addChild('Name', utf8_encode(trim(htmlspecialchars($arrFs[$i]['disk']))));
        $mount->addchild('Percent', $arrFs[$i]['percent']);
        $mount->addchild('Free', $arrFs[$i]['free']);
        $mount->addchild('Used', $arrFs[$i]['used']);
        $mount->addchild('Size', $arrFs[$i]['size']);
        if (isset($arrFs[$i]['options'])) {
          $mount->addchild('MountOptions', $arrFs[$i]['options']);
        }
        if (isset($arrFs[$i]['inodes'])) {
          $mount->addchild('Inodes', $arrFs[$i]['inodes']);
        }
      }
    }
  }
  private function buildMbinfo() {
    $mbinfo = $this->xml->addChild('MBinfo');
    $arrBuff = $this->mbinfo->temperature();
    if (sizeof($arrBuff) > 0) {
      $temp = $mbinfo->addChild('Temperature');
      foreach($arrBuff as $arrValue) {
        $item = $temp->addChild('Item');
        $item->addChild('Label', utf8_encode(trim(htmlspecialchars($arrValue['label']))));
        $item->addChild('Value', $arrValue['value']);
        $item->addChild('Limit', $arrValue['limit']);
      }
    }
    $arrBuff = $this->mbinfo->fans();
    if (sizeof($arrBuff) > 0) {
      $fan = $mbinfo->addChild('Fans');
      foreach($arrBuff as $arrValue) {
        $item = $fan->addChild('Item');
        $item->addChild('Label', utf8_encode(trim(htmlspecialchars($arrValue['label']))));
        $item->addChild('Value', $arrValue['value']);
        $item->addChild('Min', $arrValue['min']);
      }
    }
    $arrBuff = $this->mbinfo->voltage();
    if (sizeof($arrBuff) > 0) {
      $volt = $mbinfo->addChild('Voltage');
      foreach($arrBuff as $arrValue) {
        $item = $volt->addChild('Item');
        $item->addChild('Label', utf8_encode(trim(htmlspecialchars($arrValue['label']))));
        $item->addChild('Value', $arrValue['value']);
        $item->addChild('Min', $arrValue['min']);
        $item->addChild('Max', $arrValue['max']);
      }
    }
  }
  private function buildHddtemp() {
    $arrBuf = $this->hddtemp->temperature(hddTemp);
    $hddtemp = $this->xml->addChild('HDDTemp');
    for ($i = 0, $max = sizeof($arrBuf);$i < $max;$i++) {
      $item = $hddtemp->addChild('Item');
      $item->addChild('Label', utf8_encode(trim(htmlspecialchars($arrBuf[$i]['label']))));
      $item->addChild('Value', $arrBuf[$i]['value']);
      $item->addChild('Model', utf8_encode(trim(htmlspecialchars($arrBuf[$i]['model']))));
    }
  }
  private function buildUpsinfo() {
    $arrBuf = $this->upsinfo->info();
    if (isset($arrBuf) && !empty($arrBuf)) {
      $upsinfo = $this->xml->addChild('UPSinfo');
      for ($i = 0, $max = sizeof($arrBuf);$i < $max;$i++) {
        $item = $upsinfo->addChild('Ups');
        $item->addChild('Name', utf8_encode(trim(htmlspecialchars($arrBuf[$i]['name']))));
        $item->addChild('Model', utf8_encode(trim(htmlspecialchars($arrBuf[$i]['model']))));
        $item->addChild('Mode', utf8_encode(trim(htmlspecialchars($arrBuf[$i]['mode']))));
        $item->addChild('StartTime', utf8_encode(trim(htmlspecialchars($arrBuf[$i]['start_time']))));
        $item->addChild('Status', utf8_encode(trim(htmlspecialchars($arrBuf[$i]['status']))));
        $item->addChild('UPSTemperature', utf8_encode(trim(htmlspecialchars($arrBuf[$i]['temperature']))));
        $item->addChild('OutagesCount', $arrBuf[$i]['outages_count']);
        $item->addChild('LastOutage', utf8_encode(trim(htmlspecialchars($arrBuf[$i]['last_outage']))));
        $item->addChild('LastOutageFinish', utf8_encode(trim(htmlspecialchars($arrBuf[$i]['last_outage_finish']))));
        $item->addChild('LineVoltage', $arrBuf[$i]['line_voltage']);
        $item->addChild('LoadPercent', $arrBuf[$i]['load_percent']);
        $item->addChild('BatteryVoltage', $arrBuf[$i]['battery_voltage']);
        $item->addChild('BatteryChargePercent', $arrBuf[$i]['battery_charge_percent']);
        $item->addChild('TimeLeftMinutes', $arrBuf[$i]['time_left_minutes']);
      }
    }
  }
  public function buildXml() {
    if (!$this->plugin_request || $this->complete_request) {
      $this->buildVitals();
      $this->buildNetwork();
      $this->buildHardware();
      $this->buildMemory();
      $this->buildFilesystems();
      if (PSI_MBINFO) {
        $this->buildMbinfo();
      }
      if (PSI_HDDTEMP) {
        $this->buildHddtemp();
      }
      if (PSI_UPSINFO) {
        $this->buildUpsinfo();
      }
    }
    if ($this->plugin_request || $this->complete_request) {
      $this->buildPlugins();
    }
    $this->errors->ErrorsAddToXML($this->xml);
  }
  public function printXml() {
    header("Content-Type: text/xml\n\n");
    echo $this->xml->asXML();
  }
  public function getXml() {
    return $this->xml->asXML();
  }
  private function buildPlugins() {
    if (defined('PSI_PLUGINS')) {
      $pluginroot = $this->xml->addChild("Plugins");
      foreach($this->plugins as $plugin) {
        $object = new $plugin();
        $object->execute();
        $this->combinexml($pluginroot, $object->xml());
      }
    }
  }
  private function combinexml(SimpleXMLElement $parent, SimpleXMLElement $new_child) {
    $node1 = dom_import_simplexml($parent);
    $dom_sxe = dom_import_simplexml($new_child);
    $node2 = $node1->ownerDocument->importNode($dom_sxe, true);
    $node1->appendChild($node2);
  }
}
?>
