<?php
// Run after composer install. Publish the official Breeze auth PHP scaffolding only;
// the project already supplies plain-CSS Blade views and explicit auth routes.
$root=dirname(__DIR__);
$source=$root.'/vendor/laravel/breeze/stubs/default/app/Http';
if(!is_dir($source)){fwrite(STDERR,"Breeze stubs not found. Run composer install INCLUDING dev dependencies first.\n");exit(1);}
$copy=function(string $from,string $to) use (&$copy): void {
 if(!is_dir($from)){throw new RuntimeException('Missing Breeze source directory: '.$from);}
 if(!is_dir($to)){mkdir($to,0775,true);}
 foreach(new DirectoryIterator($from) as $entry){
  if($entry->isDot()){continue;}
  $target=$to.'/'.$entry->getFilename();
  if($entry->isDir()){$copy($entry->getPathname(),$target);}else{
   $contents=file_get_contents($entry->getPathname());
   // Breeze versions may use either {{ namespace }} or App in their stubs.
   $contents=str_replace(['{{ namespace }}','{{namespace}}'],'App',$contents);
   if(file_put_contents($target,$contents)===false){throw new RuntimeException('Cannot write '.$target);}
  }
 }
};
$copy($source.'/Controllers/Auth',$root.'/app/Http/Controllers/Auth');
$copy($source.'/Requests/Auth',$root.'/app/Http/Requests/Auth');
echo "Official Laravel Breeze authentication controllers and throttled LoginRequest installed.\n";
echo "Project auth routes and plain-CSS views retained; no npm build is needed.\n";
