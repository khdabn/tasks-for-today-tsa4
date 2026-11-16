<?php
return [
 'default'=>env('MAIL_MAILER','log'),
 'mailers'=>[
  'log'=>['transport'=>'log','channel'=>env('MAIL_LOG_CHANNEL','mail')],
  'array'=>['transport'=>'array'],
  'smtp'=>['transport'=>'smtp','scheme'=>env('MAIL_SCHEME'),'host'=>env('MAIL_HOST','127.0.0.1'),'port'=>env('MAIL_PORT',2525),
   'username'=>env('MAIL_USERNAME'),'password'=>env('MAIL_PASSWORD'),'timeout'=>15,'local_domain'=>env('MAIL_EHLO_DOMAIN','localhost')],
 ],
 'from'=>['address'=>env('MAIL_FROM_ADDRESS','tasks@example.com'),'name'=>env('MAIL_FROM_NAME','Tasks for Today')],
];
