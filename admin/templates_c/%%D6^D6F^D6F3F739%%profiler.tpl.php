<?php /* Smarty version 2.6.25-dev, created on 2010-10-25 13:05:25
         compiled from profiler.tpl */ ?>
<?php  
        global $profiler;
        global $G_instance_Query_trace;

        if ($profiler->installed && $profiler->modedebug)
                $profiler->display($G_instance_Query_trace);
 ?>
