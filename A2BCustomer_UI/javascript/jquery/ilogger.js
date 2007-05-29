/**
 * Interface Elements for jQuery
 * Logger
 * 
 * http://interface.eyecon.ro
 * 
 * Copyright (c) 2006 Stefan Petre
 * Dual licensed under the MIT (MIT-LICENSE.txt) 
 * and GPL (GPL-LICENSE.txt) licenses.
 *   
 * $Revision: 1.2 $
 * $Log: ilogger.js,v $
 * Revision 1.2  2006/09/07 05:19:03  Stef
 * *** empty log message ***
 *
 * Revision 1.1  2006/09/07 05:18:04  Stef
 * *** empty log message ***
 *
 * 
 */

jQuery.iLogger = {
	options : {
		background: '#eee',
		types : {
			notice : '#FEFFBF', 
			warning: '#FFAE5F',
			error: '#FF5F3F'
		},
		top: 100,
		right: 0,
		bottom: false,
		left: false,
		width: 400,
		height: 300
	},
	startTime : null,
	lastTime : null,
	logger : null,
	loggerContent : null,
	loggerTypes : null,
	loggerToggler : null,
	loggerClose : null,
	paused : false,
	
	showLogs : function(e)
	{
		logType = jQuery(this).attr('logType');
		
		if(logType) {
			jQuery('.iLogger_' + logType, jQuery.iLogger.loggerContent.get(0)).show();
			jQuery(this).css('textDecoration', 'none');
		}
	},
	
	hideLogs : function(e)
	{
		logType = jQuery(this).attr('logType');
		
		if(logType) {
			jQuery('.iLogger_' + logType, jQuery.iLogger.loggerContent.get(0)).hide();
			jQuery(this).css('textDecoration', 'line-through');
		}
	},
	
	toggleContent : function(e)
	{
		if (jQuery.iLogger.loggerContent.css('display') == 'none') {
			jQuery.iLogger.showContent();
		} else {
			jQuery.iLogger.hideContent();
		}
	},
	
	hideContent : function()
	{
		jQuery.iLogger.loggerContent.hide();
		jQuery.iLogger.loggerTypes.hide();
		jQuery.iLogger.loggerToggler.html('[+]');
	},
	
	showContent : function()
	{
		jQuery.iLogger.loggerContent.show();
		jQuery.iLogger.loggerTypes.show();
		jQuery.iLogger.loggerToggler.html('[-]');
	},
	hideLogger : function ()
	{
		jQuery.iLogger.logger.hide();
	},
	
	pauseLogging : function()
	{
		jQuery.iLogger.paused = true;
	},
	
	resumeLogging : function()
	{
		jQuery.iLogger.paused = false;
	},
	
	clearLogs : function()
	{
		jQuery.iLogger.loggerContent.empty();
	},
	
	toggleFunction : function(el)
	{
		functionCode = jQuery(el).next();
		if (functionCode.css('display') == 'none') {
			functionCode.css('display', 'block');
			jQuery(el).html('[-]');
		} else {
			functionCode.css('display', 'none');
			jQuery(el).html('[+]');
		}
	},
	
	log : function(message, type, source)
	{
		if (jQuery.iLogger.options.types[type] && jQuery.iLogger.paused == false) {
			now = new Date();
			diffTime = now - jQuery.iLogger.startTime;
			diffTimeLast = now - jQuery.iLogger.lastTime;
			dateString = now.toLocaleDateString();
			timeString = now.toLocaleTimeString();
			jQuery.iLogger.lastTime = now;
			jQuery.iLogger.loggerContent.append(
				'<p style="background-color: ' + jQuery.iLogger.options.types[type] + '; padding: 5px; margin: 0 0 1px 0;" class="iLogger_' + type + '">'+
					'<strong>' + type + '</strong> ' + diffTime + 'ms (' + diffTimeLast + 'ms )<br />' + 
					timeString + ' ' + dateString + '<br />'+
					(source ? '<strong>' + source + '</strong>:' : '') + message + 
				'</p>'
			);
			jQuery.iLogger.loggerContent.get(0).scrollTop = jQuery.iLogger.loggerContent.get(0).scrollHeight;
		}
	},
	
	logObj : function(el)
	{
		if (!el) {
			this.log(el == null ? 'null' : 'empty', 'notice', typeof el);
		} else if (el.tagName) {
			this.log(el.toString(), 'notice', el.tagName);
		} else if (el.constructor) {
			consName = el.constructor.toString();
			end = consName.indexOf('(');
			realName = consName.substring(9,end);
			
			if (el.constructor == Function) {
				functionString = el.toString()
				functionName = functionString.substring(9,functionString.indexOf('('));
				if (functionName == '') {
					functionName = 'Anonymous';
				}
				message = functionName + ' <a href="#" onclick="jQuery.iLogger.toggleFunction(this)">[+]</a><code style="display: none;">' + functionString + '</code>';
			} else {
				message = el.toString();
			}
			this.log(message, 'notice', realName);
		}
	},
	
	handler: function (msg,url,l)
	{
		jQuery.iLogger.log("Javascript: " + msg, 'error', url + ' line: ' + l);
		return true
	},
	
	build : function (options){
		for (i in options) {
			this.options[i] = options[i];
		}
		logger = 
			'<div id="iLogger" style="position: absolute; background-color: '+ this.options.background + ';' +
			'padding: 10px;font-family: \'Courier New\', Courier, monospace; font-size: 11px;' + 
			(typeof this.options.top == 'number' ? 'top:' + this.options.top + 'px;':'')+
			(typeof this.options.right == 'number' ? 'right:' + this.options.right + 'px;':'')+
			(typeof this.options.bottom == 'number' ? 'bottom:' + this.options.bottom + 'px;':'')+
			(typeof this.options.left == 'number' ? 'left:' + this.options.left + 'px;':'')+
			'">'+
			'<a href="#" style="color: #000; text-decoration: none;" id="iLoggerToggle">[-]</a>' +
			' <a href="#" style="color: #000; text-decoration: none;" id="iLoggerClose">[X]</a>' +  
			'<div id="iLoggerLogs" style="overflow: auto; margin-top: 10px;background-color: #fff; color: #000;'+
			(typeof this.options.width == 'number' ? 'width:' + this.options.width + 'px;':'')+
			(typeof this.options.height == 'number' ? 'height:' + this.options.height + 'px;':'')+
			'"></div>' + 
			'<div id="iLogerTypes" style="padding-top: 10px;">';
		for (i in this.options.types) {
			logger += 
				'<a href="#" id="iLogger_' + i + '"' +
				'style="color: #000; background-color: ' + this.options.types[i] + '; text-decoration: none;">' +
				i + '</a> ';
		}
		logger += 
			'</div>'+
			'</div>';
		$('body', document).append(logger);
		
		jQuery.iLogger.logger = jQuery('#iLogger');
		jQuery.iLogger.loggerContent = jQuery('#iLoggerLogs', jQuery.iLogger.logger.get(0));
		jQuery.iLogger.loggerTypes = jQuery('#iLogerTypes', jQuery.iLogger.logger.get(0));
		jQuery.iLogger.loggerToggler = jQuery('#iLoggerToggle').click(jQuery.iLogger.toggleContent);
		jQuery.iLogger.loggerClose = jQuery('#iLoggerClose').click(jQuery.iLogger.hideLogger);
		for (i in this.options.types) {
			$('#iLogger_' + i).attr('logType', i).toggle(
				jQuery.iLogger.hideLogs,
				jQuery.iLogger.showLogs
			);
		}
		jQuery.iLogger.startTime = jQuery.iLogger.lastTime = new Date();
	}
};

onerror = jQuery.iLogger.handler;