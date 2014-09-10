<?php /* Smarty version 2.6.25-dev, created on 2014-05-06 11:44:23
         compiled from profiler.tpl */ ?>
<?php  
        global $profiler;
        global $G_instance_Query_trace;

        if ($profiler->installed && $profiler->modedebug)
                $profiler->display($G_instance_Query_trace);
 ?>
